<nav id="appSidebar" class="fixed top-0 left-0 h-screen flex flex-col py-6 bg-primary dark:bg-primary-container docked full-height w-64 border-r border-outline-variant dark:border-outline shadow-md dark:shadow-none z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    @php
        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
        $logoApp = ($pengaturanGlobal && $pengaturanGlobal->logo) 
            ? (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http') ? $pengaturanGlobal->logo : asset('storage/' . $pengaturanGlobal->logo)) 
            : null;
    @endphp
    <div class="px-6 mb-8 flex items-center gap-4">
        @if($logoApp)
            <img src="{{ $logoApp }}" alt="Logo" class="w-10 h-10 object-contain rounded bg-white p-1">
        @else
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-primary" data-weight="fill">account_balance</span>
            </div>
        @endif
        <div>
            <h1 class="text-headline-md font-headline-md font-bold text-on-primary dark:text-primary-fixed">BKAD</h1>
            <p class="text-label-sm font-label-sm text-on-primary/80">Kabupaten Tapin</p>
        </div>
    </div>
    <div class="px-4 mb-6">
        <a href="{{ route('transaksi.create') }}" class="w-full bg-secondary-container text-on-secondary-container hover:bg-secondary-container/90 py-3 rounded-lg text-label-sm font-label-sm flex items-center justify-center gap-2 shadow-sm transition-transform scale-95 active:scale-90">
            <span class="material-symbols-outlined" data-weight="fill">add_circle</span>
            Rekonsiliasi Baru
        </a>
    </div>
    <ul class="flex-1 space-y-2">
        <li>
            <a class="bg-secondary-container text-on-secondary-container rounded-lg mx-2 flex items-center gap-3 px-4 py-3 scale-95 active:scale-90 transition-transform" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined" data-weight="fill">dashboard</span>
                <span class="text-label-sm font-label-sm">Dashboard</span>
            </a>
        </li>
        <li class="group">
            <button class="w-full text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg mx-2 flex items-center justify-between px-4 py-3 hover:bg-primary-container transition-colors duration-200 scale-95 active:scale-90 transition-transform" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">database</span>
                    <span class="text-label-sm font-label-sm">Master Data</span>
                </div>
                <span class="material-symbols-outlined text-sm arrow transition-transform duration-200">expand_more</span>
            </button>
            <ul class="hidden space-y-1 mt-1 ml-8 mr-4">
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('skpd.index') }}">
                        <span class="text-label-sm font-label-sm">Master SKPD</span>
                    </a>
                </li>
                @endif
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('rekening.index') }}">
                        <span class="text-label-sm font-label-sm">Master Rekening</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('tahun.index') }}">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">calendar_month</span>
                        <span class="text-label-sm font-label-sm">Tahun Anggaran</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        <li>
            <a class="text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg mx-2 flex items-center gap-3 px-4 py-3 hover:bg-primary-container transition-colors duration-200 scale-95 active:scale-90 transition-transform" href="{{ route('transaksi.index') }}">
                <span class="material-symbols-outlined">swap_horiz</span>
                <span class="text-label-sm font-label-sm">Transaksi</span>
            </a>
        </li>
        <li class="group">
            <button class="w-full text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg mx-2 flex items-center justify-between px-4 py-3 hover:bg-primary-container transition-colors duration-200 scale-95 active:scale-90 transition-transform" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">assessment</span>
                    <span class="text-label-sm font-label-sm">Laporan</span>
                </div>
                <span class="material-symbols-outlined text-sm arrow transition-transform duration-200">expand_more</span>
            </button>
            <ul class="hidden space-y-1 mt-1 ml-8 mr-4">
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('ba.index') }}">
                        <span class="text-label-sm font-label-sm">Berita Acara (Bulanan)</span>
                    </a>
                </li>
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('laporan.rekap') }}">
                        <span class="text-label-sm font-label-sm">Rekapitulasi Tahunan</span>
                    </a>
                </li>
                @if(in_array(auth()->user()->role, ['admin', 'konsolidator']))
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('laporan.konsolidasi') }}">
                        <span class="text-label-sm font-label-sm">Konsolidasi Daerah</span>
                    </a>
                </li>
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('laporan.tunggakan') }}">
                        <span class="text-label-sm font-label-sm">Tunggakan & Selisih</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        <li class="group">
            <button class="w-full text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg mx-2 flex items-center justify-between px-4 py-3 hover:bg-primary-container transition-colors duration-200 scale-95 active:scale-90 transition-transform" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-label-sm font-label-sm">Pengaturan</span>
                </div>
                <span class="material-symbols-outlined text-sm arrow transition-transform duration-200">expand_more</span>
            </button>
            <ul class="hidden space-y-1 mt-1 ml-8 mr-4">
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('user.index') }}">
                        <span class="text-label-sm font-label-sm">Pengaturan Pengguna</span>
                    </a>
                </li>
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('pengaturan.maintenance.index') }}">
                        <span class="text-label-sm font-label-sm text-error/90 hover:text-error">Maintenance Sistem</span>
                    </a>
                </li>
                @endif
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('pengaturan.instansi.edit') }}">
                        <span class="text-label-sm font-label-sm">Pengaturan Instansi (Kop)</span>
                    </a>
                </li>
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('password.edit') }}">
                        <span class="text-label-sm font-label-sm">Ubah Password</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('log.index') }}">
                        <span class="text-label-sm font-label-sm">Jejak Audit</span>
                    </a>
                </li>
                <li>
                    <a class="text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 rounded-lg flex items-center gap-3 px-4 py-2 transition-colors duration-200" href="{{ route('pengumuman.index') }}">
                        <span class="text-label-sm font-label-sm">Pengumuman</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
    </ul>
    <div class="mt-auto px-4 space-y-2">
        <a class="text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg mx-2 flex items-center gap-3 px-4 py-3 hover:bg-primary-container transition-colors duration-200 scale-95 active:scale-90 transition-transform" href="#">
            <span class="material-symbols-outlined">help</span>
            <span class="text-label-sm font-label-sm">Bantuan</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full text-on-primary/80 hover:text-on-primary hover:bg-primary-container/50 rounded-lg mx-2 flex items-center gap-3 px-4 py-3 hover:bg-primary-container transition-colors duration-200 scale-95 active:scale-90 transition-transform">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-label-sm font-label-sm">Logout</span>
            </button>
        </form>
    </div>
</nav>
