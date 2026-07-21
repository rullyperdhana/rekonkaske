<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Skpd;
use App\Models\Pengaturan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        $pengaturan = Pengaturan::whereNull('skpd_id')->first();
        if (!$pengaturan || !$pengaturan->is_registration_open) {
            return redirect()->route('login')->with('error', 'Pendaftaran mandiri sedang ditutup oleh Admin.');
        }

        // Ambil SKPD yang belum punya user dengan role operator
        $skpds = Skpd::whereDoesntHave('users')->orderBy('nama', 'asc')->get();

        return view('auth.register', compact('skpds'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $pengaturan = Pengaturan::whereNull('skpd_id')->first();
        if (!$pengaturan || !$pengaturan->is_registration_open) {
            return redirect()->route('login')->with('error', 'Pendaftaran mandiri sedang ditutup oleh Admin.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'skpd_id' => ['required', 'exists:skpds,id'],
            'no_whatsapp' => ['required', 'string', 'max:50'],
        ]);

        $existingUser = User::where('skpd_id', $request->skpd_id)->first();
        if ($existingUser) {
            return back()->withInput()->withErrors(['skpd_id' => 'SKPD ini sudah memiliki operator yang terdaftar.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => explode('@', $request->email)[0] . rand(10, 99),
            'password' => Hash::make($request->password),
            'skpd_id' => $request->skpd_id,
            'role' => 'operator',
            'status' => 0,
        ]);

        // Simpan/update nomor WA ke master SKPD
        $skpd = Skpd::find($request->skpd_id);
        if ($skpd) {
            $skpd->update([
                'no_whatsapp' => $request->no_whatsapp
            ]);
        }

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan Admin Pusat untuk diaktifkan.');
    }
}
