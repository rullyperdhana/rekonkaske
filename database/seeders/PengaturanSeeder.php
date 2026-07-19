<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Pengaturan::create([
            'nama_pemerintah' => 'PEMERINTAH KABUPATEN TAPIN',
            'nama_instansi' => 'BADAN KEUANGAN DAN ASET DAERAH',
            'jalan' => 'Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru',
            'kecamatan' => 'RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173',
            'kontak' => 'Kode Pos 71114 Email: bkad@tapinkab.go.id',
            'kota' => 'RANTAU',
            'logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGQglX4a91lGBKJ3x84BjayBzB86CFjav3SqOK5oE63MWbYO2Qcazq0aldyUiq4O4QUHgyHX3dIYsy_YZxQrgNA3gnZu-9IDh5PBQyqlamviMO9EYFfXzj-ZmB1cLlx2nTyOGUzDWwaUmkCW2sxkgnhAFG2520U_AyWNIov7XjxkjfYKcEDsZudVlfdUva_l58gAIdKZlkfCSf_qyyKiJjlMlPtKy6VdEbjqUDxlo92seLSowz38NN',
            'jabatan_penandatangan' => 'Pengguna Anggaran / Kuasa Pengguna Anggaran',
            'nama_penandatangan' => 'DR. H. ZAINAL AQLI, S.T. M.T',
            'nip_penandatangan' => '19690214 199403 1 011',
        ]);
    }
}
