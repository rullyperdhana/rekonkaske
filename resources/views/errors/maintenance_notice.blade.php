<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiReKa - Sistem Sedang Dalam Pemeliharaan (Under Maintenance)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen text-slate-100 flex items-center justify-center p-6 antialiased selection:bg-amber-500 selection:text-slate-950">
    <div class="max-w-xl w-full bg-slate-800/80 backdrop-blur-xl border border-slate-700/80 rounded-3xl shadow-2xl p-8 sm:p-10 text-center relative overflow-hidden">
        <!-- Glow Effect Background -->
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Icon Pulse -->
        <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center">
            <span class="absolute inset-0 bg-amber-500/20 rounded-full animate-ping opacity-75"></span>
            <div class="w-20 h-20 bg-gradient-to-tr from-amber-500 to-amber-400 rounded-full flex items-center justify-center shadow-lg shadow-amber-500/30 text-slate-950">
                <span class="material-symbols-outlined text-4xl" data-weight="fill">engineering</span>
            </div>
        </div>

        <span class="inline-block px-3.5 py-1 bg-amber-500/20 border border-amber-500/40 text-amber-300 font-extrabold text-xs rounded-full uppercase tracking-wider mb-4 shadow-sm">
            🔒 Mode Pengaman Data & Server
        </span>

        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-3">
            Sistem SiReKa Sedang Dalam Pemeliharaan
        </h1>

        <p class="text-sm text-slate-300 leading-relaxed mb-6">
            Administrator saat ini sedang melakukan pembaruan dokumen, sinkronisasi data arsip, atau perawatan rutin server. Untuk mencegah terjadinya data ganda atau error input pada saat proses sinkronisasi berlangsung, <strong class="text-white">akses input bagi operator SKPD ditangguhkan sementara</strong>.
        </p>

        <!-- Informasi Tambahan dari Admin -->
        <div class="bg-slate-900/90 border border-slate-700 p-5 rounded-2xl text-left space-y-3 mb-8 shadow-inner">
            <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
                <span class="material-symbols-outlined text-amber-400 text-2xl shrink-0" data-weight="fill">info</span>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Alasan Pemeliharaan:</span>
                    <p class="text-sm font-semibold text-white">{{ $status['reason'] ?? 'Sinkronisasi dan peningkatan performa layanan SiReKa.' }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-0.5">
                <span class="text-xs text-slate-400 font-medium">Perkiraan Selesai:</span>
                <span class="text-xs font-bold font-mono bg-indigo-500/20 text-indigo-300 px-2.5 py-1 rounded-lg border border-indigo-500/30">
                    ⏳ {{ $status['estimated_end'] ?? 'Dalam Pengerjaan Admin' }}
                </span>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 text-sm active:scale-95">
                <span class="material-symbols-outlined text-xl">refresh</span>
                <span>Cek Kembali Akses Saya</span>
            </a>

            @auth
            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-slate-700/80 hover:bg-slate-600 text-slate-200 border border-slate-600 font-semibold rounded-xl transition-all flex items-center justify-center gap-2 text-sm active:scale-95">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
            @endauth
        </div>

        <div class="mt-8 pt-6 border-t border-slate-800 text-[11px] text-slate-400 font-medium flex items-center justify-center gap-1.5">
            <span> Badan Keuangan dan Aset Daerah (BKAD) Kabupaten Tapin &bull; SiReKa</span>
        </div>
    </div>
</body>
</html>
