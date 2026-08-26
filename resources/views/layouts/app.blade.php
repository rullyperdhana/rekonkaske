<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#00346f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SiReKa">
    <link rel="apple-touch-icon" href="/icon.svg">

    <title>{{ config('app.name', 'SiReKa') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- NProgress Loading Bar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <style>
        /* Customize NProgress bar color (menggunakan warna inverse-primary yang cerah) */
        #nprogress .bar {
            background: #abc7ff !important;
            height: 4px !important;
            z-index: 9999 !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px #abc7ff, 0 0 5px #abc7ff !important;
        }
        #nprogress .spinner-icon {
            border-top-color: #abc7ff !important;
            border-left-color: #abc7ff !important;
        }
    </style>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "keyframes": {
                        "marquee": {
                            "0%": { transform: "translateX(100vw)" },
                            "100%": { transform: "translateX(-100%)" }
                        }
                    },
                    "animation": {
                        "marquee": "marquee 25s linear infinite"
                    },
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
                        "container-max": "1920px",
                        "margin-desktop": "48px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "baseline": "4px"
                    },
                    "fontFamily": {
                        "label-sm": ["Inter"],
                        "headline-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "data-tabular": ["JetBrains Mono"]
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-lg min-h-screen">
    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity" onclick="toggleSidebar()"></div>

    <!-- SideNavBar -->
    @include('layouts.sidebar')

    <!-- TopNavBar -->
    @include('layouts.topbar')

    <!-- Main Content -->
    <main id="appMain" class="lg:ml-72 pt-24 px-4 lg:px-8 pb-28 lg:pb-20 max-w-container-max mx-auto transition-all duration-300">
        @if(session('success'))
            <div class="mb-6 bg-secondary-container text-on-secondary-container px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm" role="alert">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-body-md font-body-md">{{ session('success') }}</span>
                <button class="ml-auto" onclick="this.parentElement.remove()"><span class="material-symbols-outlined text-sm">close</span></button>
            </div>
        @endif
        
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Aksi Ditolak!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#ba1a1a'
                    });
                });
            </script>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer Marquee Live Log (Admin & Konsolidator) -->
    @if(isset($globalActivities) && $globalActivities->count() > 0)
    <div id="footerMarquee" class="fixed bottom-0 left-0 right-0 z-[45] bg-slate-900 text-slate-100 border-t border-slate-700 py-2.5 overflow-hidden flex items-center shadow-lg lg:ml-72 transition-all duration-300">
        <div class="flex-shrink-0 bg-blue-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-1.5 z-10 shadow-[4px_0_15px_rgba(0,0,0,0.8)] relative">
            <span class="material-symbols-outlined text-[16px] animate-pulse">sensors</span> LIVE LOG
        </div>
        <div class="flex-grow overflow-hidden relative flex items-center h-full group">
            <div class="animate-marquee whitespace-nowrap flex items-center gap-12 pl-4 group-hover:[animation-play-state:paused]">
                @foreach($globalActivities as $act)
                    <div class="flex items-center gap-2 text-sm">
                        @if($act->status_verifikasi == 'verified')
                            <span class="text-emerald-400 material-symbols-outlined text-[16px]">check_circle</span> 
                        @else
                            <span class="text-amber-400 material-symbols-outlined text-[16px]">pending</span>
                        @endif
                        <span class="font-bold text-white">{{ $act->skpd->nama ?? 'Instansi' }}</span>
                        <span class="text-slate-300">({{ $namaBulan[$act->periode_bulan - 1] ?? 'Bulan' }})</span>
                        <span class="italic text-slate-200">{{ $act->status_verifikasi == 'verified' ? 'Telah diverifikasi' : 'Diperbarui' }}</span>
                        <span class="text-slate-400 text-xs">— Oleh {{ $act->user->name ?? 'Sistem' }} ({{ $act->updated_at->diffForHumans() }})</span>
                    </div>
                @endforeach
            </div>
            <!-- Gradient fades -->
            <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-slate-900 to-transparent z-0"></div>
            <div class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-slate-900 to-transparent z-0"></div>
        </div>
    </div>
    @endif

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const topbar = document.getElementById('appTopbar');
            const main = document.getElementById('appMain');
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                const isClosed = sidebar.classList.contains('-translate-x-[120%]') || sidebar.classList.contains('-translate-x-full');
                
                if (isClosed) {
                    sidebar.classList.remove('-translate-x-[120%]', '-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-[120%]');
                    overlay.classList.add('hidden');
                }
            } else {
                // Desktop toggle
                sidebar.classList.toggle('lg:translate-x-0');
                sidebar.classList.toggle('lg:-translate-x-[120%]');
                
                topbar.classList.toggle('lg:ml-72');
                topbar.classList.toggle('lg:ml-0');
                topbar.classList.toggle('lg:w-[calc(100%-18rem)]');
                topbar.classList.toggle('lg:w-full');
                
                main.classList.toggle('lg:ml-72');
                main.classList.toggle('lg:ml-0');
                
                const footerMarquee = document.getElementById('footerMarquee');
                if (footerMarquee) {
                    footerMarquee.classList.toggle('lg:ml-72');
                    footerMarquee.classList.toggle('lg:ml-0');
                }
            }
        }

        // Prevent double submit on all forms with POST/PUT/DELETE method
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form:not(.form-delete)');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const method = form.getAttribute('method') ? form.getAttribute('method').toUpperCase() : 'GET';
                    // Kita biarkan form GET (seperti pencarian) tetap normal, hanya disable untuk form penyimpanan/perubahan
                    if (method !== 'GET') {
                        const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                        submitBtns.forEach(btn => {
                            // Mencegah double click
                            btn.disabled = true;
                            btn.classList.add('opacity-75', 'cursor-not-allowed');
                            
                            // Ubah teks tombol menjadi loading (jika berupa button)
                            if (btn.tagName === 'BUTTON') {
                                if(!btn.dataset.originalText) {
                                    btn.dataset.originalText = btn.innerHTML;
                                }
                                btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm align-middle mr-1" style="animation: spin 1s linear infinite;">autorenew</span> Menyimpan...';
                            }
                        });
                    }
                });
            });

            // SweetAlert for delete forms
            const deleteForms = document.querySelectorAll('.form-delete');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        html: "Data dan file dokumen terkait akan dihapus!<br><br><div class='p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-200 rounded text-sm text-left border border-blue-200 dark:border-blue-800'>💡 <strong>Tips Operator:</strong> Jika hanya terjadi salah cetak angka saldo atau keterangan, disarankan menggunakan tombol <strong>EDIT (✏️)</strong> tanpa perlu menghapus dan mengisi dari awal.</div>",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ba1a1a', // error color
                        cancelButtonColor: '#737783', // outline color
                        confirmButtonText: 'Ya, Tetap Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });

            // NProgress Initialization
            NProgress.configure({ showSpinner: false, speed: 400, minimum: 0.1 });
            
            // Start progress bar when clicking a link
            document.querySelectorAll('a').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    const target = this.getAttribute('target');
                    
                    // Trigger if it's a valid link and not a new tab
                    if (href && !href.startsWith('#') && !href.startsWith('javascript') && target !== '_blank') {
                        NProgress.start();
                    }
                });
            });
            
            // Start progress bar on form submit (excluding GET forms which are usually instant searches, though we can trigger it too)
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    NProgress.start();
                });
            });
        });

        // Hide progress bar when page is fully loaded or restored from bfcache
        window.addEventListener('pageshow', function (event) {
            NProgress.done();
        });

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then((registration) => {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, (err) => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>

    <!-- Mobile Bottom Navigation -->
    @include('layouts.bottom-nav')
</body>
</html>
