<?php

namespace App\Services;

use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Dapatkan pengaturan global Telegram.
     */
    public function getSettings(): ?Pengaturan
    {
        try {
            return Pengaturan::whereNull('skpd_id')->first() ?? Pengaturan::first();
        } catch (\Throwable $e) {
            Log::warning('TelegramService: Gagal memuat pengaturan database: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Uji koneksi Bot Telegram dan kirim pesan sampel.
     */
    public function testConnection(string $token, string $chatId): array
    {
        $token = trim($token);
        $chatId = trim($chatId);

        if (empty($token)) {
            return ['success' => false, 'message' => 'Token Bot Telegram tidak boleh kosong.'];
        }
        if (empty($chatId)) {
            return ['success' => false, 'message' => 'Target Chat ID tidak boleh kosong.'];
        }

        try {
            // 1. Verifikasi Bot via getMe
            $getMeUrl = "https://api.telegram.org/bot{$token}/getMe";
            $getMeRes = Http::timeout(10)->get($getMeUrl);

            if (!$getMeRes->successful() || !($getMeRes->json()['ok'] ?? false)) {
                $description = $getMeRes->json()['description'] ?? 'Token Bot Telegram tidak valid atau tidak dikenali oleh server Telegram.';
                return ['success' => false, 'message' => "Gagal otentikasi Bot: {$description}"];
            }

            $botData = $getMeRes->json()['result'] ?? [];
            $botUsername = $botData['username'] ?? 'UnknownBot';
            $botFirstName = $botData['first_name'] ?? 'Bot';

            // 2. Kirim Pesan Uji Coba
            $nowWita = now()->timezone('Asia/Makassar')->translatedFormat('d F Y, H:i:s') . ' WITA';
            $testMsg = "✅ <b>UJI COBA INTEGRASI TELEGRAM BERHASIL</b>\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "🤖 <b>Nama Bot:</b> {$botFirstName} (@{$botUsername})\n"
                . "🏛 <b>Aplikasi:</b> SiReKa (Sistem Rekonsiliasi Kas Kab. Tapin)\n"
                . "⏰ <b>Waktu Uji:</b> {$nowWita}\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "Notifikasi otomatis saat SKPD melakukan <b>Posting Final / Diverifikasi SKPD</b> kini siap disiarkan ke akun/grup ini.";

            $sendRes = $this->rawSendMessage($token, $chatId, $testMsg);

            if (!$sendRes['success']) {
                return [
                    'success' => false,
                    'message' => "Bot (@{$botUsername}) valid, namun gagal mengirim pesan ke Chat ID '{$chatId}': " . $sendRes['error'],
                    'bot' => $botData
                ];
            }

            return [
                'success' => true,
                'message' => "Koneksi berhasil! Pesan uji coba telah terkirim via @{$botUsername} ke Chat ID '{$chatId}'.",
                'bot' => $botData
            ];
        } catch (\Throwable $e) {
            Log::error("TelegramService testConnection Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan jaringan atau server saat menghubungi API Telegram: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kirim notifikasi instan saat SKPD menyelesaikan rekonsiliasi (Posting Final / Verified).
     */
    public function notifyPostingFinal(Transaksi $transaksi): bool
    {
        $settings = $this->getSettings();
        if (!$settings) {
            return false;
        }

        // Periksa apakah notifikasi Telegram diaktifkan
        $isAktif = (bool) ($settings->telegram_notif_aktif ?? false);
        $token = trim($settings->telegram_bot_token ?? '');
        $chatId = trim($settings->telegram_chat_id ?? '');

        if (!$isAktif || empty($token) || empty($chatId)) {
            return false;
        }

        try {
            $transaksi->loadMissing(['skpd', 'user']);

            $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $bulanStr = $namaBulan[($transaksi->periode_bulan ?? 1) - 1] ?? 'Bulan ' . $transaksi->periode_bulan;
            $tahunStr = $transaksi->periode_tahun ?? date('Y');

            $namaSkpd = $transaksi->skpd ? $transaksi->skpd->nama : 'SKPD Tidak Diketahui';
            $kodeSkpd = $transaksi->skpd ? $transaksi->skpd->kode : '-';

            $saldoBku = 'Rp ' . number_format((float) ($transaksi->bku_saldo_akhir ?? 0), 2, ',', '.');
            $saldoBank = 'Rp ' . number_format((float) ($transaksi->bank_saldo_akhir ?? 0), 2, ',', '.');

            $selisihNominal = round(abs(($transaksi->bku_saldo_akhir ?? 0) - ($transaksi->bank_saldo_akhir ?? 0)), 2);
            $statusSelisih = ($selisihNominal == 0)
                ? '🟢 KLOP (Sesuai Rp 0,00)'
                : '🔴 ADA SELISIH (Rp ' . number_format($selisihNominal, 2, ',', '.') . ')';

            // Hitung berkas bukti yang telah diunggah
            $dokumenFields = ['file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran'];
            $terunggah = 0;
            foreach ($dokumenFields as $f) {
                if (!empty($transaksi->$f)) {
                    $terunggah++;
                }
            }
            $dokumenStatus = "{$terunggah}/4 Berkas Terunggah";

            $operatorName = $transaksi->user ? $transaksi->user->name : (auth()->user()->name ?? 'Operator SKPD');
            $waktuWita = now()->timezone('Asia/Makassar')->translatedFormat('d F Y, H:i') . ' WITA';

            // Susun template pesan
            $msg = "📢 <b>NOTIFIKASI REKONSILIASI KAS (SiReKa)</b>\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "🏛 <b>SKPD:</b> {$namaSkpd}\n"
                . "🔢 <b>Kode:</b> <code>{$kodeSkpd}</code>\n"
                . "📅 <b>Periode:</b> {$bulanStr} {$tahunStr}\n"
                . "💰 <b>Saldo BKU:</b> {$saldoBku}\n"
                . "🏦 <b>Saldo Bank:</b> {$saldoBank}\n"
                . "⚖️ <b>Status Kas:</b> {$statusSelisih}\n"
                . "📎 <b>Kelengkapan:</b> {$dokumenStatus}\n"
                . "📌 <b>Status:</b> Diverifikasi SKPD (Posting Final)\n"
                . "👤 <b>Operator:</b> {$operatorName}\n"
                . "⏰ <b>Waktu Posting:</b> {$waktuWita}\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "💡 <i>Silakan Konsolidator/Admin membuka menu <b>Antrean Verifikasi</b> di aplikasi SiReKa untuk memeriksa dan mengesahkan berkas.</i>";

            // Kirim ke semua target chat ID (bisa multi-chat dengan pemisah koma)
            $chatIds = array_filter(array_map('trim', explode(',', $chatId)));
            $allSuccess = true;

            foreach ($chatIds as $targetId) {
                if (empty($targetId)) continue;
                $res = $this->rawSendMessage($token, $targetId, $msg);
                if (!$res['success']) {
                    Log::warning("TelegramService: Gagal kirim notif ke Chat ID {$targetId}: " . ($res['error'] ?? 'Unknown error'));
                    $allSuccess = false;
                }
            }

            return $allSuccess;
        } catch (\Throwable $e) {
            // Fail-safe: Jangan sampai error pengiriman menghentikan proses simpan transaksi SKPD
            Log::error("TelegramService notifyPostingFinal Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim simulasi notifikasi data rekonsiliasi kas riil/sampel ke Telegram.
     */
    public function sendSampleDataNotification(string $token, string $chatId, ?Transaksi $transaksi = null): array
    {
        $token = trim($token);
        $chatId = trim($chatId);

        if (empty($token)) {
            return ['success' => false, 'message' => 'Token Bot Telegram tidak boleh kosong.'];
        }
        if (empty($chatId)) {
            return ['success' => false, 'message' => 'Target Chat ID tidak boleh kosong.'];
        }

        try {
            // Ambil data transaksi yang ada jika tidak disediakan
            if (!$transaksi) {
                $transaksi = Transaksi::where('status_verifikasi', 'verified')->latest()->with(['skpd', 'user'])->first();
                if (!$transaksi) {
                    $transaksi = Transaksi::latest()->with(['skpd', 'user'])->first();
                }
            }

            $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            if ($transaksi) {
                $transaksi->loadMissing(['skpd', 'user']);
                $namaSkpd = $transaksi->skpd ? $transaksi->skpd->nama : 'SKPD CONTOH PEMKAB TAPIN';
                $kodeSkpd = $transaksi->skpd ? $transaksi->skpd->kode : '1-02-01';
                $bulanStr = $namaBulan[($transaksi->periode_bulan ?? 1) - 1] ?? 'Bulan ' . $transaksi->periode_bulan;
                $tahunStr = $transaksi->periode_tahun ?? date('Y');
                $saldoBku = 'Rp ' . number_format((float) ($transaksi->bku_saldo_akhir ?? 0), 2, ',', '.');
                $saldoBank = 'Rp ' . number_format((float) ($transaksi->bank_saldo_akhir ?? 0), 2, ',', '.');
                $selisihNominal = round(abs(($transaksi->bku_saldo_akhir ?? 0) - ($transaksi->bank_saldo_akhir ?? 0)), 2);
                $statusSelisih = ($selisihNominal == 0)
                    ? '🟢 KLOP (Sesuai Rp 0,00)'
                    : '🔴 ADA SELISIH (Rp ' . number_format($selisihNominal, 2, ',', '.') . ')';
                
                $dokumenFields = ['file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran'];
                $terunggah = 0;
                foreach ($dokumenFields as $f) {
                    if (!empty($transaksi->$f)) $terunggah++;
                }
                $dokumenStatus = "{$terunggah}/4 Berkas Terunggah";
                $operatorName = $transaksi->user ? $transaksi->user->name : (auth()->user()->name ?? 'Operator SKPD');
            } else {
                // Sampel jika database masih kosong
                $namaSkpd = 'DINAS PENDIDIKAN KABUPATEN TAPIN';
                $kodeSkpd = '1-01-01';
                $bulanStr = $namaBulan[(int) date('m') - 1];
                $tahunStr = date('Y');
                $saldoBku = 'Rp 145.250.000,50';
                $saldoBank = 'Rp 145.250.000,50';
                $statusSelisih = '🟢 KLOP (Sesuai Rp 0,00)';
                $dokumenStatus = '4/4 Berkas Lengkap (BA, BKU, Bank, Koran)';
                $operatorName = auth()->user()->name ?? 'Ahmad Fauzi (Operator SKPD)';
            }

            $waktuWita = now()->timezone('Asia/Makassar')->translatedFormat('d F Y, H:i') . ' WITA';

            $msg = "📢 <b>[SIMULASI TES DATA] REKONSILIASI KAS (SiReKa)</b>\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "🏛 <b>SKPD:</b> {$namaSkpd}\n"
                . "🔢 <b>Kode:</b> <code>{$kodeSkpd}</code>\n"
                . "📅 <b>Periode:</b> {$bulanStr} {$tahunStr}\n"
                . "💰 <b>Saldo BKU:</b> {$saldoBku}\n"
                . "🏦 <b>Saldo Bank:</b> {$saldoBank}\n"
                . "⚖️ <b>Status Kas:</b> {$statusSelisih}\n"
                . "📎 <b>Kelengkapan:</b> {$dokumenStatus}\n"
                . "📌 <b>Status:</b> Diverifikasi SKPD (Posting Final)\n"
                . "👤 <b>Operator:</b> {$operatorName}\n"
                . "⏰ <b>Waktu Posting:</b> {$waktuWita}\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "💡 <i>[UJI COBA DATA] Ini adalah simulasi tampilan format data notifikasi otomatis saat SKPD melakukan Posting Final. Berkas siap diperiksa di Antrean Verifikasi.</i>";

            $chatIds = array_filter(array_map('trim', explode(',', $chatId)));
            $sentCount = 0;
            $errors = [];

            foreach ($chatIds as $targetId) {
                if (empty($targetId)) continue;
                $res = $this->rawSendMessage($token, $targetId, $msg);
                if ($res['success']) {
                    $sentCount++;
                } else {
                    $errors[] = "Chat ID {$targetId}: " . ($res['error'] ?? 'Gagal');
                }
            }

            if ($sentCount > 0) {
                return [
                    'success' => true,
                    'message' => "Simulasi notifikasi data rekonsiliasi kas berhasil dikirim ke {$sentCount} target Chat ID!" . (!empty($errors) ? " (Peringatan: " . implode('; ', $errors) . ")" : "")
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "Gagal mengirim data ke Telegram: " . implode('; ', $errors)
                ];
            }
        } catch (\Throwable $e) {
            Log::error("TelegramService sendSampleDataNotification Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses sampel data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Fungsi dasar pengiriman pesan Telegram dengan parse_mode HTML.
     */
    protected function rawSendMessage(string $token, string $chatId, string $text): array
    {
        try {
            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            $response = Http::timeout(10)->post($url, [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if ($response->successful() && ($response->json()['ok'] ?? false)) {
                return ['success' => true];
            }

            $desc = $response->json()['description'] ?? ('HTTP Status ' . $response->status());
            return ['success' => false, 'error' => $desc];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
