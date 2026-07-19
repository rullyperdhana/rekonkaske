<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - SiReKe (Sistem Rekonsiliasi Kas)</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#00346f",
                        "error": "#ba1a1a",
                        "secondary-fixed-dim": "#88d982",
                        "primary-container": "#004a99",
                        "tertiary-fixed": "#ffdcbe",
                        "surface-bright": "#f8f9fb",
                        "surface-dim": "#d8dadc",
                        "on-error-container": "#93000a",
                        "on-secondary-container": "#217128",
                        "on-primary": "#ffffff",
                        "on-error": "#ffffff",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#424751",
                        "on-secondary-fixed-variant": "#005312",
                        "secondary-fixed": "#a3f69c",
                        "primary-fixed": "#d7e2ff",
                        "on-tertiary": "#ffffff",
                        "tertiary-fixed-dim": "#ffb870",
                        "on-primary-fixed-variant": "#00458f",
                        "surface-container-low": "#f2f4f6",
                        "on-background": "#191c1e",
                        "surface-container-highest": "#e0e3e5",
                        "primary-fixed-dim": "#abc7ff",
                        "error-container": "#ffdad6",
                        "secondary": "#1b6d24",
                        "surface-variant": "#e0e3e5",
                        "surface": "#f8f9fb",
                        "surface-container-high": "#e6e8ea",
                        "on-tertiary-container": "#ffaa4d",
                        "outline": "#737783",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-container": "#9bbdff",
                        "secondary-container": "#a0f399",
                        "surface-tint": "#255dad",
                        "inverse-primary": "#abc7ff",
                        "on-surface": "#191c1e",
                        "on-secondary-fixed": "#002204",
                        "tertiary": "#512d00",
                        "inverse-on-surface": "#eff1f3",
                        "on-tertiary-fixed-variant": "#693c00",
                        "outline-variant": "#c2c6d3",
                        "on-primary-fixed": "#001b3f",
                        "tertiary-container": "#714000",
                        "inverse-surface": "#2d3133",
                        "background": "#f8f9fb",
                        "on-tertiary-fixed": "#2c1600",
                        "surface-container": "#eceef0"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "baseline": "4px",
                        "container-max": "1200px",
                        "gutter": "24px",
                        "margin-desktop": "48px",
                        "margin-mobile": "16px"
                    },
                    "fontFamily": {
                        "body-md": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-sm": ["Inter"],
                        "label-sm": ["Inter"],
                        "data-tabular": ["JetBrains Mono"],
                        "headline-lg": ["Inter"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background min-h-screen flex items-center justify-center font-body-md text-body-md text-on-surface antialiased p-margin-mobile md:p-margin-desktop">
<!-- Main Container -->
<main class="w-full max-w-[1000px] flex flex-col md:flex-row bg-surface-container-lowest rounded-xl shadow-[0_4px_24px_rgba(0,0,0,0.05)] border border-outline-variant overflow-hidden">
    <!-- Left Side: Branding & Info (Hidden on Mobile) -->
    <section class="hidden md:flex flex-col justify-between w-1/2 p-12 bg-gradient-to-br from-primary-container to-primary text-on-primary relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary-fixed/20 rounded-full blur-3xl pointer-events-none transform translate-x-1/3 translate-y-1/3"></div>
        <div class="z-10">
            @php
                $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
                $logoApp = ($pengaturanGlobal && $pengaturanGlobal->logo) 
                    ? (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http') ? $pengaturanGlobal->logo : asset('storage/' . $pengaturanGlobal->logo)) 
                    : null;
            @endphp
            <div class="flex items-center gap-3 mb-8">
                @if($logoApp)
                    <img src="{{ $logoApp }}" alt="Logo" class="w-12 h-12 object-contain rounded bg-white p-1">
                @else
                    <span class="material-symbols-outlined text-4xl" data-weight="fill">account_balance</span>
                @endif
                <h1 class="font-headline-lg text-headline-lg font-bold tracking-tight">SiReKe</h1>
            </div>
            <p class="font-body-lg text-body-lg text-primary-fixed mb-4">Sistem Rekonsiliasi Kas</p>
            <p class="font-body-md text-body-md text-on-primary/80 leading-relaxed max-w-sm">
                Platform terpadu untuk rekonsiliasi kas Bendahara Pengeluaran. Menyediakan tingkat presisi dan keamanan tinggi dalam pengelolaan data keuangan daerah.
            </p>
        </div>
        <div class="z-10 mt-auto">
            <div class="flex items-center gap-4 text-primary-fixed-dim">
                <span class="material-symbols-outlined" data-weight="fill">security</span>
                <p class="font-label-sm text-label-sm uppercase tracking-wider">Akses Terenkripsi & Terpantau</p>
            </div>
        </div>
    </section>

    <!-- Right Side: Login Form -->
    <section class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-surface-container-lowest">
        <!-- Mobile Branding -->
        <div class="md:hidden flex flex-col items-center mb-8 text-center">
            @if($logoApp)
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 p-2 shadow-sm border border-outline-variant">
                    <img src="{{ $logoApp }}" alt="Logo" class="max-w-full max-h-full object-contain">
                </div>
            @else
                <div class="w-16 h-16 bg-primary-container rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl text-on-primary" data-weight="fill">account_balance</span>
                </div>
            @endif
            <h1 class="font-headline-md text-headline-md font-bold text-primary">SiReKe</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Sistem Rekonsiliasi Kas</p>
        </div>

        <div class="mb-8">
            <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Selamat Datang</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Silakan masuk menggunakan kredensial Anda.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form action="{{ route('login') }}" class="space-y-6" method="POST">
            @csrf

            <!-- Tahun Login -->
            @php
                $tahunAnggarans = \App\Models\TahunAnggaran::where('is_active', true)->orderBy('tahun', 'desc')->get();
            @endphp
            <div class="space-y-1.5">
                <label class="font-label-sm text-label-sm text-on-surface block" for="tahun_login">Tahun Anggaran</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline">calendar_month</span>
                    </div>
                    <select class="block w-full pl-10 pr-10 py-2.5 h-[40px] border border-outline-variant rounded-lg bg-surface-bright text-on-surface focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 outline-none font-body-md text-body-md appearance-none" 
                        id="tahun_login" name="tahun_login" required>
                        @forelse($tahunAnggarans as $ta)
                            <option value="{{ $ta->tahun }}" {{ date('Y') == $ta->tahun ? 'selected' : '' }}>{{ $ta->tahun }}</option>
                        @empty
                            <option value="{{ date('Y') }}">{{ date('Y') }} (Default)</option>
                        @endforelse
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline">expand_more</span>
                    </div>
                </div>
            </div>

            <!-- Email Address (using email as username for breeze default) -->
            <div class="space-y-1.5">
                <label class="font-label-sm text-label-sm text-on-surface block" for="email">Email Pengguna</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline">person</span>
                    </div>
                    <input class="block w-full pl-10 pr-3 py-2.5 h-[40px] border border-outline-variant rounded-lg bg-surface-bright text-on-surface placeholder:text-outline-variant focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 outline-none font-body-md text-body-md" 
                        id="email" name="email" placeholder="Masukkan Email" required type="email" value="{{ old('email') }}" autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-error" />
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label class="font-label-sm text-label-sm text-on-surface block" for="password">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline">lock</span>
                    </div>
                    <input class="block w-full pl-10 pr-10 py-2.5 h-[40px] border border-outline-variant rounded-lg bg-surface-bright text-on-surface placeholder:text-outline-variant focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 outline-none font-body-md text-body-md" 
                        id="password" name="password" placeholder="••••••••" required type="password" autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-error" />
            </div>

            <!-- Math Captcha -->
            <div class="space-y-1.5">
                <label class="font-label-sm text-label-sm text-on-surface block" for="captcha">Pertanyaan Keamanan: Berapa {{ $num1 ?? 0 }} + {{ $num2 ?? 0 }}?</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline">calculate</span>
                    </div>
                    <input class="block w-full pl-10 pr-3 py-2.5 h-[40px] border border-outline-variant rounded-lg bg-surface-bright text-on-surface placeholder:text-outline-variant focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 outline-none font-body-md text-body-md" 
                        id="captcha" name="captcha" placeholder="Jawaban" required type="number" />
                </div>
                <x-input-error :messages="$errors->get('captcha')" class="mt-2 text-error" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center pt-2">
                <div class="flex items-center">
                    <input class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-background bg-surface-bright cursor-pointer" id="remember_me" name="remember" type="checkbox"/>
                    <label class="ml-2 block font-body-md text-body-md text-on-surface-variant cursor-pointer" for="remember_me">
                        Ingat saya
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm font-label-sm text-label-sm text-on-primary bg-primary hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200 h-[40px]" type="submit">
                    Masuk Sistem
                    <span class="material-symbols-outlined ml-2 text-[18px]">login</span>
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-outline-variant/50 text-center">
            <p class="font-label-sm text-label-sm text-on-surface-variant">
                © 2024 Pemerintah Kabupaten Tapin<br/>
                <span class="font-body-md text-body-md text-outline mt-1 block">Badan Keuangan dan Aset Daerah - Dukungan Teknis</span>
            </p>
        </div>
    </section>
</main>
</body>
</html>
