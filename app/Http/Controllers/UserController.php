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
            $validated['password_changed_at'] = null;
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
}
