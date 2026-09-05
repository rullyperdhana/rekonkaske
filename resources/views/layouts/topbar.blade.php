<header id="appTopbar" class="bg-surface-container-lowest dark:bg-surface-dim docked full-width top-0 border-b-2 border-primary dark:border-primary-container flat no shadows flex justify-between items-center h-16 px-4 lg:px-8 lg:ml-72 w-full lg:w-[calc(100%-18rem)] fixed z-30 transition-all duration-300">
    <div class="flex items-center gap-4 lg:gap-8">
        <button onclick="toggleSidebar()" class="text-on-surface-variant hover:text-primary p-1 rounded-full hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span class="text-headline-sm font-headline-sm font-black text-primary dark:text-primary-fixed hidden sm:inline">Sistem Rekonsiliasi Kas</span>
        <span class="text-headline-sm font-headline-sm font-black text-primary dark:text-primary-fixed sm:hidden">SIPANDA</span>
    </div>
    <div class="flex items-center gap-4">
        @if(session()->has('tahun_login'))
        <div class="px-3 py-1.5 bg-secondary-container text-on-secondary-container rounded-full text-label-sm font-label-sm font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">calendar_month</span>
            TA. {{ session('tahun_login') }}
        </div>
        @endif

        <button class="text-on-surface-variant hover:text-primary p-1 rounded-full hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="relative group">
            <button class="flex items-center gap-3 text-on-surface-variant hover:text-primary p-1 pr-3 rounded-full hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">account_circle</span>
                <div class="flex flex-col items-start hidden sm:flex">
                    <span class="text-sm font-semibold leading-tight">{{ Auth::user()->name ?? 'User' }}</span>
                    <span class="text-[11px] text-primary font-bold leading-tight uppercase">{{ Auth::user()->role === 'operator' ? 'Operator: ' . (Auth::user()->skpd->nama ?? 'SKPD') : Auth::user()->role }}</span>
                </div>
            </button>
            <div class="absolute right-0 top-full mt-2 w-72 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                <div class="p-4 bg-surface-container-low/50 border-b border-outline-variant/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-sm border border-primary/20">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-on-surface truncate">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-on-surface-variant truncate font-mono">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary">
                                {{ Auth::user()->role === 'operator' ? (Auth::user()->skpd->nama ?? 'Operator SKPD') : Auth::user()->role }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="py-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant">account_circle</span>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('password.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant">key</span>
                        <span>Ubah Password</span>
                    </a>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('user.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant">manage_accounts</span>
                        <span>Pengaturan Pengguna</span>
                    </a>
                    @endif
                </div>

                <div class="border-t border-outline-variant/60 p-2 bg-surface-container-low/30">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-error hover:bg-error/10 rounded-lg transition-colors font-medium">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            <span>Keluar Sistem</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
