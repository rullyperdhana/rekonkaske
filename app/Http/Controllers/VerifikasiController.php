<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pengaturan;

class VerifikasiController extends Controller
{
    public function show($id)
    {
        $transaksi = Transaksi::with(['skpd', 'rekening'])->findOrFail($id);

        if ($transaksi->status_verifikasi !== 'verified') {
            abort(404, 'Dokumen belum terverifikasi atau tidak valid.');
        }

        $pengaturan = Pengaturan::whereNull('skpd_id')->first() ?? new Pengaturan();

        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('verifikasi.show', compact('transaksi', 'pengaturan', 'namaBulan'));
    }
}
