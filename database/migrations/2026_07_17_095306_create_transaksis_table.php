<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpd_id')->constrained('skpds')->onDelete('cascade');
            $table->foreignId('rekening_id')->constrained('rekenings')->onDelete('cascade');
            $table->integer('periode_bulan');
            $table->integer('periode_tahun');
            $table->decimal('bku_saldo_awal', 20, 2)->default(0);
            $table->decimal('bku_penerimaan', 20, 2)->default(0);
            $table->decimal('bku_pengeluaran', 20, 2)->default(0);
            $table->decimal('bku_saldo_akhir', 20, 2)->default(0);
            $table->decimal('bank_saldo_awal', 20, 2)->default(0);
            $table->decimal('bank_penerimaan', 20, 2)->default(0);
            $table->decimal('bank_pengeluaran', 20, 2)->default(0);
            $table->decimal('bank_saldo_akhir', 20, 2)->default(0);
            $table->text('keterangan_selisih')->nullable();
            $table->enum('status_verifikasi', ['draft', 'verified'])->default('draft');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
