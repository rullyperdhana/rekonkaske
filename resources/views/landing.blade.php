<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiReKe') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-container": "#004a99",
                        "surface-container-high": "#e6e8ea",
                        "secondary-fixed": "#a3f69c",
                        "error": "#ba1a1a",
                        "outline": "#737783",
                        "on-surface": "#191c1e",
                        "surface-tint": "#255dad",
                        "on-secondary-container": "#217128",
                        "on-tertiary-container": "#ffaa4d",
                        "surface-dim": "#d8dadc",
                        "tertiary-fixed-dim": "#ffb870",
                        "on-primary-fixed-variant": "#00458f",
                        "inverse-on-surface": "#eff1f3",
                        "surface": "#f8f9fb",
                        "surface-container-low": "#f2f4f6",
                        "error-container": "#ffdad6",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed": "#2c1600",
                        "on-secondary": "#ffffff",
                        "secondary": "#1b6d24",
                        "tertiary": "#512d00",
                        "background": "#f8f9fb",
                        "on-primary": "#ffffff",
                        "surface-container": "#eceef0",
                        "tertiary-container": "#714000",
                        "inverse-primary": "#abc7ff",
                        "primary-fixed-dim": "#abc7ff",
                        "surface-bright": "#f8f9fb",
                        "on-error-container": "#93000a",
                        "on-primary-fixed": "#001b3f",
                        "on-secondary-fixed": "#002204",
                        "surface-container-highest": "#e0e3e5",
                        "surface-variant": "#e0e3e5",
                        "inverse-surface": "#2d3133",
                        "on-error": "#ffffff",
                        "secondary-container": "#a0f399",
                        "primary": "#00346f",
                        "outline-variant": "#c2c6d3",
                        "on-surface-variant": "#424751",
                        "secondary-fixed-dim": "#88d982",
                        "primary-fixed": "#d7e2ff",
                        "on-background": "#191c1e",
                        "on-primary-container": "#9bbdff",
                        "on-secondary-fixed-variant": "#005312",
                        "tertiary-fixed": "#ffdcbe",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-fixed-variant": "#693c00"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "container-max": "1200px",
                        "margin-desktop": "48px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "baseline": "4px"
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col">
    <header class="bg-surface-container-lowest shadow-sm h-20 flex items-center justify-between px-6 lg:px-12 sticky top-0 z-50">
        <div class="flex items-center gap-4">
            @php
                $logoApp = ($pengaturan && $pengaturan->logo) 
                    ? (Str::startsWith($pengaturan->logo, 'http') ? $pengaturan->logo : asset('storage/' . $pengaturan->logo)) 
                    : null;
            @endphp
            @if($logoApp)
                <img src="{{ $logoApp }}" alt="Logo Aplikasi" class="h-10 w-auto object-contain">
            @else
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white">
                    <span class="material-symbols-outlined" data-weight="fill">account_balance</span>
                </div>
            @endif
            <div>
                <h1 class="text-xl font-bold text-primary leading-tight">SiReKe</h1>
                <p class="text-xs text-on-surface-variant font-medium">Sistem Rekonsiliasi BKAD</p>
            </div>
        </div>
        <div>
            @auth
                <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-primary text-on-primary rounded text-sm font-bold shadow hover:bg-primary/90 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-6 py-2.5 bg-primary text-on-primary rounded text-sm font-bold shadow hover:bg-primary/90 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">login</span> Login
                </a>
            @endauth
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-on-surface mb-3">Status Rekonsiliasi SKPD</h2>
            <p class="text-lg text-on-surface-variant">Tahun Anggaran {{ $tahunAktif }}</p>
        </div>

        <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-4 px-6 text-sm text-on-surface font-semibold w-1/3">Nama SKPD</th>
                            <th class="py-4 px-6 text-sm text-on-surface font-semibold text-center w-1/6">Progres</th>
                            <th class="py-4 px-6 text-sm text-on-surface font-semibold">Bulan Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($skpdRekonStatus as $stat)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-4 px-6">
                                <div class="text-on-surface font-semibold">{{ $stat['nama'] }}</div>
                                <div class="text-xs text-on-surface-variant">{{ $stat['kode'] ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($stat['bulan_selesai'] == 12)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-secondary-container text-on-secondary-container">Selesai 100%</span>
                                @elseif($stat['bulan_selesai'] > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-tertiary-container text-on-tertiary-container">{{ $stat['bulan_selesai'] }} Bulan</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-error-container text-on-error-container">Belum</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1">
                                    @for($i = 1; $i <= 12; $i++)
                                        @if(in_array($i, $stat['bulan_list']))
                                            <span class="inline-flex w-7 h-7 rounded items-center justify-center bg-primary text-on-primary text-[11px] font-bold">{{ $i }}</span>
                                        @else
                                            <span class="inline-flex w-7 h-7 rounded items-center justify-center bg-surface-container-highest text-on-surface-variant text-[11px] opacity-40">{{ $i }}</span>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-on-surface-variant">Belum ada data SKPD.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-surface-container-low py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm text-on-surface-variant">
            &copy; {{ date('Y') }} BKAD Kabupaten Tapin. All rights reserved.
        </div>
    </footer>
</body>
</html>
