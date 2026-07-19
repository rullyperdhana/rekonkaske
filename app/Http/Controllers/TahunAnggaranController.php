<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TahunAnggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAnggarans = \App\Models\TahunAnggaran::orderBy('tahun', 'desc')->get();
        return view('master.tahun.index', compact('tahunAnggarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2000|max:2099|unique:tahun_anggarans,tahun',
        ]);

        \App\Models\TahunAnggaran::create([
            'tahun' => $validated['tahun'],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('tahun.index')->with('success', 'Tahun Anggaran berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $tahun = \App\Models\TahunAnggaran::findOrFail($id);
        
        $tahun->update([
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('tahun.index')->with('success', 'Status Tahun Anggaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $tahun = \App\Models\TahunAnggaran::findOrFail($id);
        $tahun->delete();
        
        return redirect()->route('tahun.index')->with('success', 'Tahun Anggaran berhasil dihapus.');
    }
}
