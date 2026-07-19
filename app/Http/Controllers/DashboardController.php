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

        // 1. Get the latest transaction for the main metrics (Current Period)
        $latestTransaksi = (clone $query)->orderBy('periode_tahun', 'desc')
                                        ->orderBy('periode_bulan', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

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
        $allSkpds = $skpdQuery->orderBy('nama')->get();
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

        return view('dashboard', compact('latestTransaksi', 'selisihTransaksis', 'recentActivities', 'chartData', 'missingMonth', 'tahunAktif', 'skpdRekonStatus'));
    }
}
