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
        Route::get('pengaturan/log', [\App\Http\Controllers\LogController::class, 'index'])->name('log.index');
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

    // Pengaturan Instansi (All Users)
    Route::get('pengaturan/instansi', [\App\Http\Controllers\PengaturanController::class, 'edit'])->name('pengaturan.instansi.edit');
    Route::put('pengaturan/instansi', [\App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.instansi.update');

    // Pengaturan Password (All Users)
    Route::get('pengaturan/password', [\App\Http\Controllers\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('pengaturan/password', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');
});

require __DIR__.'/auth.php';
