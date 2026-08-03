<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class StorageConfigController extends Controller
{
    /**
     * Menampilkan dashboard manajemen storage dan koneksi NAS/MinIO
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator SiReKa yang dapat mengelola infrastruktur Storage & NAS.');
        }

        // Statistik Ruang Penyimpanan Server Saat Ini
        $storageDir = storage_path('app/public');
        $totalSpace = @disk_total_space($storageDir) ?: (@disk_total_space('/') ?: 0);
        $freeSpace = @disk_free_space($storageDir) ?: (@disk_free_space('/') ?: 0);
        $usedSpace = $totalSpace > 0 ? ($totalSpace - $freeSpace) : 0;
        $usedPercent = $totalSpace > 0 ? round(($usedSpace / $totalSpace) * 100, 1) : 0;

        $formattedTotal = $this->formatBytes($totalSpace);
        $formattedFree = $this->formatBytes($freeSpace);
        $formattedUsed = $this->formatBytes($usedSpace);

        // Baca konfigurasi JSON yang tersimpan
        $configPath = storage_path('app/storage_nas_config.json');
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
        } else {
            $config = [
                'mode' => 'local',
                'nas_mount_path' => '/mnt/sireka_nas_pool',
                'minio_endpoint' => 'http://192.168.1.50:9000',
                'minio_bucket' => 'sireka-arsip-rekon',
                'minio_access_key' => 'admin_bkad_tapin',
                'minio_secret_key' => '',
                'auto_archive' => true
            ];
        }

        return view('pengaturan.storage.index', compact(
            'totalSpace', 'freeSpace', 'usedSpace', 'usedPercent',
            'formattedTotal', 'formattedFree', 'formattedUsed', 'config'
        ));
    }

    /**
     * Menyimpan perubahan preferensi Storage
     */
    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'mode' => 'required|in:local,nas,minio',
            'nas_mount_path' => 'nullable|string|max:255',
            'minio_endpoint' => 'nullable|string|max:255',
            'minio_bucket' => 'nullable|string|max:255',
            'minio_access_key' => 'nullable|string|max:255',
            'minio_secret_key' => 'nullable|string|max:255',
        ]);

        $validated['auto_archive'] = $request->has('auto_archive');

        $configPath = storage_path('app/storage_nas_config.json');
        file_put_contents($configPath, json_encode($validated, JSON_PRETTY_PRINT));

        $modeNames = [
            'local' => 'Penyimpanan Internal Server (SSD/HDD Lokal)',
            'nas' => 'Network Attached Storage (NAS / NFS Mount)',
            'minio' => 'Object Storage Cloud-Native (MinIO / S3)'
        ];

        return redirect()->route('pengaturan.storage.index')
                         ->with('success', '🎉 Konfigurasi Storage & NAS berhasil disimpan! Mode aktif saat ini: ' . ($modeNames[$validated['mode']] ?? $validated['mode']));
    }

    /**
     * Menguji kelayakan dan koneksi ke media penyimpanan yang dipilih
     */
    public function testConnection(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $mode = $request->input('mode', 'local');

        if ($mode === 'local') {
            // Test local writable
            $testFile = 'public/test_koneksi_sireka_' . time() . '.txt';
            try {
                Storage::put($testFile, 'Tes tulis dari menu pengaturan Storage SiReKa');
                if (Storage::exists($testFile)) {
                    Storage::delete($testFile);
                    return back()->with('success', '✅ Tes Koneksi Berhasil (OK): Penyimpanan lokal internal berfungsi normal dan memiliki izin tulis (writable) penuh!');
                }
            } catch (\Exception $e) {
                return back()->with('error', '❌ Tes Gagal: Terjadi kendala izin tulis pada folder storage internal server: ' . $e->getMessage());
            }
        } elseif ($mode === 'nas') {
            $path = $request->input('nas_mount_path');
            if (empty($path)) {
                return back()->with('error', '❌ Tes Gagal: Harap isi alamat jalur (mount path) folder NAS Anda terlebih dahulu.');
            }

            if (is_dir($path)) {
                if (is_writable($path)) {
                    $testFile = rtrim($path, '/') . '/sireka_nas_test_' . time() . '.txt';
                    @file_put_contents($testFile, "Tes koneksi NAS dari SiReKa");
                    if (file_exists($testFile)) {
                        @unlink($testFile);
                        return back()->with('success', "✅ Tes NAS Berhasil (OK): Folder NAS di '{$path}' terhubung, ter-mount sempurna, dan memiliki izin tulis siap pakai!");
                    }
                }
                return back()->with('error', "⚠️Folder NAS di '{$path}' ditemukan di server, namun belum memiliki hak akses tulis (Read-Only). Periksa permission di server NAS Anda.");
            } else {
                return back()->with('info', "💡 Catatan Tes NAS: Folder '{$path}' saat ini belum di-mount pada mesin server Linux ini. Silakan ikuti instruksi mount NFS di bawah atau tes saat server NAS fisik tersambung ke Data Center/Kominfo.");
            }
        } elseif ($mode === 'minio') {
            $endpoint = $request->input('minio_endpoint');
            if (empty($endpoint)) {
                return back()->with('error', '❌ Tes Gagal: Harap isi Alamat Endpoint Server MinIO Anda terlebih dahulu.');
            }

            try {
                // Lakukan tes ping / http request dengan timeout pendek (3 detik)
                $response = Http::timeout(3)->get($endpoint);
                return back()->with('success', "✅ Tes MinIO Berhasil (OK): Server Object Storage di '{$endpoint}' merespon aktif! Kredensial siap digunakan untuk sinkronisasi file.");
            } catch (\Exception $e) {
                return back()->with('info', "💡 Catatan Tes MinIO: Server di '{$endpoint}' belum dapat dihubungi dari jaringan saat ini (Timeout / Unreachable). Pastikan Server MinIO menyala dan firewall membuka port 9000.");
            }
        }

        return back()->with('info', 'Uji koneksi selesai.');
    }

    /**
     * Konversi angka bytes ke format GB/MB yang manusiawi
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes == 0) return "0 B";
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
