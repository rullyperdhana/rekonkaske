<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SyncStorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sireka:sync-storage {--limit=500 : Jumlah maksimal file yang disinkro dalam satu eksekusi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasikan dan sinkronkan file dari hard disk lokal lama ke mode Storage Aktif (NAS / MinIO S3)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=====================================================================');
        $this->info('🚀 SiReKa Batch Storage Synchronizer & Archive Migration Engine');
        $this->info('=====================================================================');

        $configPath = storage_path('app/storage_nas_config.json');
        $mode = 'local';
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            $mode = $config['mode'] ?? 'local';
        }

        $this->info("📍 Mode Penyimpanan Aktif Saat Ini: " . strtoupper($mode));

        if ($mode === 'local') {
            $this->warn('⚠️ Mode penyimpanan Anda saat ini adalah LOKAL. Seluruh file memang berada di hard disk lokal server, tidak diperlukan sinkronisasi ekstensi.');
            return self::SUCCESS;
        }

        $baseDir = storage_path('app/public');
        if (!is_dir($baseDir)) {
            $this->error("Folder arsip lokal {$baseDir} tidak ditemukan!");
            return self::FAILURE;
        }

        // Ambil semua file biner dalam folder lokal (termasuk dokumen_rekonsiliasi)
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($baseDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        $toMigrate = [];
        foreach ($files as $file) {
            if ($file->isDir()) continue;

            // Abaikan file gitkeep atau hidden system files
            if ($file->getFilename() === '.gitignore' || str_starts_with($file->getFilename(), '.')) continue;

            $realpath = $file->getRealPath();
            // Jadikan relative path terhadap storage/app/public/
            $relativePath = str_replace('\\', '/', substr($realpath, strlen($baseDir) + 1));
            
            // Cek apakah di storage aktif (MinIO/NAS) sudah ada
            if (!Storage::disk('public')->exists($relativePath)) {
                $toMigrate[] = [
                    'real' => $realpath,
                    'relative' => $relativePath,
                    'size' => $file->getSize()
                ];
            }
        }

        $totalPending = count($toMigrate);
        if ($totalPending === 0) {
            $this->info("🎉 Luar biasa! Seluruh arsip dokumen (0 file tertunda) di hard disk lokal sudah TER-SINKRONISASI SEMPURNA dengan " . strtoupper($mode) . "!");
            return self::SUCCESS;
        }

        $limit = (int) $this->option('limit');
        $batch = array_slice($toMigrate, 0, $limit);

        $this->info("📦 Ditemukan {$totalPending} file arsip lokal yang belum ada di " . strtoupper($mode) . ". Memproses batch sebanyak " . count($batch) . " file...");
        $bar = $this->output->createProgressBar(count($batch));

        $successCount = 0;
        $failCount = 0;

        foreach ($batch as $item) {
            try {
                $content = file_get_contents($item['real']);
                if (Storage::disk('public')->put($item['relative'], $content)) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                $failCount++;
                Log::error("SiReKa Sync Storage Gagal di {$item['relative']}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Berhasil memigrasikan: {$successCount} file.");
        if ($failCount > 0) {
            $this->error("❌ Gagal menyalin: {$failCount} file (Cek hak akses tulis atau koneksi jaringan NAS/MinIO).");
        }

        // Catat di audit log jika ada library activity
        try {
            if (function_exists('activity') && auth()->user()) {
                activity('storage_migration')
                    ->causedBy(auth()->user())
                    ->log("Sinkronisasi Batch Storage ke " . strtoupper($mode) . ": Berhasil menyalin {$successCount} file lama.");
            }
        } catch (\Exception $ex) {
            // Abaikan jika dipacu via CLI murni tanpa otentikasi user
        }

        $sisa = $totalPending - $successCount;
        if ($sisa > 0) {
            $this->warn("⌛ Masih tersisa {$sisa} file. Jalankan kembali perintah ini untuk melanjutkan batch berikutnya!");
        } else {
            $this->info("🎖️ Semua arsip dokumen SiReKa kini telah selesai bermigrasi secara utuh dan aman ke " . strtoupper($mode) . "!");
        }

        return self::SUCCESS;
    }
}
