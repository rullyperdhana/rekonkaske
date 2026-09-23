<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramConfigController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Tampilkan halaman pengaturan notifikasi Telegram.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak: Pengaturan Notifikasi Telegram hanya dapat diakses oleh Administrator BKAD.');
        }

        $pengaturan = Pengaturan::whereNull('skpd_id')->first() ?? Pengaturan::first();
        
        return view('pengaturan.telegram.index', compact('pengaturan'));
    }

    /**
     * Simpan konfigurasi Telegram.
     */
    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'telegram_bot_token' => 'nullable|string|max:255',
            'telegram_chat_id' => 'nullable|string|max:255',
            'telegram_template' => 'nullable|string',
        ]);

        $validated['telegram_notif_aktif'] = $request->has('telegram_notif_aktif') ? true : false;

        $pengaturan = Pengaturan::firstOrCreate(['skpd_id' => null]);
        $pengaturan->update($validated);

        return redirect()->route('pengaturan.telegram.index')->with('success', 'Konfigurasi Notifikasi Telegram berhasil disimpan.');
    }

    /**
     * Uji coba pengiriman pesan ke Telegram.
     */
    public function test(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $token = $request->input('telegram_bot_token');
        $chatId = $request->input('telegram_chat_id');

        // Jika tidak diisi pada form test, coba ambil dari database
        if (empty($token) || empty($chatId)) {
            $pengaturan = Pengaturan::whereNull('skpd_id')->first() ?? Pengaturan::first();
            $token = $token ?: ($pengaturan->telegram_bot_token ?? '');
            $chatId = $chatId ?: ($pengaturan->telegram_chat_id ?? '');
        }

        if (empty($token)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Token Bot Telegram belum diisi.'], 422);
            }
            return redirect()->back()->with('error', 'Token Bot Telegram belum diisi.');
        }

        if (empty($chatId)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Target Chat ID belum diisi.'], 422);
            }
            return redirect()->back()->with('error', 'Target Chat ID belum diisi.');
        }

        // Lakukan pengujian koneksi dan pengiriman pesan sampel
        $result = $this->telegramService->testConnection($token, $chatId);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Uji coba pengiriman simulasi data rekonsiliasi kas ke Telegram.
     */
    public function testData(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $token = $request->input('telegram_bot_token');
        $chatId = $request->input('telegram_chat_id');

        // Jika tidak diisi pada form test, coba ambil dari database
        if (empty($token) || empty($chatId)) {
            $pengaturan = Pengaturan::whereNull('skpd_id')->first() ?? Pengaturan::first();
            $token = $token ?: ($pengaturan->telegram_bot_token ?? '');
            $chatId = $chatId ?: ($pengaturan->telegram_chat_id ?? '');
        }

        if (empty($token)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Token Bot Telegram belum diisi.'], 422);
            }
            return redirect()->back()->with('error', 'Token Bot Telegram belum diisi.');
        }

        if (empty($chatId)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Target Chat ID belum diisi.'], 422);
            }
            return redirect()->back()->with('error', 'Target Chat ID belum diisi.');
        }

        // Lakukan pengujian pengiriman data rekonsiliasi sampel
        $result = $this->telegramService->sendSampleDataNotification($token, $chatId);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
