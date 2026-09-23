<x-app-layout>
    @php
        $badgeClasses = [
            'emerald' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
            'blue' => 'bg-blue-100 text-blue-800 border border-blue-300',
            'purple' => 'bg-purple-100 text-purple-800 border border-purple-300',
            'amber' => 'bg-amber-100 text-amber-800 border border-amber-300',
            'rose' => 'bg-rose-100 text-rose-800 border border-rose-300',
            'slate' => 'bg-slate-100 text-slate-800 border border-slate-300',
        ];

        $modulColorClasses = [
            'sky' => 'bg-sky-500/15 text-sky-600',
            'emerald' => 'bg-emerald-500/15 text-emerald-600',
            'blue' => 'bg-blue-500/15 text-blue-600',
            'indigo' => 'bg-indigo-500/15 text-indigo-600',
            'purple' => 'bg-purple-500/15 text-purple-600',
            'teal' => 'bg-teal-500/15 text-teal-600',
            'amber' => 'bg-amber-500/15 text-amber-600',
            'rose' => 'bg-rose-500/15 text-rose-600',
            'slate' => 'bg-slate-500/15 text-slate-700',
        ];

        $itemTypeClasses = [
            'new' => 'bg-emerald-500/15 text-emerald-800 border border-emerald-500/30',
            'improvement' => 'bg-sky-500/15 text-sky-800 border border-sky-500/30',
            'security' => 'bg-purple-500/15 text-purple-800 border border-purple-500/30',
            'fix' => 'bg-amber-500/15 text-amber-800 border border-amber-500/30',
        ];
    @endphp

    <div class="space-y-6" x-data="{
        activeTab: 'changelog',
        searchQuery: '',
        selectedCategory: 'all',
        
        matchesSearch(text) {
            if (!this.searchQuery) return true;
            return text.toLowerCase().includes(this.searchQuery.toLowerCase());
        },
        
        filterItem(type) {
            if (this.selectedCategory === 'all') return true;
            return type === this.selectedCategory;
        }
    }">
        <!-- Page Header -->
        <div class="border-b-[3px] border-primary pb-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-3xl" data-weight="fill">menu_book</span>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface font-extrabold tracking-tight">Dokumentasi & Log Pembaruan Sistem</h1>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Pusat dokumentasi internal, katalog fitur unggulan, dan riwayat pembaruan (changelog) aplikasi SiReKa khusus Administrator BKAD Kabupaten Tapin.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold font-mono bg-primary/10 text-primary border border-primary/20">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    VERSI AKTIF: {{ $systemSpecs['app_version'] }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    ADMINISTRATOR ONLY
                </span>
            </div>
        </div>

        <!-- Metric Banner & Quick Navigation -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Stat 1 -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">history</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Rilis Versi</p>
                    <p class="text-2xl font-black text-on-surface">{{ count($changelogs) }} Versi</p>
                    <p class="text-[11px] text-primary font-medium mt-0.5">Sejak v2.0 s.d {{ $changelogs[0]['version'] }}</p>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">view_module</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Modul Terintegrasi</p>
                    <p class="text-2xl font-black text-on-surface">{{ count($modulKatalog) }} Pilar Modul</p>
                    <p class="text-[11px] text-sky-600 font-medium mt-0.5">Core & Enterprise Tools</p>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">send</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Notifikasi Telegram</p>
                    <p class="text-2xl font-black text-emerald-600">v2.6.0 Baru</p>
                    <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Auto-Broadcast Posting Final</p>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">dns</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Framework Core</p>
                    <p class="text-2xl font-black text-on-surface">Laravel {{ explode('.', $systemSpecs['laravel_version'])[0] }}</p>
                    <p class="text-[11px] text-purple-600 font-medium mt-0.5">PHP {{ explode('.', $systemSpecs['php_version'])[0] . '.' . explode('.', $systemSpecs['php_version'])[1] }} Enterprise</p>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation & Search Bar -->
        <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <!-- Navigation Tabs -->
            <div class="flex items-center gap-1.5 p-1 bg-surface-container-low rounded-xl border border-outline-variant/50 self-start md:self-auto overflow-x-auto max-w-full">
                <button @click="activeTab = 'changelog'"
                    :class="activeTab === 'changelog' ? 'bg-primary text-on-primary shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">history_edu</span>
                    <span>Log Update (Changelog)</span>
                </button>
                <button @click="activeTab = 'katalog'"
                    :class="activeTab === 'katalog' ? 'bg-primary text-on-primary shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">dashboard_customize</span>
                    <span>Katalog Modul & Fitur</span>
                </button>
                <button @click="activeTab = 'arsitektur'"
                    :class="activeTab === 'arsitektur' ? 'bg-primary text-on-primary shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">terminal</span>
                    <span>Arsitektur & Spesifikasi</span>
                </button>
            </div>

            <!-- Live Search -->
            <div class="relative w-full md:w-80">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input type="text" x-model="searchQuery" placeholder="Cari fitur, modul, versi..."
                    class="w-full h-10 pl-10 pr-8 rounded-xl border border-outline-variant bg-surface text-body-sm text-on-surface placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: CHANGELOG & LOG UPDATE             -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'changelog'" x-transition class="space-y-6">
            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap items-center gap-2 pt-1">
                <span class="text-xs font-bold text-on-surface-variant mr-1">Filter Kategori:</span>
                <button @click="selectedCategory = 'all'"
                    :class="selectedCategory === 'all' ? 'bg-slate-900 text-white font-bold' : 'bg-surface border border-outline-variant text-on-surface-variant hover:text-on-surface'"
                    class="px-3 py-1 rounded-full text-xs transition-all">Semua Pembaruan</button>
                <button @click="selectedCategory = 'new'"
                    :class="selectedCategory === 'new' ? 'bg-emerald-600 text-white font-bold' : 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100'"
                    class="px-3 py-1 rounded-full text-xs transition-all flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Fitur Baru
                </button>
                <button @click="selectedCategory = 'improvement'"
                    :class="selectedCategory === 'improvement' ? 'bg-sky-600 text-white font-bold' : 'bg-sky-50 text-sky-800 border border-sky-200 hover:bg-sky-100'"
                    class="px-3 py-1 rounded-full text-xs transition-all flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Peningkatan UI/UX
                </button>
                <button @click="selectedCategory = 'security'"
                    :class="selectedCategory === 'security' ? 'bg-purple-600 text-white font-bold' : 'bg-purple-50 text-purple-800 border border-purple-200 hover:bg-purple-100'"
                    class="px-3 py-1 rounded-full text-xs transition-all flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Keamanan & Audit
                </button>
                <button @click="selectedCategory = 'fix'"
                    :class="selectedCategory === 'fix' ? 'bg-amber-600 text-white font-bold' : 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100'"
                    class="px-3 py-1 rounded-full text-xs transition-all flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Perbaikan Bug
                </button>
            </div>

            <!-- Timeline Container -->
            <div class="relative before:absolute before:inset-y-0 before:left-4 md:before:left-8 before:w-0.5 before:bg-outline-variant/60 space-y-6">
                @foreach($changelogs as $index => $log)
                <div class="relative pl-10 md:pl-20 group"
                    x-show="matchesSearch('{{ addslashes($log['version'] . ' ' . $log['title'] . ' ' . $log['summary'] . ' ' . implode(' ', array_column($log['items'], 'desc'))) }}')">
                    
                    <!-- Timeline Node Icon -->
                    <div class="absolute left-2 md:left-6 top-5 -translate-x-1/2 w-6 h-6 rounded-full flex items-center justify-center shadow-sm z-10 transition-transform group-hover:scale-110 {{ $index === 0 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-surface border-2 border-primary text-primary' }}">
                        <span class="material-symbols-outlined text-[14px]">{{ $index === 0 ? 'star' : 'commit' }}</span>
                    </div>

                    <!-- Changelog Card -->
                    <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 md:p-6 shadow-xs hover:shadow-md transition-all {{ $index === 0 ? 'ring-2 ring-emerald-500/30' : '' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/40 pb-3">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <span class="font-mono font-black text-lg md:text-xl text-on-surface tracking-tight">{{ $log['version'] }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider {{ $badgeClasses[$log['badge_color']] ?? 'bg-slate-100 text-slate-800 border border-slate-300' }}">
                                    {{ $log['badge'] }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-on-surface-variant font-medium">
                                <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                <span>{{ $log['date'] }}</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h2 class="text-base md:text-lg font-bold text-on-surface leading-snug">{{ $log['title'] }}</h2>
                            <p class="text-xs md:text-sm text-on-surface-variant mt-1 leading-relaxed">{{ $log['summary'] }}</p>
                        </div>

                        <!-- Bullet Items -->
                        <div class="mt-4 space-y-2.5">
                            @foreach($log['items'] as $item)
                            <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-surface-container-low/60 hover:bg-surface-container-low transition-colors"
                                x-show="filterItem('{{ $item['type'] }}')">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shrink-0 mt-0.5 {{ $itemTypeClasses[$item['type']] ?? 'bg-slate-500/15 text-slate-800 border border-slate-500/30' }}">
                                    {{ $item['label'] }}
                                </span>
                                <p class="text-xs md:text-sm text-on-surface leading-relaxed flex-grow">{{ $item['desc'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: KATALOG MODUL & FITUR UNGGULAN      -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'katalog'" x-transition class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($modulKatalog as $modul)
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:shadow-md hover:border-primary/40 transition-all flex flex-col justify-between"
                    x-show="matchesSearch('{{ addslashes($modul['nama'] . ' ' . $modul['deskripsi'] . ' ' . implode(' ', $modul['fitur_kunci'])) }}')">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $modulColorClasses[$modul['color']] ?? 'bg-slate-500/15 text-slate-700' }}">
                                <span class="material-symbols-outlined text-2xl" data-weight="fill">{{ $modul['icon'] }}</span>
                            </div>
                            <div class="flex flex-wrap gap-1 justify-end">
                                @foreach($modul['roles'] as $role)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-surface-container-high text-on-surface-variant border border-outline-variant">
                                    {{ $role }}
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Info -->
                        <h2 class="text-base font-extrabold text-on-surface tracking-tight">{{ $modul['nama'] }}</h2>
                        <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed line-clamp-3">{{ $modul['deskripsi'] }}</p>

                        <!-- Key Capabilities -->
                        <div class="mt-4 pt-3 border-t border-outline-variant/40 space-y-1.5">
                            <p class="text-[11px] font-bold text-on-surface uppercase tracking-wider">Kemampuan Utama:</p>
                            @foreach($modul['fitur_kunci'] as $cap)
                            <div class="flex items-start gap-1.5 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-emerald-600 text-[14px] mt-0.5 shrink-0" data-weight="fill">check_circle</span>
                                <span>{{ $cap }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer Link -->
                    <div class="mt-5 pt-3 border-t border-outline-variant/40">
                        <a href="{{ $modul['url'] }}"
                            class="w-full py-2 px-3 rounded-xl bg-surface-container hover:bg-primary hover:text-on-primary text-on-surface text-xs font-bold flex items-center justify-center gap-1.5 transition-all group">
                            <span>{{ $modul['url_label'] }}</span>
                            <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: ARSITEKTUR & SPESIFIKASI SISTEM     -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'arsitektur'" x-transition class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Server Environment Card -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">developer_board</span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-on-surface">Lingkungan Server</h2>
                            <p class="text-xs text-on-surface-variant">Konfigurasi runtime aktif</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2">
                        @foreach($systemSpecs as $key => $val)
                        <div class="flex items-center justify-between text-xs py-1.5 border-b border-outline-variant/30 last:border-0">
                            <span class="text-on-surface-variant font-medium uppercase tracking-wider text-[11px]">{{ str_replace('_', ' ', $key) }}</span>
                            <span class="font-mono font-bold text-on-surface bg-surface-container px-2 py-0.5 rounded">{{ $val }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Security & Audit Governance -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">shield</span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-on-surface">Keamanan & Audit BPK</h2>
                            <p class="text-xs text-on-surface-variant">Prinsip kepatuhan standar</p>
                        </div>
                    </div>

                    <ul class="space-y-3 pt-2 text-xs text-on-surface-variant">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                            <span><strong>Audit Trail Forensik:</strong> Seluruh modifikasi data dan penimpaan file dicatat oleh Spatie ActivityLog.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                            <span><strong>Anti-Tampering Lock:</strong> Formulir pemeriksaan terkunci otomatis saat status rekonsiliasi disahkan VALID.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                            <span><strong>Anti-Malware Upload:</strong> Validasi ekstensi ganda dan MIME-type inspection pada seluruh berkas unggahan.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                            <span><strong>Fail-Safe Architecture:</strong> Kegagalan koneksi API pihak ketiga (seperti Telegram) tidak akan memutus transaksi SKPD.</span>
                        </li>
                    </ul>
                </div>

                <!-- Maintenance Guide -->
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">build</span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-on-surface">Prosedur Pembaruan</h2>
                            <p class="text-xs text-on-surface-variant">SOP deploy di server hosting/VPS</p>
                        </div>
                    </div>

                    <div class="space-y-2 pt-1 font-mono text-[11px] bg-slate-900 text-slate-200 p-4 rounded-xl overflow-x-auto">
                        <p class="text-slate-400"># 1. Aktifkan Mode Lockdown di Web</p>
                        <p class="text-emerald-400"># 2. Jalankan perintah di Terminal:</p>
                        <p>git pull origin main</p>
                        <p>composer install --no-dev</p>
                        <p>php artisan migrate --force</p>
                        <p>php artisan optimize:clear</p>
                        <p class="text-emerald-400"># 3. Nonaktifkan Lockdown</p>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('pengaturan.maintenance.index') }}"
                            class="w-full py-2 px-3 rounded-xl bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-700 text-xs font-bold flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                            <span>Buka Layar Maintenance Sistem</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
