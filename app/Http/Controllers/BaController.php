<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class BaController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['skpd', 'rekening'])->orderBy('created_at', 'desc');
        
        // Retrieve active year from login session
        $tahunAktif = session('tahun_login') ?? date('Y');
        
        $query->where('periode_tahun', $tahunAktif);

        if (Auth::user()->skpd_id) {
            $query->where('skpd_id', Auth::user()->skpd_id);
        }

        // Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('skpd', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhereHas('rekening', function($q) use ($search) {
                    $q->where('nomor_rekening', 'like', "%{$search}%")
                      ->orWhere('nama_bank', 'like', "%{$search}%");
                });
            });
        }

        // Filter by Month
        if ($request->has('bulan') && $request->bulan != '') {
            $query->where('periode_bulan', $request->bulan);
        }

        $transaksis = $query->paginate(10);
        return view('laporan.ba.list', compact('transaksis'));
    }

    public function show(Transaksi $transaksi)
    {
        // Ensure user can only view their own SKPD's BA unless they are admin pusat
        if (Auth::user()->skpd_id && Auth::user()->skpd_id != $transaksi->skpd_id) {
            abort(403, 'Unauthorized access.');
        }

        $pengaturan = $transaksi->skpd->pengaturan ?? \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? new \App\Models\Pengaturan([
            'nama_pemerintah' => 'PEMERINTAH KABUPATEN TAPIN',
            'nama_instansi' => $transaksi->skpd->nama,
            'jalan' => 'Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru',
            'kecamatan' => 'RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173',
            'kontak' => 'Kode Pos 71114 Email: bkad@tapinkab.go.id',
            'kota' => 'RANTAU',
        ]);

        return view('laporan.ba.index', compact('transaksi', 'pengaturan'));
    }

    public function pdf(Transaksi $transaksi)
    {
        // Ensure user can only view their own SKPD's BA unless they are admin pusat
        if (Auth::user()->skpd_id && Auth::user()->skpd_id != $transaksi->skpd_id) {
            abort(403, 'Unauthorized access.');
        }

        $pengaturan = $transaksi->skpd->pengaturan ?? \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? new \App\Models\Pengaturan([
            'nama_pemerintah' => 'PEMERINTAH KABUPATEN TAPIN',
            'nama_instansi' => $transaksi->skpd->nama,
            'jalan' => 'Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru',
            'kecamatan' => 'RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173',
            'kontak' => 'Kode Pos 71114 Email: bkad@tapinkab.go.id',
            'kota' => 'RANTAU',
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.ba.pdf', compact('transaksi', 'pengaturan'))
            ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
        
        // F4/Folio size: 8.5 x 13 inches -> 612 x 936 pt approx.
        $pdf->setPaper([0, 0, 612.0, 936.0], 'portrait');
        
        return $pdf->stream('Berita-Acara-' . $transaksi->skpd->nama . '-' . $transaksi->periode_bulan . '-' . $transaksi->periode_tahun . '.pdf');
    }
}
