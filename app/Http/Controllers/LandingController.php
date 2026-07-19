<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skpd;
use App\Models\Transaksi;
use App\Models\Pengaturan;

class LandingController extends Controller
{
    public function index()
    {
        $tahunAktif = date('Y'); // Since it's public, just default to current year
        
        $pengaturan = Pengaturan::whereNull('skpd_id')->first();

        $skpdRekonStatus = [];
        $allSkpds = Skpd::where('status', true)->orderBy('nama')->get();
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

        return view('landing', compact('skpdRekonStatus', 'tahunAktif', 'pengaturan'));
    }
}
