<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <div class="flex items-center gap-2 text-primary text-xs font-bold uppercase tracking-wider mb-1">
                    <span class="material-symbols-outlined text-[18px]">account_circle</span>
                    <span>Pengaturan Akun</span>
                </div>
                <h1 class="text-headline-lg font-headline-lg font-bold text-on-surface">Profil Saya</h1>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1">Kelola data profil pengguna dan preferensi keamanan Anda di SiReKa BKAD Kabupaten Tapin.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-3.5 py-1.5 bg-secondary-container text-on-secondary-container rounded-full text-label-sm font-bold flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                    TA. {{ session('tahun_login') ?? date('Y') }}
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-error/10 text-error p-4 rounded-xl border border-error/20 flex items-start gap-3">
                <span class="material-symbols-outlined text-error text-[22px] shrink-0 mt-0.5">error</span>
                <div class="space-y-1">
                    <p class="font-bold text-sm">Gagal memperbarui profil:</p>
                    <ul class="list-disc pl-5 space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Bento Grid Container -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Column: Identity & Security Card (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- User Card -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-primary to-primary-container relative">
                        <div class="absolute -bottom-10 left-6">
                            <div class="w-20 h-20 rounded-2xl bg-surface-container-lowest border-4 border-surface-container-lowest shadow-md flex items-center justify-center text-primary font-black text-2xl tracking-wider uppercase">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-12 p-6 space-y-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-xl font-bold text-on-surface leading-snug">{{ $user->name }}</h3>
                                @if($user->status == 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-700 border border-emerald-500/30" title="Akun Aktif">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-500/15 text-gray-700 border border-gray-500/30">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs font-mono text-on-surface-variant mt-0.5">{{ '@' . ($user->username ?? 'user') }}</p>
                        </div>

                        <div class="border-t border-outline-variant/60 pt-4 space-y-3 text-sm">
                            <!-- Role Badge -->
                            <div class="flex items-center justify-between">
                                <span class="text-on-surface-variant text-xs">Peran Akses:</span>
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider
                                    @if($user->role === 'admin')
                                        bg-primary/10 text-primary border border-primary/20
                                    @elseif($user->role === 'konsolidator')
                                        bg-amber-500/10 text-amber-700 border border-amber-500/20
                                    @else
                                        bg-blue-500/10 text-blue-700 border border-blue-500/20
                                    @endif">
                                    {{ $user->role === 'admin' ? 'Administrator' : ($user->role === 'konsolidator' ? 'Konsolidator Daerah' : 'Operator SKPD') }}
                                </span>
                            </div>

                            <!-- NIP -->
                            <div class="flex items-center justify-between">
                                <span class="text-on-surface-variant text-xs">NIP Pegawai:</span>
                                <span class="font-mono text-xs font-bold text-on-surface">{{ $user->nip ?: '-' }}</span>
                            </div>

                            <!-- SKPD -->
                            <div class="flex flex-col gap-1">
                                <span class="text-on-surface-variant text-xs">Instansi / Unit Kerja:</span>
                                <div class="p-2.5 rounded-lg bg-surface-container-low border border-outline-variant/50 text-xs font-semibold text-on-surface flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-primary shrink-0 mt-0.5">apartment</span>
                                    <span>
                                        @if($user->skpd)
                                            <span class="font-mono text-primary mr-1">[{{ $user->skpd->kode }}]</span>
                                            {{ $user->skpd->nama }}
                                        @else
                                            Badan Keuangan dan Aset Daerah (BKAD) Kabupaten Tapin
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Created At -->
                            <div class="flex items-center justify-between text-xs text-on-surface-variant pt-2 border-t border-outline-variant/40">
                                <span>Terdaftar Sejak:</span>
                                <span class="font-medium text-on-surface">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</span>
                            </div>

                            <!-- Updated At -->
                            <div class="flex items-center justify-between text-xs text-on-surface-variant">
                                <span>Pembaruan Terakhir:</span>
                                <span class="font-medium text-on-surface">{{ $user->updated_at ? $user->updated_at->diffForHumans() : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Action: Ubah Password -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0 border border-amber-500/20">
                            <span class="material-symbols-outlined text-[20px]">key</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-on-surface">Kata Sandi & Keamanan</h4>
                            <p class="text-xs text-on-surface-variant">Perbarui password Anda secara berkala.</p>
                        </div>
                    </div>
                    <a href="{{ route('password.edit') }}" class="w-full py-2.5 px-4 rounded-xl bg-surface-container-low hover:bg-primary hover:text-on-primary text-on-surface font-semibold text-xs flex items-center justify-center gap-2 border border-outline-variant/70 transition-all">
                        <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                        <span>Buka Form Ubah Password</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Edit Profile Form (7 cols) -->
            <div class="lg:col-span-7">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-surface-container-low border-b border-outline-variant/60 p-5 flex items-center justify-between">
                        <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">badge</span>
                            Informasi Data Diri
                        </h3>
                        <span class="text-xs text-on-surface-variant">Formulir Pembaruan</span>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-5">
                        @csrf
                        @method('PATCH')

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                                Nama Lengkap <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">person</span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full h-11 pl-10 pr-3 rounded-lg border border-outline-variant bg-surface text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    placeholder="Masukkan nama lengkap Anda">
                            </div>
                            <p class="text-[11px] text-on-surface-variant mt-1">Nama ini akan tercantum pada riwayat berkas dan log rekonsiliasi.</p>
                        </div>

                        <!-- NIP -->
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                                NIP (Nomor Induk Pegawai)
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">badge</span>
                                <input type="text" name="nip" value="{{ old('nip', $user->nip) }}"
                                    class="w-full h-11 pl-10 pr-3 rounded-lg border border-outline-variant bg-surface text-body-md font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    placeholder="contoh: 19850115 201001 1 002">
                            </div>
                            <p class="text-[11px] text-on-surface-variant mt-1">NIP akan otomatis tercantum pada stempel pengesahan dan lembar verifikasi digital.</p>
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                                Username <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">alternate_email</span>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                    class="w-full h-11 pl-10 pr-3 rounded-lg border border-outline-variant bg-surface text-body-md font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    placeholder="contoh: operator_tapin">
                            </div>
                            <p class="text-[11px] text-on-surface-variant mt-1">Username digunakan sebagai alternatif email untuk login ke sistem.</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                                Alamat Email Dinas / Resmi <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">mail</span>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full h-11 pl-10 pr-3 rounded-lg border border-outline-variant bg-surface text-body-md font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    placeholder="user@tapinkab.go.id">
                            </div>
                        </div>

                        <!-- Info Non-Editable: Peran & SKPD -->
                        <div class="pt-4 border-t border-outline-variant/60 space-y-4 bg-surface-container-low/30 -mx-6 px-6 pb-4">
                            <div class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                                <span class="material-symbols-outlined text-[16px]">lock</span>
                                <span>Informasi Kewenangan (Hanya Dapat Diubah oleh Admin Pusat)</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-medium text-on-surface-variant mb-1">Peran Pengguna</label>
                                    <input type="text" value="{{ strtoupper($user->role) }}" disabled
                                        class="w-full h-9 px-3 rounded border border-outline-variant/60 bg-surface-container text-xs font-bold text-on-surface cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-medium text-on-surface-variant mb-1">Unit Kerja / SKPD</label>
                                    <input type="text" value="{{ $user->skpd->nama ?? 'BKAD KABUPATEN TAPIN' }}" disabled
                                        class="w-full h-9 px-3 rounded border border-outline-variant/60 bg-surface-container text-xs font-bold text-on-surface cursor-not-allowed">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 flex items-center justify-between border-t border-outline-variant/60">
                            <span class="text-xs text-on-surface-variant">Pastikan data yang dimasukkan sudah valid.</span>
                            <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-xl font-label-sm font-bold shadow hover:bg-primary/90 transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
