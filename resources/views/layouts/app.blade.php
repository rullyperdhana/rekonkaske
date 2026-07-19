<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiReKa') }}</title>

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
    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900/50 z-20 hidden lg:hidden backdrop-blur-sm transition-opacity" onclick="toggleSidebar()"></div>

    <!-- SideNavBar -->
    @include('layouts.sidebar')

    <!-- TopNavBar -->
    @include('layouts.topbar')

    <!-- Main Content -->
    <main id="appMain" class="lg:ml-64 pt-24 px-4 lg:px-8 pb-12 max-w-container-max mx-auto transition-all duration-300">
        @if(session('success'))
            <div class="mb-6 bg-secondary-container text-on-secondary-container px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm" role="alert">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-body-md font-body-md">{{ session('success') }}</span>
                <button class="ml-auto" onclick="this.parentElement.remove()"><span class="material-symbols-outlined text-sm">close</span></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 bg-error-container text-on-error-container px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm" role="alert">
                <span class="material-symbols-outlined">error</span>
                <span class="text-body-md font-body-md">{{ session('error') }}</span>
                <button class="ml-auto" onclick="this.parentElement.remove()"><span class="material-symbols-outlined text-sm">close</span></button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const topbar = document.getElementById('appTopbar');
            const main = document.getElementById('appMain');
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            } else {
                // Desktop toggle
                sidebar.classList.toggle('lg:translate-x-0');
                sidebar.classList.toggle('lg:-translate-x-full');
                
                topbar.classList.toggle('lg:ml-64');
                topbar.classList.toggle('lg:ml-0');
                topbar.classList.toggle('lg:w-[calc(100%-16rem)]');
                topbar.classList.toggle('lg:w-full');
                
                main.classList.toggle('lg:ml-64');
                main.classList.toggle('lg:ml-0');
            }
        }
    </script>
</body>
</html>
