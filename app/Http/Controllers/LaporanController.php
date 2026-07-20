<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Skpd;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan rekapitulasi tahunan per SKPD
     */
    public function rekapTahunan(Request $request)
    {
        $user = Auth::user();
        $tahunAktif = session('tahun_login') ?? date('Y');
        
        $skpds = [];
        $selectedSkpdId = $request->skpd_id;

        if ($user->skpd_id) {
            // Operator hanya bisa melihat SKPD-nya sendiri
            $selectedSkpdId = $user->skpd_id;
            $selectedSkpd = Skpd::find($selectedSkpdId);
        } else {
            // Admin bisa memilih SKPD
            $skpds = Skpd::where('status', true)->orderBy('nama')->get();
            $selectedSkpd = $selectedSkpdId ? Skpd::find($selectedSkpdId) : null;
        }

        $rekapData = [];
        if ($selectedSkpdId) {
            $transaksis = Transaksi::where('skpd_id', $selectedSkpdId)
                ->where('periode_tahun', $tahunAktif)
                ->orderBy('periode_bulan', 'asc')
                ->get()
                ->keyBy('periode_bulan');
            
            for ($i = 1; $i <= 12; $i++) {
                $trx = $transaksis->get($i);
                $rekapData[$i] = [
                    'bulan' => $i,
                    'bku' => $trx ? $trx->bku_saldo_akhir : null,
                    'bank' => $trx ? $trx->bank_saldo_akhir : null,
                    'selisih' => $trx ? abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) : null,
                    'status' => $trx ? $trx->status_verifikasi : null,
                    'is_exist' => $trx ? true : false,
                ];
            }
        }

        return view('laporan.rekap_tahunan', compact('skpds', 'selectedSkpdId', 'selectedSkpd', 'rekapData', 'tahunAktif'));
    }

    /**
     * Mencetak PDF Rekapitulasi Tahunan per SKPD
     */
    public function cetakRekapTahunan(Request $request)
    {
        $user = Auth::user();
        $tahunAktif = session('tahun_login') ?? date('Y');
        $selectedSkpdId = $request->skpd_id;

        if ($user->skpd_id) {
            $selectedSkpdId = $user->skpd_id;
        }

        if (!$selectedSkpdId) {
            return back()->with('error', 'Pilih SKPD terlebih dahulu untuk mencetak rekapitulasi.');
        }

        $skpd = Skpd::findOrFail($selectedSkpdId);
        $pengaturan = \App\Models\Pengaturan::first();

        $transaksis = Transaksi::where('skpd_id', $selectedSkpdId)
                ->where('periode_tahun', $tahunAktif)
                ->orderBy('periode_bulan', 'asc')
                ->get()
                ->keyBy('periode_bulan');

        $rekapData = [];
        $totalBku = 0;
        $totalBank = 0;
        
        for ($i = 1; $i <= 12; $i++) {
            $trx = $transaksis->get($i);
            $rekapData[$i] = [
                'bulan' => $i,
                'bku' => $trx ? $trx->bku_saldo_akhir : null,
                'bank' => $trx ? $trx->bank_saldo_akhir : null,
                'selisih' => $trx ? abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) : null,
            ];

            if ($trx) {
                // Untuk rekap total, biasanya dihitung dari saldo bulan terakhir yang ada
                $totalBku = $trx->bku_saldo_akhir;
                $totalBank = $trx->bank_saldo_akhir;
            }
        }

        $pdf = Pdf::loadView('laporan.rekap_tahunan_pdf', compact('skpd', 'rekapData', 'tahunAktif', 'pengaturan', 'totalBku', 'totalBank'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Rekapitulasi_Tahunan_' . $skpd->kode . '_' . $tahunAktif . '.pdf');
    }

    /**
     * Menampilkan daftar tunggakan dan selisih (Khusus Admin)
     */
    public function tunggakan(Request $request)
    {
        $tahunAktif = session('tahun_login') ?? date('Y');
        
        // SKPD dengan Selisih (bku != bank) di tahun aktif
        $skpdSelisihIds = Transaksi::where('periode_tahun', $tahunAktif)
            ->whereRaw('ABS(bku_saldo_akhir - bank_saldo_akhir) > 0')
            ->pluck('skpd_id')
            ->unique();
            
        $skpdDenganSelisih = Skpd::whereIn('id', $skpdSelisihIds)->get();

        // Menyusun daftar selisih per SKPD
        $dataSelisih = [];
        foreach ($skpdDenganSelisih as $skpd) {
            $trxSelisih = Transaksi::where('skpd_id', $skpd->id)
                ->where('periode_tahun', $tahunAktif)
                ->whereRaw('ABS(bku_saldo_akhir - bank_saldo_akhir) > 0')
                ->orderBy('periode_bulan', 'asc')
                ->get();
                
            $dataSelisih[] = [
                'skpd' => $skpd,
                'transaksi' => $trxSelisih
            ];
        }

        // SKPD Terlambat / Belum Lapor
        $currentMonth = (int)date('n');
        $targetMonth = $currentMonth > 1 ? $currentMonth - 1 : 12; // Biasanya wajib lapor bulan sebelumnya
        $targetYear = $currentMonth > 1 ? $tahunAktif : $tahunAktif - 1; // Jika januari, target bulan 12 tahun sebelumnya
        
        $dataTunggakan = [];
        
        // Jika sedang melihat tahun lalu, semua bulan harusnya sudah lapor (hingga bln 12)
        if ($tahunAktif < date('Y')) {
            $targetMonth = 12;
            $targetYear = $tahunAktif;
        }

        if ($targetYear == $tahunAktif) {
            $skpds = Skpd::where('status', true)->get();
            foreach ($skpds as $skpd) {
                $lastTrx = Transaksi::where('skpd_id', $skpd->id)
                    ->where('periode_tahun', $tahunAktif)
                    ->orderBy('periode_bulan', 'desc')
                    ->first();
                
                $lastMonthReported = $lastTrx ? $lastTrx->periode_bulan : 0;
                
                if ($lastMonthReported < $targetMonth) {
                    $dataTunggakan[] = [
                        'skpd' => $skpd,
                        'bulan_terakhir' => $lastMonthReported,
                        'tunggakan_bulan' => $targetMonth - $lastMonthReported
                    ];
                }
            }
        }

        // Urutkan tunggakan terbanyak
        usort($dataTunggakan, function($a, $b) {
            return $b['tunggakan_bulan'] <=> $a['tunggakan_bulan'];
        });

        $perPage = 10;
        
        $tunggakanCollection = collect($dataTunggakan);
        $pageTunggakan = $request->get('page_tunggakan', 1);
        $dataTunggakanPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $tunggakanCollection->forPage($pageTunggakan, $perPage),
            $tunggakanCollection->count(),
            $perPage,
            $pageTunggakan,
            ['path' => $request->url(), 'query' => $request->query(), 'pageName' => 'page_tunggakan']
        );
        
        $selisihCollection = collect($dataSelisih);
        $pageSelisih = $request->get('page_selisih', 1);
        $dataSelisihPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $selisihCollection->forPage($pageSelisih, $perPage),
            $selisihCollection->count(),
            $perPage,
            $pageSelisih,
            ['path' => $request->url(), 'query' => $request->query(), 'pageName' => 'page_selisih']
        );

        return view('laporan.tunggakan', compact('dataSelisihPaginated', 'dataTunggakanPaginated', 'tahunAktif', 'targetMonth'));
    }

    /**
     * Menampilkan laporan konsolidasi seluruh SKPD per bulan
     */
    public function konsolidasi(Request $request)
    {
        $tahunAktif = session('tahun_login') ?? date('Y');
        
        // Default to active month (previous month usually) or the month selected
        $currentMonth = (int)date('n');
        $defaultMonth = $currentMonth > 1 ? $currentMonth - 1 : 12;
        if ($tahunAktif < date('Y')) $defaultMonth = 12;

        $selectedBulan = $request->bulan ?? $defaultMonth;

        $skpdsPaginated = Skpd::where('status', true)->orderBy('kode')->paginate(15);
        $skpdsPaginated->appends($request->all());
        
        $skpdsPaginated->getCollection()->transform(function ($skpd) use ($tahunAktif, $selectedBulan) {
            $trx = Transaksi::where('skpd_id', $skpd->id)
                ->where('periode_tahun', $tahunAktif)
                ->where('periode_bulan', $selectedBulan)
                ->first();

            return [
                'skpd' => $skpd,
                'bku' => $trx ? $trx->bku_saldo_akhir : null,
                'bank' => $trx ? $trx->bank_saldo_akhir : null,
                'selisih' => $trx ? abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) : null,
                'status' => $trx ? $trx->status_verifikasi : null,
                'is_exist' => $trx ? true : false,
            ];
        });

        // Hitung Grand Total dari tabel Transaksi
        $totalBku = Transaksi::where('periode_tahun', $tahunAktif)
            ->where('periode_bulan', $selectedBulan)
            ->whereHas('skpd', function($q) { $q->where('status', true); })
            ->sum('bku_saldo_akhir');
            
        $totalBank = Transaksi::where('periode_tahun', $tahunAktif)
            ->where('periode_bulan', $selectedBulan)
            ->whereHas('skpd', function($q) { $q->where('status', true); })
            ->sum('bank_saldo_akhir');

        $konsolidasiData = $skpdsPaginated;

        return view('laporan.konsolidasi', compact('konsolidasiData', 'tahunAktif', 'selectedBulan', 'totalBku', 'totalBank'));
    }

    /**
     * Mencetak PDF Laporan Konsolidasi
     */
    public function cetakKonsolidasi(Request $request)
    {
        $tahunAktif = session('tahun_login') ?? date('Y');
        $selectedBulan = $request->bulan;

        if (!$selectedBulan) {
            return back()->with('error', 'Pilih bulan terlebih dahulu.');
        }

        $pengaturan = \App\Models\Pengaturan::first();
        $skpds = Skpd::where('status', true)->orderBy('kode')->get();
        
        $konsolidasiData = [];
        $totalBku = 0;
        $totalBank = 0;

        foreach ($skpds as $skpd) {
            $trx = Transaksi::where('skpd_id', $skpd->id)
                ->where('periode_tahun', $tahunAktif)
                ->where('periode_bulan', $selectedBulan)
                ->first();

            $konsolidasiData[] = [
                'skpd' => $skpd,
                'bku' => $trx ? $trx->bku_saldo_akhir : null,
                'bank' => $trx ? $trx->bank_saldo_akhir : null,
                'selisih' => $trx ? abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) : null,
            ];

            if ($trx) {
                $totalBku += $trx->bku_saldo_akhir;
                $totalBank += $trx->bank_saldo_akhir;
            }
        }

        $pdf = Pdf::loadView('laporan.konsolidasi_pdf', compact('konsolidasiData', 'tahunAktif', 'selectedBulan', 'pengaturan', 'totalBku', 'totalBank'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Konsolidasi_Bulan_' . $selectedBulan . '_' . $tahunAktif . '.pdf');
    }
}
