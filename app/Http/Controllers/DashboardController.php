<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Skpd;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Retrieve active year from login session
        $tahunAktif = session('tahun_login') ?? date('Y');

        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Base query for transactions visible to the user
        $query = Transaksi::with(['skpd', 'user'])->where('periode_tahun', $tahunAktif);
        
        if ($user->skpd_id) {
            $query->where('skpd_id', $user->skpd_id);
        }

        // 1. Get the summary for the main metrics (Current Period)
        $summary = [
            'has_data' => false,
            'bku' => 0,
            'bank' => 0,
            'info' => '',
            'is_matched' => true
        ];

        // We keep latestTransaksi for backwards compatibility if needed elsewhere
        $latestTransaksi = null;

        if ($user->skpd_id) {
            $latestTransaksi = (clone $query)->orderBy('periode_bulan', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
            if ($latestTransaksi) {
                $summary['has_data'] = true;
                $summary['bku'] = $latestTransaksi->bku_saldo_akhir;
                $summary['bank'] = $latestTransaksi->bank_saldo_akhir;
                $summary['info'] = 'Periode aktif: ' . $namaBulan[$latestTransaksi->periode_bulan - 1] . ' ' . $latestTransaksi->periode_tahun . ' | ' . ($latestTransaksi->skpd->nama ?? '');
                $summary['is_matched'] = abs($latestTransaksi->bku_saldo_akhir - $latestTransaksi->bank_saldo_akhir) < 0.01;
            }
        } else {
            $allTransactions = (clone $query)->orderBy('periode_bulan', 'asc')->orderBy('created_at', 'asc')->get();
            $latestPerSkpd = [];
            foreach ($allTransactions as $trx) {
                $latestPerSkpd[$trx->skpd_id] = $trx;
            }
            if (count($latestPerSkpd) > 0) {
                $summary['has_data'] = true;
                $summary['info'] = 'Akumulasi Saldo Terakhir Seluruh SKPD (' . $tahunAktif . ')';
                foreach ($latestPerSkpd as $trx) {
                    $summary['bku'] += $trx->bku_saldo_akhir;
                    $summary['bank'] += $trx->bank_saldo_akhir;
                }
                $summary['is_matched'] = abs($summary['bku'] - $summary['bank']) < 0.01;
            }
        }

        // 2. Ringkasan Selisih Transaksi: Transaksi with discrepancy (not 0) and not verified yet
        // Alternatively, just any recent transaction with a discrepancy
        $selisihTransaksis = (clone $query)->whereRaw('ABS(bku_saldo_akhir - bank_saldo_akhir) > 0')
                                           ->orderBy('created_at', 'desc')
                                           ->paginate(10, ['*'], 'selisih_page');

        // 3. Aktivitas Terakhir
        $recentActivities = (clone $query)->orderBy('updated_at', 'desc')
                                          ->take(5)
                                          ->get();

        // 4. Chart Data (Current Year)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            'bku' => array_fill(0, 12, 0),
            'bank' => array_fill(0, 12, 0),
        ];

        $chartTransactions = (clone $query)->orderBy('periode_bulan', 'asc')->get();
        $rekeningBalances = [];
        $currentMonth = (int)date('n');
        
        for ($m = 1; $m <= 12; $m++) {
            // Update the latest balance for each rekening up to month $m
            foreach ($chartTransactions as $trx) {
                if ($trx->periode_bulan == $m) {
                    $rekeningBalances[$trx->rekening_id] = [
                        'bku' => $trx->bku_saldo_akhir,
                        'bank' => $trx->bank_saldo_akhir,
                    ];
                }
            }
            
            // Only calculate sum if we haven't passed the current month (don't chart future months)
            if ($m <= $currentMonth) {
                $sumBku = 0;
                $sumBank = 0;
                foreach ($rekeningBalances as $balance) {
                    $sumBku += $balance['bku'];
                    $sumBank += $balance['bank'];
                }
                $chartData['bku'][$m - 1] = $sumBku;
                $chartData['bank'][$m - 1] = $sumBank;
            } else {
                $chartData['bku'][$m - 1] = 0;
                $chartData['bank'][$m - 1] = 0;
            }
        }

        // 5. Reminder (Notifikasi Peringatan)
        $missingMonth = null;
        if ($user->role === 'operator') {
            $currentMonth = (int)date('n');
            $prevMonth = $currentMonth - 1;
            if ($prevMonth > 0) {
                $hasPrevMonth = (clone $query)->where('periode_bulan', $prevMonth)->exists();
                if (!$hasPrevMonth) {
                    $missingMonth = $namaBulan[$prevMonth - 1];
                }
            }
        }

        // 6. SKPD Reconciliation Status (Admin: all, Operator: own)
        $skpdRekonStatus = [];
        $skpdQuery = Skpd::where('status', true);
        if ($user->skpd_id) {
            $skpdQuery->where('id', $user->skpd_id);
        }
        $skpdsPaginated = $skpdQuery->orderBy('nama')->paginate(10);
        $allSkpds = $skpdsPaginated->items();
        foreach ($allSkpds as $skpd) {
            $bulanRekon = Transaksi::where('skpd_id', $skpd->id)
                ->where('periode_tahun', $tahunAktif)
                ->where('status_verifikasi', 'verified')
                ->pluck('periode_bulan')
                ->unique()
                ->toArray();
            
            $skpdRekonStatus[] = [
                'nama' => $skpd->nama,
                'kode' => $skpd->kode,
                'bulan_selesai' => count($bulanRekon),
                'bulan_list' => $bulanRekon,
            ];
        }

        // 7. Pengumuman Aktif
        $pengumumans = \App\Models\Pengumuman::where('is_aktif', true)->orderBy('created_at', 'desc')->get();

        // 8. Persentase Kepatuhan (Hanya untuk Admin)
        $kepatuhanData = null;
        if (!$user->skpd_id) {
            $totalSkpd = Skpd::where('status', true)->count();
            // Tentukan bulan target (bulan lalu)
            $currentMonth = (int)date('n');
            $targetMonth = $currentMonth > 1 ? $currentMonth - 1 : 12;
            $targetYear = $currentMonth > 1 ? $tahunAktif : $tahunAktif - 1;
            
            if ($tahunAktif < date('Y')) {
                $targetMonth = 12;
                $targetYear = $tahunAktif;
            }
            
            if ($targetYear == $tahunAktif) {
                $skpdPatuhCount = Transaksi::where('periode_tahun', $tahunAktif)
                    ->where('periode_bulan', $targetMonth)
                    ->where('status_verifikasi', 'verified')
                    ->whereRaw('ABS(bku_saldo_akhir - bank_saldo_akhir) < 0.01')
                    ->distinct('skpd_id')
                    ->count('skpd_id');
            } else {
                $skpdPatuhCount = 0;
            }

            $persentase = $totalSkpd > 0 ? round(($skpdPatuhCount / $totalSkpd) * 100) : 0;
            $kepatuhanData = [
                'target_bulan' => $targetMonth,
                'total_skpd' => $totalSkpd,
                'patuh' => $skpdPatuhCount,
                'persentase' => $persentase
            ];
        }

        // 9. Leaderboard with Timeliness Scoring & Early Warning System (Hanya untuk Admin / Konsolidator)
        $topSkpds = collect();
        $bottomSkpds = collect();
        if (!$user->skpd_id) {
            $skpdsWithTrx = Skpd::where('status', true)->with(['transaksis' => function ($query) use ($tahunAktif) {
                $query->where('periode_tahun', $tahunAktif);
            }])->get();

            $skpdScored = $skpdsWithTrx->map(function ($skpd) {
                $transaksis = $skpd->transaksis;
                $totalTrx = $transaksis->count();
                $verifiedTrx = $transaksis->where('status_verifikasi', 'verified')->count();
                
                $totalScore = 0;
                $selisihCount = 0;
                
                foreach ($transaksis as $trx) {
                    if (abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) > 0.01) {
                        $selisihCount++;
                    }
                    
                    // Bobot waktu kedisiplinan lapor (berdasarkan tanggal created_at)
                    $tanggalLapor = $trx->created_at ? $trx->created_at->day : 15;
                    if ($tanggalLapor <= 5) {
                        $totalScore += 100; // Sangat cepat (Tgl 1-5)
                    } elseif ($tanggalLapor <= 10) {
                        $totalScore += 85;  // Tepat waktu (Tgl 6-10)
                    } elseif ($tanggalLapor <= 15) {
                        $totalScore += 65;  // Batas toleransi (Tgl 11-15)
                    } else {
                        $totalScore += 40;  // Terlambat (> Tgl 15)
                    }

                    if ($trx->status_verifikasi == 'verified' && abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) < 0.01) {
                        $totalScore += 20;
                    }
                }
                
                $avgScore = $totalTrx > 0 ? round(($totalScore / ($totalTrx * 120)) * 100) : 0;
                if ($avgScore > 100) $avgScore = 100;
                if ($totalTrx > 0 && $avgScore < 20) $avgScore = 20; // Nilai partisipasi minimal jika ada transaksi

                $skpd->timeliness_score = $avgScore;
                $skpd->transaksi_count = $totalTrx;
                $skpd->verified_count = $verifiedTrx;
                $skpd->selisih_count = $selisihCount;
                return $skpd;
            });

            // Top 5 Paling Disiplin & Rajin
            $topSkpds = $skpdScored->sortByDesc(function ($s) {
                return ($s->verified_count * 1000) + $s->timeliness_score - ($s->selisih_count * 50);
            })->take(5)->values();

            // Bottom 5 Paling Rawan & Butuh Perhatian (Early Warning System / EWS)
            $bottomSkpds = $skpdScored->sortBy(function ($s) {
                return ($s->verified_count * 1000) + $s->timeliness_score - ($s->selisih_count * 100);
            })->take(5)->values();
        }

        return view('dashboard', compact('latestTransaksi', 'summary', 'selisihTransaksis', 'recentActivities', 'chartData', 'missingMonth', 'tahunAktif', 'skpdRekonStatus', 'skpdsPaginated', 'pengumumans', 'kepatuhanData', 'topSkpds', 'bottomSkpds', 'namaBulan'));
    }

    /**
     * Mencetak Rapor Kepatuhan Eksekutif & Timeliness Score SKPD (PDF untuk Pimpinan)
     */
    public function cetakRaporKepatuhan(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator'])) {
            abort(403, 'Akses khusus Admin dan Konsolidator');
        }

        $tahunAktif = session('tahun_login') ?? date('Y');

        $skpdsWithTrx = Skpd::where('status', true)->with(['transaksis' => function ($query) use ($tahunAktif) {
            $query->where('periode_tahun', $tahunAktif);
        }])->get();

        $totalSkpd = $skpdsWithTrx->count();
        $totalVerifiedTrx = 0;
        $totalSelisihKas = 0;
        $totalScoreSum = 0;

        $skpdScored = $skpdsWithTrx->map(function ($skpd) use (&$totalVerifiedTrx, &$totalSelisihKas, &$totalScoreSum) {
            $transaksis = $skpd->transaksis;
            $totalTrx = $transaksis->count();
            $verifiedTrx = $transaksis->where('status_verifikasi', 'verified')->count();
            
            $totalScore = 0;
            $selisihCount = 0;
            
            foreach ($transaksis as $trx) {
                if (abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) > 0.01) {
                    $selisihCount++;
                }
                
                $tanggalLapor = $trx->created_at ? $trx->created_at->day : 15;
                if ($tanggalLapor <= 5) {
                    $totalScore += 100;
                } elseif ($tanggalLapor <= 10) {
                    $totalScore += 85;
                } elseif ($tanggalLapor <= 15) {
                    $totalScore += 65;
                } else {
                    $totalScore += 40;
                }

                if ($trx->status_verifikasi == 'verified' && abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) < 0.01) {
                    $totalScore += 20;
                }
            }
            
            $avgScore = $totalTrx > 0 ? round(($totalScore / ($totalTrx * 120)) * 100) : 0;
            if ($avgScore > 100) $avgScore = 100;
            if ($totalTrx > 0 && $avgScore < 20) $avgScore = 20;

            $skpd->timeliness_score = $avgScore;
            $skpd->transaksi_count = $totalTrx;
            $skpd->verified_count = $verifiedTrx;
            $skpd->selisih_count = $selisihCount;

            $totalVerifiedTrx += $verifiedTrx;
            $totalSelisihKas += $selisihCount;
            $totalScoreSum += $avgScore;

            return $skpd;
        });

        // Urutkan berdasarkan skor kedisiplinan tertinggi ke terendah
        $raporSkpd = $skpdScored->sortByDesc(function ($s) {
            return ($s->verified_count * 1000) + $s->timeliness_score - ($s->selisih_count * 50);
        })->values();

        $avgDaerah = $totalSkpd > 0 ? round($totalScoreSum / $totalSkpd) : 0;
        $pengaturan = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();

        // Tanggal cetak format Indonesia
        $now = \Carbon\Carbon::now();
        $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$now->dayOfWeek];
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$now->month - 1];
        $tanggalCetak = "{$namaHari}, {$now->day} {$namaBulan} {$now->year} - Pukul " . $now->format('H:i') . " WIB";
        $pencetak = auth()->user()->name . " (" . ucfirst(auth()->user()->role) . ")";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.rapor_kepatuhan_pdf', compact(
            'raporSkpd',
            'tahunAktif',
            'totalSkpd',
            'totalVerifiedTrx',
            'totalSelisihKas',
            'avgDaerah',
            'pengaturan',
            'tanggalCetak',
            'pencetak'
        ));

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Rapor_Kepatuhan_SiReKa_' . $tahunAktif . '_' . date('Ymd_His') . '.pdf');
    }
}
