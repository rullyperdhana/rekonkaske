<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Skpd;

class SkpdController extends Controller
{
    public function index(Request $request)
    {
        $query = Skpd::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }
        $skpds = $query->orderBy('kode')->paginate(10)->withQueryString();
        return view('master.skpd.index', compact('skpds'));
    }

    public function create()
    {
        return view('master.skpd.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:skpds,kode|max:255',
            'nama' => 'required|max:255',
            'no_whatsapp' => 'nullable|string|max:50',
            'nama_bendahara' => 'nullable|max:255',
            'status' => 'boolean',
        ]);

        Skpd::create($validated);

        return redirect()->route('skpd.index')->with('success', 'SKPD berhasil ditambahkan.');
    }

    public function edit(Skpd $skpd)
    {
        return view('master.skpd.edit', compact('skpd'));
    }

    public function update(Request $request, Skpd $skpd)
    {
        $validated = $request->validate([
            'kode' => 'required|max:255|unique:skpds,kode,' . $skpd->id,
            'nama' => 'required|max:255',
            'no_whatsapp' => 'nullable|string|max:50',
            'nama_bendahara' => 'nullable|max:255',
            'status' => 'boolean',
        ]);

        $skpd->update($validated);

        return redirect()->route('skpd.index')->with('success', 'SKPD berhasil diperbarui.');
    }

    public function destroy(Skpd $skpd)
    {
        $skpd->delete();
        return redirect()->route('skpd.index')->with('success', 'SKPD berhasil dihapus.');
    }
}
