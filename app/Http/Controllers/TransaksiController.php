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

        if (!$skpdId || !$rekeningId || !$periodeTahun) {
            return response()->json(['bku_saldo_akhir' => 0, 'bank_saldo_akhir' => 0, 'existing_months' => []]);
        }
        
        $existingMonths = Transaksi::where('skpd_id', $skpdId)
            ->where('rekening_id', $rekeningId)
            ->where('periode_tahun', $periodeTahun)
            ->pluck('periode_bulan')
            ->toArray();

        // Cari transaksi di bulan sebelumnya pada tahun yang sama
        $prevMonth = $periodeBulan - 1;
        if ($prevMonth < 1) {
            return response()->json(['bku_saldo_akhir' => 0, 'bank_saldo_akhir' => 0, 'existing_months' => $existingMonths]);
        }

        $prevTransaksi = Transaksi::where('skpd_id', $skpdId)
            ->where('rekening_id', $rekeningId)
            ->where('periode_tahun', $periodeTahun)
            ->where('periode_bulan', $prevMonth)
            ->first();

        return response()->json([
            'bku_saldo_akhir' => $prevTransaksi ? $prevTransaksi->bku_saldo_akhir : 0,
            'bank_saldo_akhir' => $prevTransaksi ? $prevTransaksi->bank_saldo_akhir : 0,
            'existing_months' => $existingMonths
        ]);
    }

    public function index(Request $request)
    {
        $query = Transaksi::with(['skpd', 'rekening', 'user', 'checker', 'catatans.user'])->orderBy('created_at', 'desc');

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
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nomor', 'like', "%{$search}%")
                      ->orWhere('bank', 'like', "%{$search}%");
                });
            });
        }

        // Filter by Month
        if ($request->has('bulan') && $request->bulan != '') {
            $query->where('periode_bulan', $request->bulan);
        }

        $transaksis = $query->paginate(10)->withQueryString();
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
        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();
        return view('transaksi.create', compact('skpds', 'rekenings', 'pengaturanGlobal'));
    }

    public function store(StoreTransaksiRequest $request)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);

        $validated = $request->validated();

        $validated['status_verifikasi'] = $request->status_verifikasi ?? 'draft';
        $validated['status_konsolidator'] = 'menunggu';

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

        // Snapshot BA jika diverifikasi
        if (isset($validated['status_verifikasi']) && $validated['status_verifikasi'] === 'verified') {
            $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();
            $validated['snapshot_pengantar_ba'] = $pengaturanGlobal->teks_pengantar_ba ?? 'Pada hari ini [HARI] Tanggal [TANGGAL] Bulan [BULAN] Tahun [TAHUN], telah dilakukan rekonsiliasi Saldo Kas Bendahara Pengeluaran per [AKHIR_BULAN] pada [NAMA_INSTANSI] [NAMA_PEMDA].<br><br>Dengan mencocokkan BKU Bendahara Pengeluaran per [AKHIR_BULAN] pada Aplikasi SIPANDA dengan Rekening Koran Bank Kalsel per [AKHIR_BULAN] dengan hasil sebagai berikut :';
            $validated['snapshot_penutup_ba'] = $pengaturanGlobal->teks_penutup_ba ?? '** Rincian terlampir';
        }

        Transaksi::create($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit(Transaksi $transaksi)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);
        if ($transaksi->status_verifikasi === 'verified' && Auth::user()->role === 'operator') {
            abort(403, 'Transaksi yang sudah diverifikasi tidak dapat diubah oleh SKPD. Silakan hubungi Admin Pusat untuk mengubah status menjadi Draft.');
        }

        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        // Get all active rekenings. If user is operator, filter by their SKPD.
        $rekeningQuery = Rekening::where('status', true);
        if (Auth::user()->role === 'operator') {
            $rekeningQuery->where('skpd_id', Auth::user()->skpd_id);
        }
        $rekenings = $rekeningQuery->orderBy('nama')->get();
        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();
        return view('transaksi.edit', compact('transaksi', 'skpds', 'rekenings', 'pengaturanGlobal'));
    }

    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);
        if ($transaksi->status_verifikasi === 'verified' && Auth::user()->role === 'operator') {
            abort(403, 'Transaksi yang sudah diverifikasi tidak dapat diubah oleh SKPD.');
        }

        $validated = $request->validated();

        if ($request->has('status_verifikasi')) {
            $validated['status_verifikasi'] = $request->status_verifikasi;
            // Jika disimpan sebagai verified, reset status konsolidator ke 'menunggu' untuk diperiksa ulang
            if ($validated['status_verifikasi'] === 'verified') {
                $validated['status_konsolidator'] = 'menunggu';
            }
        }
        
        if ($request->hasFile('file_bukti')) {
            // Delete old file if exists
            if ($transaksi->file_bukti) {
                \App\Services\SiReKaStorage::delete($transaksi->file_bukti);
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

        // Snapshot BA jika diverifikasi
        if (isset($validated['status_verifikasi']) && $validated['status_verifikasi'] === 'verified') {
            $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();
            $validated['snapshot_pengantar_ba'] = $pengaturanGlobal->teks_pengantar_ba ?? 'Pada hari ini [HARI] Tanggal [TANGGAL] Bulan [BULAN] Tahun [TAHUN], telah dilakukan rekonsiliasi Saldo Kas Bendahara Pengeluaran per [AKHIR_BULAN] pada [NAMA_INSTANSI] [NAMA_PEMDA].<br><br>Dengan mencocokkan BKU Bendahara Pengeluaran per [AKHIR_BULAN] pada Aplikasi SIPANDA dengan Rekening Koran Bank Kalsel per [AKHIR_BULAN] dengan hasil sebagai berikut :';
            $validated['snapshot_penutup_ba'] = $pengaturanGlobal->teks_penutup_ba ?? '** Rincian terlampir';
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

        // Hapus file fisik dari storage
        $fields = ['file_bukti', 'file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran'];
        foreach ($fields as $field) {
            if ($transaksi->$field) {
                \App\Services\SiReKaStorage::delete($transaksi->$field);
            }
        }

        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi beserta dokumennya berhasil dihapus.');
    }

    public function uploadForm(Transaksi $transaksi)
    {
        // Check operator access
        if (Auth::user()->role === 'operator' && Auth::user()->skpd_id != $transaksi->skpd_id) {
            abort(403);
        }

        $transaksi->load('catatans.user');

        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();
        $allowReupload = $pengaturanGlobal ? (bool) ($pengaturanGlobal->allow_operator_reupload ?? false) : false;
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('transaksi.upload', compact('transaksi', 'allowReupload', 'namaBulan'));
    }

    public function uploadStore(UploadTransaksiRequest $request, Transaksi $transaksi)
    {
        if (Auth::user()->role === 'konsolidator') abort(403);

        // Check operator access
        if (Auth::user()->role === 'operator' && Auth::user()->skpd_id != $transaksi->skpd_id) {
            abort(403);
        }

        $validated = $request->validated();

        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();
        $allowReupload = $pengaturanGlobal ? (bool) ($pengaturanGlobal->allow_operator_reupload ?? false) : false;

        $fields = ['file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran'];
        $uploadedCount = 0;
        $errors = [];
        
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                if (!$file->isValid()) {
                    $errors[] = "File " . strtoupper(str_replace('file_', '', $field)) . " gagal diunggah: " . $file->getErrorMessage();
                    continue;
                }

                // Keamanan Audit: Mencegah operator menimpa bukti jika izin re-upload nonaktif
                if (Auth::user()->role === 'operator' && $transaksi->$field && !$allowReupload) {
                    continue;
                }

                try {
                    // Pre-check diagnostik untuk driver lokal / mount NAS
                    $diskDriver = config('filesystems.disks.public.driver');
                    $diskRoot = config('filesystems.disks.public.root');
                    if ($diskDriver === 'local' && !empty($diskRoot)) {
                        if (!file_exists($diskRoot)) {
                            throw new \Exception("Folder storage root ('{$diskRoot}') tidak ditemukan atau belum di-mount di server.");
                        }
                        if (!is_writable($diskRoot)) {
                            $owner = @function_exists('posix_getpwuid') && @function_exists('fileowner') ? (@posix_getpwuid(fileowner($diskRoot))['name'] ?? 'root/other') : 'root';
                            throw new \Exception("Folder ('{$diskRoot}') tidak berhak ditulisi oleh PHP/Webserver (Pemilik folder saat ini: {$owner}). Jalankan di SSH VPS Anda: chown -R www:www {$diskRoot} && chmod -R 775 {$diskRoot}");
                        }
                        $subDir = rtrim($diskRoot, '/') . '/dokumen_rekonsiliasi';
                        if (!is_dir($subDir)) {
                            @mkdir($subDir, 0777, true);
                            @chmod($subDir, 0775);
                        }
                        if (is_dir($subDir) && !is_writable($subDir)) {
                            $owner = @function_exists('posix_getpwuid') && @function_exists('fileowner') ? (@posix_getpwuid(fileowner($subDir))['name'] ?? 'root/other') : 'root';
                            throw new \Exception("Subfolder ('{$subDir}') menolak penulisan file (Pemilik: {$owner}). Jalankan di SSH VPS Anda: chown -R www:www {$diskRoot} && chmod -R 775 {$diskRoot}");
                        }
                    }

                    // Simpan ke disk public aktif saat ini (Lokal/NAS/MinIO S3)
                    $storedPath = $file->store('dokumen_rekonsiliasi', 'public');
                    if (!$storedPath) {
                        $phpError = error_get_last();
                        $reason = $phpError['message'] ?? "Izin tulis folder ditolak oleh sistem Linux atau open_basedir aaPanel.";
                        throw new \Exception("Penyimpanan gagal dengan info: {$reason}");
                    }

                    // Delete old file if present & catat jejak audit (Audit Trail)
                    if ($transaksi->$field) {
                        \Illuminate\Support\Facades\Log::info("Audit Trail SiReKa: Berkas {$field} pada Transaksi ID #{$transaksi->id} (SKPD ID #{$transaksi->skpd_id}) ditimpa oleh User ID #" . Auth::id() . " (" . Auth::user()->name . ") [Role: " . Auth::user()->role . "]");
                        \App\Services\SiReKaStorage::delete($transaksi->$field);
                    }

                    $transaksi->$field = $storedPath;
                    $uploadedCount++;
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Gagal menyimpan dokumen {$field} ke storage: " . $e->getMessage());
                    $errors[] = "Gagal menyimpan " . strtoupper(str_replace('file_', '', $field)) . ": " . $e->getMessage();
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->route('transaksi.upload', $transaksi->id)->with('error', implode(' | ', $errors));
        }

        if ($uploadedCount === 0) {
            // Deteksi jika file yang diupload ditolak diam-diam oleh PHP (melebihi post_max_size / upload_max_filesize)
            $contentLength = (int) ($request->server('CONTENT_LENGTH', 0));
            $maxPostSize = self::getBytes(ini_get('post_max_size'));
            if ($contentLength > 0 && $maxPostSize > 0 && $contentLength >= $maxPostSize) {
                return redirect()->route('transaksi.upload', $transaksi->id)->with('error', "Gagal mengunggah: Total ukuran file melepasi batas maksimal server (post_max_size PHP Anda: " . ini_get('post_max_size') . "). Silakan perkecil ukuran file atau naikkan batas upload di PHP aaPanel Anda.");
            }
            return redirect()->route('transaksi.upload', $transaksi->id)->with('error', 'Tidak ada file baru yang dipilih atau berkas gagal diterima oleh server.');
        }

        $transaksi->save();

        return redirect()->route('transaksi.upload', $transaksi->id)->with('success', "{$uploadedCount} dokumen berhasil disimpan ke server.");
    }

    public function hapusDokumen(Request $request, Transaksi $transaksi, $field)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus dokumen spesifik.');
        }

        $validFields = ['file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran'];
        if (!in_array($field, $validFields)) {
            return redirect()->back()->with('error', 'Field dokumen tidak valid.');
        }

        if ($transaksi->$field) {
            \Illuminate\Support\Facades\Log::info("Audit Trail SiReKa: Berkas {$field} pada Transaksi ID #{$transaksi->id} (SKPD ID #{$transaksi->skpd_id}) dihapus oleh Admin User ID #" . Auth::id() . " (" . Auth::user()->name . ") agar dapat diupload ulang oleh SKPD.");
            \App\Services\SiReKaStorage::delete($transaksi->$field);
            $transaksi->$field = null;
            $transaksi->save();
        }

        return redirect()->route('transaksi.upload', $transaksi->id)->with('success', 'Dokumen berhasil dihapus. SKPD sekarang dapat mengunggah ulang dokumen tersebut.');
    }

    public function pemeriksaanForm(Transaksi $transaksi)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator'])) {
            abort(403, 'Akses khusus Admin dan Konsolidator.');
        }

        $transaksi->load(['skpd', 'rekening', 'user', 'checker', 'catatans.user']);

        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Cari nomor WhatsApp Admin BKAD untuk tombol hubungi Admin
        $bkadSkpd = \App\Models\Skpd::where('nama', 'like', '%BADAN KEUANGAN%')
            ->orWhere('nama', 'like', '%BKAD%')
            ->first();
        $adminWa = $bkadSkpd ? $bkadSkpd->no_whatsapp : null;

        return view('transaksi.pemeriksaan', compact('transaksi', 'namaBulan', 'adminWa'));
    }

    public function pemeriksaanStore(Request $request, Transaksi $transaksi)
    {
        if (!in_array(Auth::user()->role, ['admin', 'konsolidator'])) {
            abort(403, 'Akses khusus Admin dan Konsolidator.');
        }

        $request->validate([
            'status_konsolidator' => 'required|in:valid,perlu_perbaikan',
            'catatan' => 'required_if:status_konsolidator,perlu_perbaikan|nullable|string|max:1000',
        ], [
            'status_konsolidator.required' => 'Pilih hasil status pemeriksaan laporan.',
            'catatan.required_if' => 'Catatan koreksi/kesalahan wajib diisi jika status memerlukan perbaikan.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        $status = $request->status_konsolidator;
        $catatanText = trim($request->catatan ?? '');

        // Simpan catatan ke riwayat transaksi_catatans jika ada catatan atau status perlu_perbaikan
        if (!empty($catatanText) || $status === 'perlu_perbaikan') {
            \App\Models\TransaksiCatatan::create([
                'transaksi_id' => $transaksi->id,
                'user_id' => Auth::id(),
                'status_pemeriksaan' => $status,
                'catatan' => !empty($catatanText) ? $catatanText : ($status === 'valid' ? 'Laporan dan dokumen bukti telah diperiksa dan disetujui sah.' : 'Terdapat perbedaan/kesalahan yang memerlukan perbaikan.'),
            ]);

            $transaksi->catatan_konsolidator_terakhir = $catatanText;
        }

        $transaksi->status_konsolidator = $status;
        $transaksi->checked_by = Auth::id();
        $transaksi->checked_at = now();
        $transaksi->save();

        \Illuminate\Support\Facades\Log::info("Pemeriksaan Konsolidator SiReKa: Transaksi ID #{$transaksi->id} ({$transaksi->skpd->nama}) diperiksa oleh User #" . Auth::id() . " (" . Auth::user()->name . ") dengan status: {$status}");

        $msg = $status === 'valid' 
            ? 'Laporan berhasil diperiksa dan ditandai VALID & SAH oleh Konsolidator.' 
            : 'Hasil pemeriksaan berhasil disimpan sebagai PERLU PERBAIKAN. Silakan hubungi Admin Pusat untuk mengubah status menjadi Draft.';

        return redirect()->route('transaksi.pemeriksaan', $transaksi->id)->with('success', $msg);
    }

    public function resetToDraft(Transaksi $transaksi)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Administrator Pusat yang berhak merubah status transaksi menjadi Draft.');
        }

        $transaksi->status_verifikasi = 'draft';
        $transaksi->save();

        \App\Models\TransaksiCatatan::create([
            'transaksi_id' => $transaksi->id,
            'user_id' => Auth::id(),
            'status_pemeriksaan' => 'reset_draft',
            'catatan' => 'Status transaksi dikembalikan ke DRAFT oleh Administrator Pusat (' . Auth::user()->name . ') untuk diperbaiki ulang oleh SKPD.',
        ]);

        \Illuminate\Support\Facades\Log::info("Audit Trail SiReKa: Transaksi ID #{$transaksi->id} ({$transaksi->skpd->nama}) diubah ke Draft oleh Admin #" . Auth::id() . " (" . Auth::user()->name . ")");

        return redirect()->back()->with('success', "Transaksi untuk {$transaksi->skpd->nama} berhasil dikembalikan menjadi DRAFT. SKPD sekarang dapat memperbaiki data dan bukti dukung.");
    }

    private static function getBytes($val): int
    {
        $val = trim((string) $val);
        if (empty($val)) return 0;
        $last = strtolower($val[strlen($val)-1]);
        $val = (int) $val;
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }
}
