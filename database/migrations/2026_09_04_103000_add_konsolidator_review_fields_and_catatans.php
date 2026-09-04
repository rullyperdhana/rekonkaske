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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status_konsolidator')->default('menunggu')->after('status_verifikasi');
            $table->text('catatan_konsolidator_terakhir')->nullable()->after('status_konsolidator');
            $table->foreignId('checked_by')->nullable()->after('catatan_konsolidator_terakhir')->constrained('users')->nullOnDelete();
            $table->timestamp('checked_at')->nullable()->after('checked_by');
        });

        Schema::create('transaksi_catatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status_pemeriksaan')->default('perlu_perbaikan');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_catatans');

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['checked_by']);
            $table->dropColumn([
                'status_konsolidator',
                'catatan_konsolidator_terakhir',
                'checked_by',
                'checked_at',
            ]);
        });
    }
};
