<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skpd;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    public function tree(Request $request)
    {
        // Hanya Admin dan Konsolidator yang boleh mengakses
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator'])) {
            abort(403);
        }

        $tahunAktif = session('tahun_login') ?? date('Y');

        $query = Skpd::whereHas('transaksis', function ($q) use ($tahunAktif) {
            $q->where('periode_tahun', $tahunAktif);
        })->with(['transaksis' => function ($q) use ($tahunAktif) {
            $q->where('periode_tahun', $tahunAktif)
              ->orderBy('periode_bulan', 'asc');
        }, 'transaksis.rekening']);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->has('filter_status') && $request->filter_status != '') {
            if ($request->filter_status === 'kurang') {
                $query->whereHas('transaksis', function ($q) use ($tahunAktif) {
                    $q->where('periode_tahun', $tahunAktif)
                      ->where(function($sub) {
                          $sub->whereNull('file_ba_manual')
                              ->orWhereNull('file_buku_kas')
                              ->orWhereNull('file_buku_pembantu_bank')
                              ->orWhereNull('file_rekening_koran');
                      });
                });
            } elseif ($request->filter_status === 'lengkap') {
                $query->whereDoesntHave('transaksis', function ($q) use ($tahunAktif) {
                    $q->where('periode_tahun', $tahunAktif)
                      ->where(function($sub) {
                          $sub->whereNull('file_ba_manual')
                              ->orWhereNull('file_buku_kas')
                              ->orWhereNull('file_buku_pembantu_bank')
                              ->orWhereNull('file_rekening_koran');
                      });
                });
            }
        }

        // Mengambil SKPD yang memiliki transaksi di tahun aktif dengan paginasi
        $skpds = $query->orderBy('nama')->paginate(10)->withQueryString();

        // Kita akan melakukan grouping di Controller agar View lebih ringan.
        $treeData = [];

        foreach ($skpds as $skpd) {
            $totalTransaksi = 0;
            $totalDokumenMissing = 0;
            $totalDraft = 0;
            $totalVerified = 0;

            $treeData[$skpd->id] = [
                'nama' => $skpd->nama,
                'rekenings' => [],
                'stats' => []
            ];

            foreach ($skpd->transaksis as $trx) {
                if (!$trx->rekening) continue;
                
                $totalTransaksi++;

                if ($trx->status_verifikasi == 'verified') {
                    $totalVerified++;
                } else {
                    $totalDraft++;
                }

                // Cek dokumen yang belum di-upload
                $docMissing = 0;
                if (!$trx->file_ba_manual) $docMissing++;
                if (!$trx->file_buku_kas) $docMissing++;
                if (!$trx->file_buku_pembantu_bank) $docMissing++;
                if (!$trx->file_rekening_koran) $docMissing++;
                
                $totalDokumenMissing += $docMissing;
                
                $rekId = $trx->rekening_id;
                if (!isset($treeData[$skpd->id]['rekenings'][$rekId])) {
                    $treeData[$skpd->id]['rekenings'][$rekId] = [
                        'nama' => $trx->rekening->nama . ' (' . $trx->rekening->nomor . ') - ' . $trx->rekening->bank,
                        'transaksis' => []
                    ];
                }

                $treeData[$skpd->id]['rekenings'][$rekId]['transaksis'][$trx->periode_bulan] = $trx;
            }

            // Simpan ringkasan status di level SKPD
            $treeData[$skpd->id]['stats'] = [
                'transaksi' => $totalTransaksi,
                'missing_docs' => $totalDokumenMissing,
                'draft' => $totalDraft,
                'verified' => $totalVerified
            ];
        }

        $namaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Meneruskan variabel $skpds untuk memunculkan link pagination di view
        return view('dokumen.tree', compact('treeData', 'tahunAktif', 'namaBulan', 'skpds'));
    }
    public function downloadZip(Transaksi $transaksi)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator'])) {
            abort(403);
        }

        $zip = new \ZipArchive();
        
        $skpdName = $transaksi->skpd->nama ?? 'SKPD';
        $bulan = str_pad($transaksi->periode_bulan, 2, '0', STR_PAD_LEFT);
        $tahun = $transaksi->periode_tahun;
        $fileName = 'Dokumen_' . \Illuminate\Support\Str::slug($skpdName) . '_' . $bulan . '_' . $tahun . '.zip';
        $zipPath = storage_path('app/public/' . $fileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $files = [
                'BA_Manual' => $transaksi->file_ba_manual,
                'Buku_Kas' => $transaksi->file_buku_kas,
                'Buku_Pembantu_Bank' => $transaksi->file_buku_pembantu_bank,
                'Rekening_Koran' => $transaksi->file_rekening_koran,
            ];

            $hasFiles = false;
            foreach ($files as $name => $path) {
                if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                    $zip->addFile(storage_path('app/public/' . $path), $name . '.' . $extension);
                    $hasFiles = true;
                }
            }

            $zip->close();

            if ($hasFiles) {
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }
        }

        return back()->with('error', 'Tidak ada dokumen yang bisa didownload atau zip gagal dibuat.');
    }
}
