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
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pemerintah',
                'nama_instansi',
                'jalan',
                'kecamatan',
                'kontak',
                'kota',
                'jabatan_penandatangan',
                'nama_penandatangan',
                'nip_penandatangan'
            ]);

            $table->text('isi_kop')->nullable();
            
            $table->string('nama_kepala')->nullable();
            $table->string('nip_kepala')->nullable();
            $table->string('pangkat_kepala')->nullable();
            $table->string('jabatan_kepala')->nullable();
            
            $table->string('nama_bendahara')->nullable();
            $table->string('nip_bendahara')->nullable();
            $table->string('pangkat_bendahara')->nullable();
            $table->string('jabatan_bendahara')->nullable();
            
            $table->string('nama_kasubag')->nullable();
            $table->string('nip_kasubag')->nullable();
            $table->string('pangkat_kasubag')->nullable();
            $table->string('jabatan_kasubag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn([
                'isi_kop',
                'nama_kepala', 'nip_kepala', 'pangkat_kepala', 'jabatan_kepala',
                'nama_bendahara', 'nip_bendahara', 'pangkat_bendahara', 'jabatan_bendahara',
                'nama_kasubag', 'nip_kasubag', 'pangkat_kasubag', 'jabatan_kasubag',
            ]);

            $table->string('nama_pemerintah')->nullable();
            $table->string('nama_instansi')->nullable();
            $table->string('jalan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kontak')->nullable();
            $table->string('kota')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->string('nama_penandatangan')->nullable();
            $table->string('nip_penandatangan')->nullable();
        });
    }
};
