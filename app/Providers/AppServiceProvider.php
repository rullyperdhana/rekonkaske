<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LogSuccessfulLogin::class
        );
        \Illuminate\Validation\Rules\Password::defaults(function () {
            $rule = \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();

            return app()->isProduction() ? $rule->uncompromised() : $rule;
        });

        // Share namaBulan globally for all views
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        \Illuminate\Support\Facades\View::share('namaBulan', $namaBulan);

        // Dynamic SiReKa Storage Engine Configuration (NAS / MinIO S3)
        try {
            $storageConfigPath = storage_path('app/storage_nas_config.json');
            if (file_exists($storageConfigPath)) {
                $storageConfig = json_decode(file_get_contents($storageConfigPath), true);
                $mode = $storageConfig['mode'] ?? 'local';

                if ($mode === 'nas' && !empty($storageConfig['nas_mount_path'])) {
                    $nasPath = rtrim($storageConfig['nas_mount_path'], '/');
                    config([
                        'filesystems.disks.public.driver' => 'local',
                        'filesystems.disks.public.root' => $nasPath,
                        'filesystems.disks.public.url' => env('APP_URL') . '/storage-stream',
                        'filesystems.disks.public.visibility' => 'public',
                    ]);
                    \Illuminate\Support\Facades\Storage::forgetDisk('public');
                } elseif ($mode === 'minio' && !empty($storageConfig['minio_bucket'])) {
                    config([
                        'filesystems.disks.public.driver' => 's3',
                        'filesystems.disks.public.key' => $storageConfig['minio_key'] ?? '',
                        'filesystems.disks.public.secret' => $storageConfig['minio_secret'] ?? '',
                        'filesystems.disks.public.region' => $storageConfig['minio_region'] ?? 'us-east-1',
                        'filesystems.disks.public.bucket' => $storageConfig['minio_bucket'] ?? '',
                        'filesystems.disks.public.endpoint' => $storageConfig['minio_endpoint'] ?? '',
                        'filesystems.disks.public.use_path_style_endpoint' => ($storageConfig['minio_use_path_style_endpoint'] ?? 'true') === 'true',
                    ]);
                    \Illuminate\Support\Facades\Storage::forgetDisk('public');
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal memuat konfigurasi storage dinamis SiReKa: ' . $e->getMessage());
        }
    }
}
