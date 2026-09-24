<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Skpd;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Auth;

class EksekutifController extends Controller
{
    /**
     * Tampilan Utama Executive Command Center (KDH & Sekda View)
     */
    public function index(Request $request)
    {
        $tahunAktif = session('tahun_login') ?? date('Y');
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $currentMonth = (int)date('n');
        $defaultBulan = ($tahunAktif < date('Y')) ? 12 : ($currentMonth > 1 ? $currentMonth - 1 : 1);
        $bulanAktif = (int)$request->query('bulan', $defaultBulan);
        if ($bulanAktif < 1 || $bulanAktif > 12) {
            $bulanAktif = $defaultBulan;
        }

        // Ambil Pengaturan Global
        $pengaturanGlobal = Pengaturan::whereNull('skpd_id')->first();

        // 1. Ambil seluruh SKPD Aktif
        $allSkpds = Skpd::where('status', true)->orderBy('kode', 'asc')->get();
        $totalSkpdCount = $allSkpds->count();

        // 2. Ambil transaksi pada periode bulan & tahun aktif
        $transaksis = Transaksi::with(['skpd', 'user'])
            ->where('periode_tahun', $tahunAktif)
            ->where('periode_bulan', $bulanAktif)
            ->get();

        // Kelompokkan transaksi per SKPD (1 SKPD bisa memiliki multi rekening)
        $trxGrouped = $transaksis->groupBy('skpd_id');

        $skpdMatrix = [];
        $totalBku = 0;
        $totalBank = 0;
        $totalSelisih = 0;
        $countKlop = 0;
        $countDraft = 0;
        $countSelisih = 0;
        $countBelumLapor = 0;
        $countTepatWaktu = 0;
        $countBerkasLengkap = 0;
        $skpdSelisihList = [];
        $skpdBelumLaporList = [];

        foreach ($allSkpds as $skpd) {
            $items = $trxGrouped->get($skpd->id);

            if ($items && $items->count() > 0) {
                $bkuSum = $items->sum('bku_saldo_akhir');
                $bankSum = $items->sum('bank_saldo_akhir');
                $diff = round(abs($bkuSum - $bankSum), 2);

                $totalBku += $bkuSum;
                $totalBank += $bankSum;
                $totalSelisih += $diff;

                // Tentukan status verifikasi tertinggi
                $hasVerified = $items->every(fn($t) => $t->status_verifikasi === 'verified');
                $isDraft = !$hasVerified;

                // Kelengkapan berkas fisik rata-rata (4 berkas)
                $fileCount = 0;
                foreach ($items as $t) {
                    if ($t->file_ba_manual) $fileCount++;
                    if ($t->file_bku) $fileCount++;
                    if ($t->file_buku_pembantu_bank) $fileCount++;
                    if ($t->file_rekening_koran) $fileCount++;
                }
                $maxPossibleFiles = $items->count() * 4;
                $isAllFiles = ($maxPossibleFiles > 0 && $fileCount === $maxPossibleFiles);
                if ($isAllFiles) $countBerkasLengkap++;

                // Kedisiplinan tanggal (created_at <= 10)
                $firstCreated = $items->min('created_at');
                $isOntime = $firstCreated && $firstCreated->day <= 10;
                if ($isOntime) $countTepatWaktu++;

                if ($hasVerified && $diff == 0) {
                    $statusBadge = 'klop';
                    $statusLabel = 'KLOP (Verified)';
                    $countKlop++;
                } elseif ($hasVerified && $diff > 0) {
                    $statusBadge = 'selisih';
                    $statusLabel = 'Selisih Kas';
                    $countSelisih++;
                    $skpdSelisihList[] = [
                        'nama' => $skpd->nama,
                        'kode' => $skpd->kode,
                        'selisih' => $diff,
                        'bku' => $bkuSum,
                        'bank' => $bankSum,
                        'no_wa' => $skpd->no_whatsapp,
                    ];
                } else {
                    $statusBadge = 'draft';
                    $statusLabel = 'Dalam Proses / Draft';
                    $countDraft++;
                }

                $skpdMatrix[] = [
                    'id' => $skpd->id,
                    'kode' => $skpd->kode,
                    'nama' => $skpd->nama,
                    'bendahara' => $skpd->nama_bendahara ?? '-',
                    'no_wa' => $skpd->no_whatsapp,
                    'status' => $statusBadge,
                    'status_label' => $statusLabel,
                    'bku' => $bkuSum,
                    'bank' => $bankSum,
                    'selisih' => $diff,
                    'is_ontime' => $isOntime,
                    'tanggal_lapor' => $firstCreated ? $firstCreated->format('d M Y') : '-',
                    'berkas_persen' => $maxPossibleFiles > 0 ? round(($fileCount / $maxPossibleFiles) * 100) : 0,
                    'rekening_count' => $items->count(),
                ];
            } else {
                $countBelumLapor++;
                $skpdBelumLaporList[] = [
                    'nama' => $skpd->nama,
                    'kode' => $skpd->kode,
                    'bendahara' => $skpd->nama_bendahara ?? '-',
                    'no_wa' => $skpd->no_whatsapp,
                ];

                $skpdMatrix[] = [
                    'id' => $skpd->id,
                    'kode' => $skpd->kode,
                    'nama' => $skpd->nama,
                    'bendahara' => $skpd->nama_bendahara ?? '-',
                    'no_wa' => $skpd->no_whatsapp,
                    'status' => 'belum',
                    'status_label' => 'Belum Lapor',
                    'bku' => 0,
                    'bank' => 0,
                    'selisih' => 0,
                    'is_ontime' => false,
                    'tanggal_lapor' => '-',
                    'berkas_persen' => 0,
                    'rekening_count' => 0,
                ];
            }
        }

        // 3. Kalkulasi Persentase KPI Makro
        $kepatuhanRate = $totalSkpdCount > 0 ? round(($countKlop / $totalSkpdCount) * 100, 1) : 0;
        $timelinessRate = $totalSkpdCount > 0 ? round(($countTepatWaktu / $totalSkpdCount) * 100, 1) : 0;
        $auditReadinessRate = $totalSkpdCount > 0 ? round(($countBerkasLengkap / max(1, ($totalSkpdCount - $countBelumLapor))) * 100, 1) : 0;

        // 4. Decision Support System (Rekomendasi Kebijakan Cerdas untuk KDH / Sekda)
        $rekomendasiKebijakan = [];

        if ($countSelisih > 0) {
            $rekomendasiKebijakan[] = [
                'tipe' => 'danger',
                'icon' => 'error',
                'judul' => 'Instruksi Asistensi Selisih Kas Segera',
                'pesan' => "Terdeteksi {$countSelisih} SKPD memiliki selisih fisik antara BKU dan Rekening Koran dengan akumulasi nominal Rp " . number_format($totalSelisih, 2, ',', '.') . ". Disarankan Sekretaris Daerah menginstruksikan Tim Konsolidator BKAD & Inspektorat melakukan audit penelusuran (rekonsiliasi internal).",
            ];
        }

        if ($countBelumLapor > 0) {
            $pctBelum = round(($countBelumLapor / $totalSkpdCount) * 100);
            $rekomendasiKebijakan[] = [
                'tipe' => 'warning',
                'icon' => 'schedule',
                'judul' => 'Penerbitan Surat Peringatan / Teguran Keterlambatan',
                'pesan' => "Terdapat {$countBelumLapor} SKPD ({$pctBelum}%) yang belum menyelesaikan rekonsiliasi kas untuk periode {$namaBulan[$bulanAktif - 1]} {$tahunAktif}. Disarankan penerbitan Surat Peringatan Tertulis Sekretaris Daerah guna percepatan kepatuhan pelaporan pertanggungjawaban kas.",
            ];
        }

        if ($kepatuhanRate >= 90) {
            $rekomendasiKebijakan[] = [
                'tipe' => 'success',
                'icon' => 'verified',
                'judul' => 'Tingkat Kepatuhan Sangat Baik (Predikat A)',
                'pesan' => "Kepatuhan rekonsiliasi kas daerah periode {$namaBulan[$bulanAktif - 1]} mencapai {$kepatuhanRate}%. Sebagian besar SKPD telah tertib administrasi dan saldo kas terbukti klop tanpa selisih.",
            ];
        }

        // Likuiditas Idle Cash Note
        $rekomendasiKebijakan[] = [
            'tipe' => 'info',
            'icon' => 'account_balance',
            'judul' => 'Monitoring Likuiditas Kas Pemda di Bank Kalsel',
            'pesan' => "Total likuiditas kas daerah yang tersebar di bendahara pengeluaran saat ini tercatat Rp " . number_format($totalBank, 2, ',', '.') . ". Pastikan kas yang mengendap sesuai dengan rencana penyerapan anggaran operasional triwulan.",
        ];

        // 5. Tren 12 Bulan Likuiditas Kas Pemda (Jan - Des)
        $chart12Bulan = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            'bku' => array_fill(0, 12, 0),
            'bank' => array_fill(0, 12, 0),
            'kepatuhan' => array_fill(0, 12, 0),
        ];

        $allYearTrx = Transaksi::where('periode_tahun', $tahunAktif)->get();
        for ($m = 1; $m <= 12; $m++) {
            $mTrx = $allYearTrx->where('periode_bulan', $m);
            if ($mTrx->count() > 0) {
                $chart12Bulan['bku'][$m - 1] = $mTrx->sum('bku_saldo_akhir');
                $chart12Bulan['bank'][$m - 1] = $mTrx->sum('bank_saldo_akhir');
                
                // SKPD klop pada bulan m
                $mVerifiedCount = $mTrx->where('status_verifikasi', 'verified')
                    ->filter(fn($t) => round(abs($t->bku_saldo_akhir - $t->bank_saldo_akhir), 2) == 0)
                    ->pluck('skpd_id')->unique()->count();
                $chart12Bulan['kepatuhan'][$m - 1] = $totalSkpdCount > 0 ? round(($mVerifiedCount / $totalSkpdCount) * 100) : 0;
            }
        }

        // 6. Leaderboard Kinerja Kedisiplinan SKPD (Top 5 & Bottom 5 EWS)
        $skpdsScored = $allSkpds->map(function ($skpd) use ($allYearTrx) {
            $skpdTrx = $allYearTrx->where('skpd_id', $skpd->id);
            $totalTrx = $skpdTrx->count();
            $verifiedTrx = $skpdTrx->where('status_verifikasi', 'verified')->count();
            $selisihCount = $skpdTrx->filter(fn($t) => round(abs($t->bku_saldo_akhir - $t->bank_saldo_akhir), 2) > 0)->count();

            $totalScore = 0;
            foreach ($skpdTrx as $trx) {
                $day = $trx->created_at ? $trx->created_at->day : 15;
                if ($day <= 5) $totalScore += 100;
                elseif ($day <= 10) $totalScore += 85;
                elseif ($day <= 15) $totalScore += 65;
                else $totalScore += 40;

                if ($trx->status_verifikasi === 'verified' && round(abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir), 2) == 0) {
                    $totalScore += 20;
                }
            }

            $score = $totalTrx > 0 ? min(100, round(($totalScore / ($totalTrx * 120)) * 100)) : 0;

            $skpd->skor_kinerja = $score;
            $skpd->verified_count = $verifiedTrx;
            $skpd->selisih_count = $selisihCount;
            return $skpd;
        });

        $top5Skpd = $skpdsScored->sortByDesc(fn($s) => ($s->verified_count * 1000) + $s->skor_kinerja - ($s->selisih_count * 50))->take(5)->values();
        $bottom5Skpd = $skpdsScored->sortBy(fn($s) => ($s->verified_count * 1000) + $s->skor_kinerja - ($s->selisih_count * 100))->take(5)->values();

        return view('eksekutif.index', compact(
            'tahunAktif',
            'bulanAktif',
            'namaBulan',
            'totalSkpdCount',
            'totalBku',
            'totalBank',
            'totalSelisih',
            'countKlop',
            'countDraft',
            'countSelisih',
            'countBelumLapor',
            'kepatuhanRate',
            'timelinessRate',
            'auditReadinessRate',
            'skpdMatrix',
            'skpdSelisihList',
            'skpdBelumLaporList',
            'rekomendasiKebijakan',
            'chart12Bulan',
            'top5Skpd',
            'bottom5Skpd',
            'pengaturanGlobal'
        ));
    }

    /**
     * Cetak Lembar Ringkasan Eksekutif (Executive Briefing Document) untuk Bupati / Sekda
     */
    public function cetakBrief(Request $request)
    {
        $tahunAktif = session('tahun_login') ?? date('Y');
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $currentMonth = (int)date('n');
        $defaultBulan = ($tahunAktif < date('Y')) ? 12 : ($currentMonth > 1 ? $currentMonth - 1 : 1);
        $bulanAktif = (int)$request->query('bulan', $defaultBulan);

        $pengaturanGlobal = Pengaturan::whereNull('skpd_id')->first();
        $allSkpds = Skpd::where('status', true)->orderBy('kode', 'asc')->get();
        $totalSkpdCount = $allSkpds->count();

        $transaksis = Transaksi::with(['skpd', 'user'])
            ->where('periode_tahun', $tahunAktif)
            ->where('periode_bulan', $bulanAktif)
            ->get();

        $trxGrouped = $transaksis->groupBy('skpd_id');

        $totalBku = 0;
        $totalBank = 0;
        $totalSelisih = 0;
        $countKlop = 0;
        $countDraft = 0;
        $countSelisih = 0;
        $countBelumLapor = 0;
        $skpdRows = [];

        foreach ($allSkpds as $skpd) {
            $items = $trxGrouped->get($skpd->id);
            if ($items && $items->count() > 0) {
                $bkuSum = $items->sum('bku_saldo_akhir');
                $bankSum = $items->sum('bank_saldo_akhir');
                $diff = round(abs($bkuSum - $bankSum), 2);

                $totalBku += $bkuSum;
                $totalBank += $bankSum;
                $totalSelisih += $diff;

                $hasVerified = $items->every(fn($t) => $t->status_verifikasi === 'verified');
                if ($hasVerified && $diff == 0) {
                    $status = 'KLOP';
                    $countKlop++;
                } elseif ($hasVerified && $diff > 0) {
                    $status = 'SELISIH';
                    $countSelisih++;
                } else {
                    $status = 'PROSES';
                    $countDraft++;
                }

                $skpdRows[] = [
                    'kode' => $skpd->kode,
                    'nama' => $skpd->nama,
                    'bendahara' => $skpd->nama_bendahara ?? '-',
                    'bku' => $bkuSum,
                    'bank' => $bankSum,
                    'selisih' => $diff,
                    'status' => $status,
                ];
            } else {
                $countBelumLapor++;
                $skpdRows[] = [
                    'kode' => $skpd->kode,
                    'nama' => $skpd->nama,
                    'bendahara' => $skpd->nama_bendahara ?? '-',
                    'bku' => 0,
                    'bank' => 0,
                    'selisih' => 0,
                    'status' => 'BELUM',
                ];
            }
        }

        $kepatuhanRate = $totalSkpdCount > 0 ? round(($countKlop / $totalSkpdCount) * 100, 1) : 0;

        return view('eksekutif.cetak-brief', compact(
            'tahunAktif',
            'bulanAktif',
            'namaBulan',
            'totalSkpdCount',
            'totalBku',
            'totalBank',
            'totalSelisih',
            'countKlop',
            'countDraft',
            'countSelisih',
            'countBelumLapor',
            'kepatuhanRate',
            'skpdRows',
            'pengaturanGlobal'
        ));
    }
}
