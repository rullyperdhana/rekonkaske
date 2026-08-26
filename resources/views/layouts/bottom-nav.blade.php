<!-- Bottom Navigation (Mobile Only) -->
<div class="fixed bottom-0 left-0 right-0 bg-surface-container-lowest border-t border-outline-variant/30 z-[60] lg:hidden flex justify-around items-center h-16 px-2 shadow-[0_-4px_15px_rgba(0,0,0,0.05)] transition-all duration-300 pb-safe">
    
    <!-- Beranda -->
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-center group">
        <div class="px-4 py-1 rounded-full transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-container/30 text-primary' : 'text-on-surface-variant group-hover:text-primary' }}">
            <span class="material-symbols-outlined text-[24px]" data-weight="{{ request()->routeIs('dashboard') ? 'fill' : '300' }}">home</span>
        </div>
        <span class="text-[10px] font-medium mt-0.5 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">Beranda</span>
    </a>

    <!-- Entri -->
    <a href="{{ route('transaksi.index') }}" class="flex flex-col items-center justify-center w-full h-full text-center group">
        <div class="px-4 py-1 rounded-full transition-colors {{ request()->routeIs('transaksi.*') ? 'bg-primary-container/30 text-primary' : 'text-on-surface-variant group-hover:text-primary' }}">
            <span class="material-symbols-outlined text-[24px]" data-weight="{{ request()->routeIs('transaksi.*') ? 'fill' : '300' }}">swap_horiz</span>
        </div>
        <span class="text-[10px] font-medium mt-0.5 {{ request()->routeIs('transaksi.*') ? 'text-primary' : 'text-on-surface-variant' }}">Entri</span>
    </a>

    <!-- Laporan -->
    <a href="{{ route('laporan.rekap') }}" class="flex flex-col items-center justify-center w-full h-full text-center group">
        <div class="px-4 py-1 rounded-full transition-colors {{ request()->routeIs('laporan.*') || request()->routeIs('ba.*') || request()->routeIs('dokumen.*') ? 'bg-primary-container/30 text-primary' : 'text-on-surface-variant group-hover:text-primary' }}">
            <span class="material-symbols-outlined text-[24px]" data-weight="{{ request()->routeIs('laporan.*') || request()->routeIs('ba.*') || request()->routeIs('dokumen.*') ? 'fill' : '300' }}">assessment</span>
        </div>
        <span class="text-[10px] font-medium mt-0.5 {{ request()->routeIs('laporan.*') || request()->routeIs('ba.*') || request()->routeIs('dokumen.*') ? 'text-primary' : 'text-on-surface-variant' }}">Laporan</span>
    </a>

    <!-- Menu (Toggles Sidebar) -->
    <button onclick="toggleSidebar()" class="flex flex-col items-center justify-center w-full h-full text-center group">
        <div class="px-4 py-1 rounded-full transition-colors text-on-surface-variant group-hover:text-primary">
            <span class="material-symbols-outlined text-[24px]" data-weight="300">menu</span>
        </div>
        <span class="text-[10px] font-medium mt-0.5 text-on-surface-variant">Menu</span>
    </button>
</div>

<!-- Styles for safe area inset on iOS PWA -->
<style>
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom);
        height: calc(4rem + env(safe-area-inset-bottom));
    }
</style>
