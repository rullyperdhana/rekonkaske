<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    public function edit()
    {
        $skpdId = auth()->user()->skpd_id;
        $skpdName = auth()->user()->skpd ? auth()->user()->skpd->nama : 'BADAN KEUANGAN DAN ASET DAERAH';
        
        $pengaturan = Pengaturan::firstOrCreate(
            ['skpd_id' => $skpdId],
            [
                'isi_kop' => "PEMERINTAH KABUPATEN TAPIN|{$skpdName}|Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru|RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173",
                'nama_kepala' => 'NAMA KEPALA SKPD',
                'nip_kepala' => '19700101 200001 1 001',
                'pangkat_kepala' => 'PEMBINA (IV/a)',
                'jabatan_kepala' => 'KEPALA BADAN',
                'nama_bendahara' => 'NAMA BENDAHARA',
                'nip_bendahara' => '19800101 200501 2 001',
                'pangkat_bendahara' => 'PENATA (III/c)',
                'jabatan_bendahara' => 'BENDAHARA PENGELUARAN',
                'nama_kasubag' => 'NAMA KASUBAG KEUANGAN',
                'nip_kasubag' => '19850101 201001 1 001',
                'pangkat_kasubag' => 'PENATA MUDA (III/a)',
                'jabatan_kasubag' => 'KASUBAG KEUANGAN',
            ]
        );

        return view('pengaturan.instansi.edit', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $skpdId = auth()->user()->skpd_id;
        $pengaturan = Pengaturan::firstOrCreate(['skpd_id' => $skpdId]);
        
        $validated = $request->validate([
            'isi_kop' => 'required|string',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'nama_kepala' => 'required|string|max:255',
            'nip_kepala' => 'required|string|max:255',
            'pangkat_kepala' => 'required|string|max:255',
            'jabatan_kepala' => 'required|string|max:255',
            'nama_bendahara' => 'required|string|max:255',
            'nip_bendahara' => 'required|string|max:255',
            'pangkat_bendahara' => 'required|string|max:255',
            'jabatan_bendahara' => 'required|string|max:255',
            'nama_kasubag' => 'required|string|max:255',
            'nip_kasubag' => 'required|string|max:255',
            'pangkat_kasubag' => 'required|string|max:255',
            'jabatan_kasubag' => 'required|string|max:255',
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('logos', 'public');
            $validated['logo'] = $path;
        }
        unset($validated['logo_file']);

        if (auth()->user()->role === 'admin') {
            $validated['is_registration_open'] = $request->has('is_registration_open') ? true : false;
        }

        $pengaturan->update($validated);

        return redirect()->route('pengaturan.instansi.edit')->with('success', 'Pengaturan instansi berhasil diperbarui.');
    }
}
