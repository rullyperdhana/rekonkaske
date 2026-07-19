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
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemerintah')->default('PEMERINTAH KABUPATEN TAPIN');
            $table->string('nama_instansi')->default('BADAN KEUANGAN DAN ASET DAERAH');
            $table->string('jalan')->default('Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru');
            $table->string('kecamatan')->default('RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173');
            $table->string('kontak')->default('Kode Pos 71114 Email: bkad@tapinkab.go.id');
            $table->string('kota')->default('RANTAU');
            $table->text('logo')->nullable();
            
            // Penandatangan (Mengetahui)
            $table->string('jabatan_penandatangan')->default('Pengguna Anggaran / Kuasa Pengguna Anggaran');
            $table->string('nama_penandatangan')->nullable();
            $table->string('nip_penandatangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
