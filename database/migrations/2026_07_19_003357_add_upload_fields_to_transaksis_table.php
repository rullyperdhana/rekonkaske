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
            $table->string('file_ba_manual')->nullable()->after('file_bukti');
            $table->string('file_buku_kas')->nullable()->after('file_ba_manual');
            $table->string('file_buku_pembantu_bank')->nullable()->after('file_buku_kas');
            $table->string('file_rekening_koran')->nullable()->after('file_buku_pembantu_bank');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran']);
        });
    }
};
