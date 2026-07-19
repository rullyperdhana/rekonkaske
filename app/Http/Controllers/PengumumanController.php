<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = \App\Models\Pengumuman::orderBy('created_at', 'desc')->paginate(10);
        return view('pengaturan.pengumuman.index', compact('pengumumans'));
    }

    public function create()
    {
        return view('pengaturan.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'is_aktif' => 'boolean',
        ]);

        \App\Models\Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'is_aktif' => $request->has('is_aktif') ? $request->is_aktif : true,
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(\App\Models\Pengumuman $pengumuman)
    {
        return view('pengaturan.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, \App\Models\Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'is_aktif' => 'boolean',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'is_aktif' => $request->has('is_aktif') ? $request->is_aktif : false,
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(\App\Models\Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
