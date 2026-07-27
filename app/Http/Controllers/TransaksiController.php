<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Transaksi\StoreTransaksiRequest;
use App\Http\Requests\Transaksi\UpdateTransaksiRequest;
use App\Http\Requests\Transaksi\UploadTransaksiRequest;

use App\Models\Transaksi;
use App\Models\Skpd;
use App\Models\Rekening;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function getSaldoAwal(Request $request)
    {
        $skpdId = $request->skpd_id;
        $rekeningId = $request->rekening_id;
        $periodeBulan = (int)$request->periode_bulan;
        $periodeTahun = (int)$request->periode_tahun;

        if (!$skpdId || !$rekeningId || !$periodeBulan || !$periodeTahun) {
            return response()->json(['bku_saldo_akhir' => 0, 'bank_saldo_akhir' => 0]);
        }

        // Cari transaksi di bulan sebelumnya pada tahun yang sama
        // Jika bulan adalah 1, mungkin perlu cari bulan 12 tahun sebelumnya (opsional, tergantung rule bisnis)
        // Saat ini asumsikan hanya mencari di dalam tahun berjalan.
        $prevMonth = $periodeBulan - 1;
        if ($prevMonth < 1) {
            return response()->json(['bku_saldo_akhir' => 0, 'bank_saldo_akhir' => 0]);
        }

        $prevTransaksi = Transaksi::where('skpd_id', $skpdId)
            ->where('rekening_id', $rekeningId)
            ->where('periode_tahun', $periodeTahun)
            ->where('periode_bulan', $prevMonth)
            ->first();

        if ($prevTransaksi) {
            return response()->json([
                'bku_saldo_akhir' => $prevTransaksi->bku_saldo_akhir,
                'bank_saldo_akhir' => $prevTransaksi->bank_saldo_akhir,
            ]);
        }

        return response()->json(['bku_saldo_akhir' => 0, 'bank_saldo_akhir' => 0]);
    }

    public function index(Request $request)
    {
        $query = Transaksi::with(['skpd', 'rekening', 'user'])->orderBy('created_at', 'desc');

        // Retrieve active year from login session
        $tahunAktif = session('tahun_login') ?? date('Y');
        
        $query->where('periode_tahun', $tahunAktif);

        if (Auth::user()->role === 'operator') {
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
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        if (Auth::user()->role === 'konsolidator') abort(403);
        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        // Get all active rekenings. If user is operator, filter by their SKPD.
        $rekeningQuery = Rekening::where('status', true);
        if (Auth::user()->role === 'operator') {
            $rekeningQuery->where('skpd_id', Auth::user()->skpd_id);
        }
        $rekenings = $rekeningQuery->orderBy('nama')->get();
        return view('transaksi.create', compact('skpds', 'rekenings'));
    }

    public function store(StoreTransaksiRequest $request)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);

        $validated = $request->validated();

        if (Auth::user()->role === 'operator') {
            $validated['status_verifikasi'] = 'draft';
        } else {
            $validated['status_verifikasi'] = $request->status_verifikasi ?? 'draft';
        }

        if ($request->hasFile('file_bukti')) {
            $validated['file_bukti'] = $request->file('file_bukti')->store('bukti_rekonsiliasi', 'public');
        }

        $validated['user_id'] = Auth::id();

        // Convert null to 0 for numeric fields
        $numericFields = [
            'bku_saldo_awal', 'bku_penerimaan', 'bku_pengeluaran', 'bku_saldo_akhir',
            'bank_saldo_awal', 'bank_penerimaan', 'bank_pengeluaran', 'bank_saldo_akhir'
        ];
        foreach ($numericFields as $field) {
            $validated[$field] = $validated[$field] ?? 0;
        }

        Transaksi::create($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit(Transaksi $transaksi)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);
        if ($transaksi->status_verifikasi === 'verified' && Auth::user()->role === 'operator') {
            abort(403, 'Transaksi yang sudah diverifikasi tidak dapat diubah.');
        }

        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        // Get all active rekenings. If user is operator, filter by their SKPD.
        $rekeningQuery = Rekening::where('status', true);
        if (Auth::user()->role === 'operator') {
            $rekeningQuery->where('skpd_id', Auth::user()->skpd_id);
        }
        $rekenings = $rekeningQuery->orderBy('nama')->get();
        return view('transaksi.edit', compact('transaksi', 'skpds', 'rekenings'));
    }

    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);

        $validated = $request->validated();

        if (Auth::user()->role === 'admin') {
            $validated['status_verifikasi'] = $request->status_verifikasi ?? 'draft';
        }
        
        if ($request->hasFile('file_bukti')) {
            // Delete old file if exists
            if ($transaksi->file_bukti) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($transaksi->file_bukti);
            }
            $validated['file_bukti'] = $request->file('file_bukti')->store('bukti_rekonsiliasi', 'public');
        }

        $numericFields = [
            'bku_saldo_awal', 'bku_penerimaan', 'bku_pengeluaran', 'bku_saldo_akhir',
            'bank_saldo_awal', 'bank_penerimaan', 'bank_pengeluaran', 'bank_saldo_akhir'
        ];
        foreach ($numericFields as $field) {
            $validated[$field] = $validated[$field] ?? 0;
        }

        $transaksi->update($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);
        if ($transaksi->status_verifikasi === 'verified' && Auth::user()->role === 'operator') {
            abort(403, 'Transaksi yang sudah diverifikasi tidak dapat dihapus.');
        }
        
        if ($transaksi->file_bukti) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($transaksi->file_bukti);
        }

        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function uploadForm(Transaksi $transaksi)
    {
        if ($transaksi->status_verifikasi !== 'verified') {
            return redirect()->route('transaksi.index')->with('error', 'Upload dokumen hanya tersedia untuk transaksi yang sudah diverifikasi.');
        }

        // Check operator access
        if (Auth::user()->role === 'operator' && Auth::user()->skpd_id != $transaksi->skpd_id) {
            abort(403);
        }

        return view('transaksi.upload', compact('transaksi'));
    }

    public function uploadStore(UploadTransaksiRequest $request, Transaksi $transaksi)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);
        if ($transaksi->status_verifikasi !== 'verified') {
            return redirect()->route('transaksi.index')->with('error', 'Upload dokumen hanya tersedia untuk transaksi yang sudah diverifikasi.');
        }

        $validated = $request->validated();

        $fields = ['file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran'];
        
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file
                if ($transaksi->$field) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($transaksi->$field);
                }
                $transaksi->$field = $request->file($field)->store('dokumen_rekonsiliasi', 'public');
            }
        }

        $transaksi->save();

        return redirect()->route('transaksi.upload', $transaksi->id)->with('success', 'Dokumen berhasil diupload.');
    }
}
