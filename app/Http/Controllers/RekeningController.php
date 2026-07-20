<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Rekening;

class RekeningController extends Controller
{
    public function index()
    {
        $query = Rekening::with('skpd')->orderBy('nama');
        if (auth()->user()->role === 'operator') {
            $query->where('skpd_id', auth()->user()->skpd_id);
        }
        $rekenings = $query->paginate(10);
        return view('master.rekening.index', compact('rekenings'));
    }

    public function create()
    {
        if (auth()->user()->role === 'konsolidator') abort(403);
        $skpds = \App\Models\Skpd::orderBy('nama')->get();
        return view('master.rekening.create', compact('skpds'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'konsolidator') abort(403);
        $rules = [
            'nama' => 'required|max:255',
            'nomor' => 'required|max:255',
            'bank' => 'required|max:255',
            'status' => 'boolean',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['skpd_id'] = 'required|exists:skpds,id';
        }

        $validated = $request->validate($rules);

        if (auth()->user()->role === 'operator') {
            $validated['skpd_id'] = auth()->user()->skpd_id;
        }

        Rekening::create($validated);

        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function edit(Rekening $rekening)
    {
        if (auth()->user()->role === 'konsolidator') abort(403);
        if (auth()->user()->role === 'operator' && $rekening->skpd_id !== auth()->user()->skpd_id) {
            abort(403, 'Unauthorized action.');
        }
        $skpds = \App\Models\Skpd::orderBy('nama')->get();
        return view('master.rekening.edit', compact('rekening', 'skpds'));
    }

    public function update(Request $request, Rekening $rekening)
    {
        if (auth()->user()->role === 'konsolidator') abort(403);
        if (auth()->user()->role === 'operator' && $rekening->skpd_id !== auth()->user()->skpd_id) {
            abort(403, 'Unauthorized action.');
        }

        $rules = [
            'nama' => 'required|max:255',
            'nomor' => 'required|max:255',
            'bank' => 'required|max:255',
            'status' => 'boolean',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['skpd_id'] = 'required|exists:skpds,id';
        }

        $validated = $request->validate($rules);

        if (auth()->user()->role === 'operator') {
            $validated['skpd_id'] = auth()->user()->skpd_id;
        }

        $rekening->update($validated);

        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil diperbarui.');
    }

    public function destroy(Rekening $rekening)
    {
        if (auth()->user()->role === 'konsolidator') abort(403);
        if (auth()->user()->role === 'operator' && $rekening->skpd_id !== auth()->user()->skpd_id) {
            abort(403, 'Unauthorized action.');
        }
        $rekening->delete();
        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil dihapus.');
    }
}
