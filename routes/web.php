<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\SkpdController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\BaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Master Data (Admin Only)
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        Route::resource('master/skpd', SkpdController::class);
        Route::resource('master/tahun', \App\Http\Controllers\TahunAnggaranController::class)->except(['create', 'show', 'edit']);
        Route::resource('pengaturan/user', UserController::class);
        Route::resource('pengaturan/pengumuman', \App\Http\Controllers\PengumumanController::class);
        Route::get('pengaturan/log', [\App\Http\Controllers\LogController::class, 'index'])->name('log.index');
        
        // Maintenance System
        Route::get('pengaturan/maintenance', [\App\Http\Controllers\MaintenanceController::class, 'index'])->name('pengaturan.maintenance.index');
        Route::post('pengaturan/maintenance/backup', [\App\Http\Controllers\MaintenanceController::class, 'backup'])->name('pengaturan.maintenance.backup');
        Route::post('pengaturan/maintenance/restore', [\App\Http\Controllers\MaintenanceController::class, 'restore'])->name('pengaturan.maintenance.restore');
        Route::delete('pengaturan/maintenance/reset', [\App\Http\Controllers\MaintenanceController::class, 'reset'])->name('pengaturan.maintenance.reset');
    });

    // Laporan (Admin & Konsolidator)
    Route::middleware(['admin.konsolidator'])->group(function () {
        Route::get('/laporan/tunggakan', [\App\Http\Controllers\LaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
        Route::get('/laporan/konsolidasi', [\App\Http\Controllers\LaporanController::class, 'konsolidasi'])->name('laporan.konsolidasi');
        Route::get('/laporan/konsolidasi/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakKonsolidasi'])->name('laporan.konsolidasi.pdf');
    });
    
    // Master Data (All Users)
    Route::resource('master/rekening', RekeningController::class);
    
    // Transaksi (All Users)
    Route::get('transaksi/get-saldo-awal', [TransaksiController::class, 'getSaldoAwal'])->name('transaksi.getSaldoAwal');
    Route::get('transaksi/{transaksi}/upload', [TransaksiController::class, 'uploadForm'])->name('transaksi.upload');
    Route::post('transaksi/{transaksi}/upload', [TransaksiController::class, 'uploadStore'])->name('transaksi.upload.store');
    Route::resource('transaksi', TransaksiController::class);
    
    // Laporan (All Users)
    Route::get('/laporan/ba', [BaController::class, 'index'])->name('ba.index');
    Route::get('/laporan/ba/{transaksi}', [BaController::class, 'show'])->name('ba.show');
    Route::get('/laporan/ba/{transaksi}/pdf', [BaController::class, 'pdf'])->name('ba.pdf');
    Route::get('/laporan/rekap', [\App\Http\Controllers\LaporanController::class, 'rekapTahunan'])->name('laporan.rekap');
    Route::get('/laporan/rekap/pdf', [\App\Http\Controllers\LaporanController::class, 'cetakRekapTahunan'])->name('laporan.rekap.pdf');

    // Pengaturan Instansi (All Users)
    Route::get('pengaturan/instansi', [\App\Http\Controllers\PengaturanController::class, 'edit'])->name('pengaturan.instansi.edit');
    Route::put('pengaturan/instansi', [\App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.instansi.update');

    // Pengaturan Password (All Users)
    Route::get('pengaturan/password', [\App\Http\Controllers\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('pengaturan/password', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');
});

require __DIR__.'/auth.php';
