<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Skpd;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('skpd')->orderBy('name')->paginate(10);
        return view('pengaturan.user.index', compact('users'));
    }

    public function create()
    {
        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        return view('pengaturan.user.create', compact('skpds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults(), 'confirmed'],
            'role' => 'required|in:admin,operator',
            'skpd_id' => 'nullable|exists:skpds,id',
            'status' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $skpds = Skpd::where('status', true)->orderBy('nama')->get();
        return view('pengaturan.user.edit', compact('user', 'skpds'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,operator',
            'skpd_id' => 'nullable|exists:skpds,id',
            'status' => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', \Illuminate\Validation\Rules\Password::defaults(), 'confirmed'];
        }

        $validated = $request->validate($rules);

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

        $user->delete();
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
