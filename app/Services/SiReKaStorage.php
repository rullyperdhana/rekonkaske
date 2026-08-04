<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SiReKaStorage
{
    /**
     * Cek apakah file ada di storage aktif (MinIO/NAS) ataupun di hard disk lokal lama
     */
    public static function exists($path): bool
    {
        if (empty($path)) return false;
        $path = ltrim($path, '/');

        // 1. Cek di disk aktif saat ini (bisa Lokal, NAS, atau MinIO S3)
        try {
            if (Storage::disk('public')->exists($path)) {
                return true;
            }
        } catch (\Throwable $e) {
            Log::warning("SiReKaStorage::exists error on disk public: " . $e->getMessage());
        }

        // 2. Fallback: Cek di folder fisik hard disk lokal internal
        $localFallbackPath = storage_path('app/public/' . $path);
        if (file_exists($localFallbackPath) && is_file($localFallbackPath)) {
            return true;
        }

        return false;
    }

    /**
     * Ambil isi file biner dari storage aktif ataupun fallback dari lokal.
     * Jika terambil dari lokal dan storage aktif bukan lokal, otomatis dikopi (Auto-Heal Migration).
     */
    public static function read($path)
    {
        if (empty($path)) return null;
        $path = ltrim($path, '/');

        // 1. Jika sudah ada di disk aktif saat ini
        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->get($path);
            }
        } catch (\Throwable $e) {
            Log::warning("SiReKaStorage::read error on disk public: " . $e->getMessage());
        }

        // 2. Fallback dari hard disk lokal lama
        $localFallbackPath = storage_path('app/public/' . $path);
        if (file_exists($localFallbackPath) && is_file($localFallbackPath)) {
            $content = @file_get_contents($localFallbackPath);
            if ($content === false) return null;

            // Auto-Heal: Salin ke MinIO / NAS di belakang layar agar kedepannya langsung terbaca di storage aktif
            try {
                $configPath = storage_path('app/storage_nas_config.json');
                if (file_exists($configPath)) {
                    $config = json_decode(file_get_contents($configPath), true);
                    if (!empty($config['mode']) && $config['mode'] !== 'local') {
                        Storage::disk('public')->put($path, $content);
                        Log::info("SiReKa Auto-Heal Migration: File {$path} tersalin otomatis ke " . strtoupper($config['mode']));
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("SiReKa Auto-Heal Migration gagal untuk file {$path}: " . $e->getMessage());
            }

            return $content;
        }

        return null;
    }

    /**
     * Hapus file dari kedua tempat jika ada
     */
    public static function delete($path): bool
    {
        if (empty($path)) return false;
        $path = ltrim($path, '/');

        $deleted = false;
        try {
            if (Storage::disk('public')->exists($path)) {
                $deleted = Storage::disk('public')->delete($path) || $deleted;
            }
        } catch (\Throwable $e) {
            Log::warning("SiReKaStorage::delete error on disk public: " . $e->getMessage());
        }

        $localFallbackPath = storage_path('app/public/' . $path);
        if (file_exists($localFallbackPath) && is_file($localFallbackPath)) {
            @unlink($localFallbackPath);
            $deleted = true;
        }

        return $deleted;
    }

    /**
     * Buat URL yang aman mengarah ke rute stream cerdas SiReKa atau URL storage standar
     */
    public static function url($path): string
    {
        if (empty($path)) return '#';

        if (Route::has('storage.stream')) {
            return route('storage.stream', ['path' => ltrim($path, '/')]);
        }

        return Storage::url($path);
    }
}
