<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

use App\Models\User;
use App\Models\Skpd;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('skpd')->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('skpd_id')) {
            $query->where('skpd_id', $request->skpd_id);
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10)->appends($request->query());
        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        return view('pengaturan.user.index', compact('users', 'skpds'));
    }

    public function create()
    {
        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        return view('pengaturan.user.create', compact('skpds'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        return view('pengaturan.user.edit', compact('user', 'skpds'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $transaksiCount = \App\Models\Transaksi::where('user_id', $user->id)->count();
        if ($transaksiCount > 0) {
            return back()->with('error', 'Penghapusan dibatalkan demi keamanan! Pengguna ini memiliki ' . $transaksiCount . ' data transaksi yang saling terikat. Silakan ubah status pengguna menjadi "Non-Aktif" melalui fitur Edit.');
        }

        $user->delete();
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Mencetak Laporan Pengecekan Internal Akun SKPD dan Daftar Pengguna (PDF)
     */
    public function cetakLaporan(Request $request)
    {
        $skpds = Skpd::with('users')->where('status', true)->orderBy('kode', 'asc')->get();
        $nonSkpdUsers = User::whereNull('skpd_id')->orderBy('role', 'asc')->orderBy('name', 'asc')->get();

        $totalSkpd = $skpds->count();
        $skpdSudahAdaUser = $skpds->filter(function ($s) {
            return $s->users->count() > 0;
        })->count();
        $skpdBelumAdaUser = $totalSkpd - $skpdSudahAdaUser;
        $totalUsers = User::count();

        $pengaturan = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();

        // Waktu cetak dengan terjemahan bahasa Indonesia otomatis
        $now = \Carbon\Carbon::now();
        $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$now->dayOfWeek];
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$now->month - 1];
        $tanggalCetak = "{$namaHari}, {$now->day} {$namaBulan} {$now->year} - Pukul " . $now->format('H:i') . " WIB";

        $adminPencetak = auth()->user()->name . " (" . ucfirst(auth()->user()->role) . ")";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengaturan.user.laporan_pdf', compact(
            'skpds',
            'nonSkpdUsers',
            'totalSkpd',
            'skpdSudahAdaUser',
            'skpdBelumAdaUser',
            'totalUsers',
            'pengaturan',
            'tanggalCetak',
            'adminPencetak'
        ));

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Audit_Kepemilikan_Akun_SKPD_' . date('Ymd_His') . '.pdf');
    }
}
