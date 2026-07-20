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
                $summary['info'] = 'Periode aktif: ' . date('F', mktime(0, 0, 0, $latestTransaksi->periode_bulan, 10)) . ' ' . $latestTransaksi->periode_tahun . ' | ' . ($latestTransaksi->skpd->nama ?? '');
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
                                           ->take(5)
                                           ->get();

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
        foreach ($chartTransactions as $trx) {
            $monthIndex = $trx->periode_bulan - 1;
            // For admin, it will sum across all SKPDs. For operator, only their SKPD.
            $chartData['bku'][$monthIndex] += $trx->bku_saldo_akhir;
            $chartData['bank'][$monthIndex] += $trx->bank_saldo_akhir;
        }

        // 5. Reminder (Notifikasi Peringatan)
        $missingMonth = null;
        if ($user->role === 'operator') {
            $currentMonth = (int)date('n');
            $prevMonth = $currentMonth - 1;
            if ($prevMonth > 0) {
                $hasPrevMonth = (clone $query)->where('periode_bulan', $prevMonth)->exists();
                if (!$hasPrevMonth) {
                    $missingMonth = date('F', mktime(0, 0, 0, $prevMonth, 10));
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

        return view('dashboard', compact('latestTransaksi', 'summary', 'selisihTransaksis', 'recentActivities', 'chartData', 'missingMonth', 'tahunAktif', 'skpdRekonStatus', 'skpdsPaginated', 'pengumumans'));
    }
}
