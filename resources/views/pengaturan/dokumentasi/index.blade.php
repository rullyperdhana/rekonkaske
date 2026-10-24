<x-app-layout>
    @php
        $badgeClasses = [
            'emerald' => 'bg-emerald-100 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-700/50',
            'blue' => 'bg-blue-100 text-blue-800 border border-blue-300 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-700/50',
            'purple' => 'bg-purple-100 text-purple-800 border border-purple-300 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-700/50',
            'indigo' => 'bg-indigo-100 text-indigo-800 border border-indigo-300 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-700/50',
            'amber' => 'bg-amber-100 text-amber-800 border border-amber-300 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-700/50',
            'rose' => 'bg-rose-100 text-rose-800 border border-rose-300 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-700/50',
            'slate' => 'bg-slate-100 text-slate-800 border border-slate-300 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
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
            'new' => 'bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-emerald-500/30',
            'improvement' => 'bg-sky-500/15 text-sky-800 dark:text-sky-300 border border-sky-500/30',
            'security' => 'bg-purple-500/15 text-purple-800 dark:text-purple-300 border border-purple-500/30',
            'fix' => 'bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-500/30',
        ];
    @endphp

    <div class="space-y-6" x-data="{
        activeTab: 'sop_vps',
        searchQuery: '',
        selectedCategory: 'all',
        copiedTarget: null,
        checkedPreflight: [],
        checkedPostflight: [],
        scanningSecurity: false,
        securityAuditResult: null,

        copyCommand(text, targetId) {
            navigator.clipboard.writeText(text).then(() => {
                this.copiedTarget = targetId;
                setTimeout(() => {
                    if (this.copiedTarget === targetId) {
                        this.copiedTarget = null;
                    }
                }, 2500);
            }).catch(err => {
                console.error('Gagal menyalin:', err);
            });
        },

        matchesSearch(text) {
            if (!this.searchQuery) return true;
            return text.toLowerCase().includes(this.searchQuery.toLowerCase());
        },

        filterItem(type) {
            if (this.selectedCategory === 'all') return true;
            return type === this.selectedCategory;
        },

        runSecurityScan() {
            this.scanningSecurity = true;
            fetch('{{ route('pengaturan.dokumentasi.audit') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.securityAuditResult = data;
            })
            .catch(err => {
                console.error('Audit scan error:', err);
                this.securityAuditResult = {
                    success: false,
                    status: 'unavailable',
                    message: 'Gagal menghubungi server untuk menjalankan audit live scan otomatis. Periksa koneksi jaringan atau silakan verifikasi manual via Terminal VPS menggunakan perintah composer audit.',
                    cli_command: '{{ $laravelInfo['composer_audit_cmd'] }}'
                };
            })
            .finally(() => {
                this.scanningSecurity = false;
            });
        }
    }">
        <!-- Page Header -->
        <div class="border-b-[3px] border-primary pb-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl" data-weight="fill">system_update_alt</span>
                    </div>
                    <div>
                        <h1 class="font-headline-lg text-2xl md:text-3xl text-on-surface font-black tracking-tight">Pusat Informasi & Log Update Aplikasi</h1>
                        <p class="font-body-md text-xs md:text-sm text-on-surface-variant mt-0.5">
                            Portal dokumentasi terpadu pembaruan sistem SiReKa, SOP deployment VPS aaPanel, matriks hak akses, dan log rilis resmi untuk Administrator BKAD Kabupaten Tapin.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <button @click="activeTab = 'keamanan'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black font-mono bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/20 transition-all cursor-pointer shadow-2xs group"
                    title="Buka Radar Keamanan & Versi Laravel">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span class="material-symbols-outlined text-[15px] text-rose-600">shield_with_heart</span>
                    <span>Laravel v{{ $laravelInfo['version'] }}</span>
                </button>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black font-mono bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $systemSpecs['app_version'] }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-primary text-on-primary shadow-xs">
                    <span class="material-symbols-outlined text-[15px]">admin_panel_settings</span>
                    SUPER ADMIN
                </span>
            </div>
        </div>

        <!-- Metric Banner & Quick Deployment Status (5 Pillars) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <!-- Stat 1: Rilis Versi SiReKa -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">verified</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Rilis Versi SiReKa</p>
                    <p class="text-xl font-black text-on-surface truncate">{{ $systemSpecs['app_version'] }}</p>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">{{ $systemSpecs['release_date'] }}</p>
                </div>
            </div>

            <!-- Stat 2: Versi Laravel Engine & Radar Keamanan -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4 cursor-pointer hover:border-rose-500/50 hover:shadow-md transition-all group"
                @click="activeTab = 'keamanan'" title="Klik untuk membuka tab Versi Laravel & Radar Keamanan">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 group-hover:bg-rose-500/20 flex items-center justify-center shrink-0 transition-colors">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">shield_with_heart</span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Laravel Engine</p>
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300">Aktif</span>
                    </div>
                    <p class="text-xl font-black text-on-surface truncate">v{{ $laravelInfo['version'] }}</p>
                    <p class="text-[11px] text-rose-600 font-semibold truncate mt-0.5 flex items-center gap-1">
                        <span>PHP {{ $laravelInfo['php_runtime'] }}</span>
                        <span>&middot; Radar Keamanan</span>
                    </p>
                </div>
            </div>

            <!-- Stat 3: Git Repository Sync -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">source_environment</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Git Commit Aktif</p>
                    <div class="flex items-center gap-1.5">
                        <span class="font-mono text-sm font-black text-purple-700 dark:text-purple-300 bg-purple-500/10 px-1.5 py-0.5 rounded">{{ $gitInfo['commit'] }}</span>
                        <span class="text-xs font-semibold text-on-surface-variant">({{ $gitInfo['branch'] }})</span>
                    </div>
                    <p class="text-[11px] text-on-surface-variant truncate mt-0.5" title="{{ $gitInfo['message'] }}">{{ $gitInfo['message'] }}</p>
                </div>
            </div>

            <!-- Stat 4: Jalur Server VPS -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">dns</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Server Root (aaPanel)</p>
                    <p class="text-xs font-mono font-bold text-on-surface truncate">{{ $vpsDeploymentInfo['vps_path'] }}</p>
                    <p class="text-[11px] text-amber-600 font-semibold mt-0.5">PHP 8.4 CLI &middot; www:www</p>
                </div>
            </div>

            <!-- Stat 5: Mode Sistem -->
            <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-weight="fill">health_and_safety</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status Produksi</p>
                    <p class="text-sm font-black text-sky-700 dark:text-sky-300 truncate">{{ $systemSpecs['maintenance_mode'] }}</p>
                    <a href="{{ route('pengaturan.maintenance.index') }}" class="text-[11px] text-primary hover:underline font-bold inline-flex items-center gap-0.5 mt-0.5">
                        <span>Kelola Maintenance</span>
                        <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation & Search Bar -->
        <div class="bg-surface p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <!-- Navigation Tabs -->
            <div class="flex items-center gap-1.5 p-1 bg-surface-container-low rounded-xl border border-outline-variant/50 self-start md:self-auto overflow-x-auto max-w-full">
                <!-- Tab SOP VPS -->
                <button @click="activeTab = 'sop_vps'"
                    :class="activeTab === 'sop_vps' ? 'bg-primary text-on-primary shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">terminal</span>
                    <span>Panduan Update VPS (SOP)</span>
                </button>

                <!-- Tab Radar Keamanan & Versi Laravel -->
                <button @click="activeTab = 'keamanan'"
                    :class="activeTab === 'keamanan' ? 'bg-rose-600 text-white shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">shield_with_heart</span>
                    <span>Versi Laravel & Radar Keamanan</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-white/20 text-white">v{{ $laravelInfo['version'] }}</span>
                </button>

                <!-- Tab Changelog -->
                <button @click="activeTab = 'changelog'"
                    :class="activeTab === 'changelog' ? 'bg-primary text-on-primary shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">history_edu</span>
                    <span>Log Update ({{ count($changelogs) }} Versi)</span>
                </button>

                <!-- Tab Hak Akses Peran -->
                <button @click="activeTab = 'hak_akses'"
                    :class="activeTab === 'hak_akses' ? 'bg-primary text-on-primary shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">badge</span>
                    <span>Matriks Hak Akses Peran</span>
                </button>

                <!-- Tab Katalog Modul & Spesifikasi -->
                <button @click="activeTab = 'katalog'"
                    :class="activeTab === 'katalog' ? 'bg-primary text-on-primary shadow-xs font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">dashboard_customize</span>
                    <span>Katalog Modul & Arsitektur</span>
                </button>
            </div>

            <!-- Live Search -->
            <div class="relative w-full md:w-80">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input type="text" x-model="searchQuery" placeholder="Cari fitur, perintah, versi, modul..."
                    class="w-full h-10 pl-10 pr-8 rounded-xl border border-outline-variant bg-surface text-body-sm text-on-surface placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- TAB 1: PANDUAN UPDATE SERVER VPS (SOP DEPLOYMENT PRODUCTION) -->
        <!-- ========================================================== -->
        <div x-show="activeTab === 'sop_vps'" x-transition class="space-y-6">
            <!-- 1. Highlight Box: Jalur Eksekusi Cepat (pull.sh) -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white p-6 md:p-8 shadow-xl border border-slate-800">
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                METODA UTAMA TERCEPAT (1-KLIK CLI)
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-500/20 text-sky-400 border border-sky-500/30">
                                aaPanel VPS Production
                            </span>
                        </div>
                        <h2 class="text-xl md:text-2xl font-black tracking-tight text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-400 text-2xl">bolt</span>
                            Jalur Singkat Eksekusi Script Pembaruan Otomatis
                        </h2>
                        <p class="text-xs md:text-sm text-slate-300 leading-relaxed">
                            Cukup salin dan tempel (paste) satu baris perintah di bawah ini ke dalam Terminal SSH Server Anda. Script <code class="bg-white/10 px-1.5 py-0.5 rounded text-amber-300 font-mono text-xs">pull.sh</code> akan secara otomatis menyalakan mode maintenance, menarik kode terbaru dari GitHub, memperbarui database, mengoptimasi cache, dan memulihkan hak akses server dalam waktu ~15 detik.
                        </p>
                    </div>

                    <div class="w-full lg:w-auto shrink-0 flex flex-col gap-2">
                        <button @click="copyCommand('{{ $vpsDeploymentInfo['quick_cmd'] }}', 'quick_cmd')"
                            class="w-full lg:w-auto px-6 py-3.5 rounded-2xl bg-primary hover:bg-primary/90 text-on-primary font-black text-sm flex items-center justify-center gap-2.5 shadow-lg shadow-primary/30 transition-all active:scale-95 group">
                            <span class="material-symbols-outlined text-lg" x-text="copiedTarget === 'quick_cmd' ? 'done' : 'content_copy'"></span>
                            <span x-text="copiedTarget === 'quick_cmd' ? 'Perintah Berhasil Disalin!' : 'Salin Perintah Cepat'"></span>
                        </button>
                        <p class="text-[11px] text-center text-slate-400">Direktori: {{ $vpsDeploymentInfo['vps_path'] }}</p>
                    </div>
                </div>

                <!-- Command Code Banner -->
                <div class="mt-6 pt-5 border-t border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-black/40 rounded-2xl p-4 font-mono text-xs md:text-sm border border-white/5">
                    <div class="flex items-center gap-3 overflow-x-auto">
                        <span class="text-emerald-400 select-none font-bold">root@sireke:~#</span>
                        <span class="text-amber-300 font-bold tracking-wide">{{ $vpsDeploymentInfo['quick_cmd'] }}</span>
                    </div>
                    <button @click="copyCommand('{{ $vpsDeploymentInfo['quick_cmd'] }}', 'quick_cmd_btn')"
                        class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 text-xs font-semibold flex items-center justify-center gap-1.5 transition-all shrink-0">
                        <span class="material-symbols-outlined text-[15px]" x-text="copiedTarget === 'quick_cmd_btn' ? 'check' : 'content_copy'"></span>
                        <span x-text="copiedTarget === 'quick_cmd_btn' ? 'Tersalin' : 'Copy'"></span>
                    </button>
                </div>
            </div>

            <!-- 2. Prosedur Manual 8 Langkah & Penjelasan Alur -->
            <div class="bg-surface rounded-3xl border border-outline-variant/70 p-6 md:p-8 shadow-xs space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-outline-variant/40 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-2xl" data-weight="fill">menu_book</span>
                            <h2 class="text-lg md:text-xl font-black text-on-surface">Prosedur Manual Bertahap (Standard Operating Procedure)</h2>
                        </div>
                        <p class="text-xs md:text-sm text-on-surface-variant mt-1">
                            Gunakan prosedur bertahap ini apabila Anda ingin memantau output konsol satu per satu atau jika terjadi kendala pada script otomatis.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="copyCommand('{{ collect($vpsDeploymentInfo['steps'])->pluck('cmd')->implode("\n") }}', 'all_manual_cmds')"
                            class="px-4 py-2 rounded-xl bg-surface-container-high hover:bg-primary hover:text-on-primary text-on-surface text-xs font-bold flex items-center gap-1.5 transition-all">
                            <span class="material-symbols-outlined text-[16px]" x-text="copiedTarget === 'all_manual_cmds' ? 'done' : 'copy_all'"></span>
                            <span x-text="copiedTarget === 'all_manual_cmds' ? 'Semua Perintah Tersalin!' : 'Salin Semua 8 Langkah'"></span>
                        </button>
                    </div>
                </div>

                <!-- 8 Steps Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($vpsDeploymentInfo['steps'] as $step)
                    <div class="bg-surface-container-low/60 hover:bg-surface-container-low border border-outline-variant/60 rounded-2xl p-4.5 transition-all flex flex-col justify-between group">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-primary text-on-primary font-black text-xs flex items-center justify-center shrink-0">
                                        {{ $step['num'] }}
                                    </span>
                                    <h3 class="text-sm font-black text-on-surface">{{ $step['title'] }}</h3>
                                </div>
                                <button @click="copyCommand('{{ $step['cmd'] }}', 'step_{{ $step['num'] }}')"
                                    class="p-1.5 rounded-lg bg-surface border border-outline-variant/50 hover:bg-primary hover:text-on-primary text-on-surface-variant transition-colors"
                                    title="Salin Perintah">
                                    <span class="material-symbols-outlined text-[16px]" x-text="copiedTarget === 'step_{{ $step['num'] }}' ? 'check' : 'content_copy'"></span>
                                </button>
                            </div>
                            <p class="text-xs text-on-surface-variant leading-relaxed">{{ $step['desc'] }}</p>
                        </div>

                        <!-- Command Box -->
                        <div class="mt-3 bg-slate-950 text-slate-200 p-2.5 rounded-xl font-mono text-[11px] border border-slate-800 flex items-center justify-between gap-2 overflow-x-auto">
                            <span class="text-emerald-400 shrink-0">$</span>
                            <code class="text-slate-200 flex-grow whitespace-nowrap">{{ $step['cmd'] }}</code>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Checklist Pra & Pasca Update Interaktif & Rollback SOP -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Checklist Pra & Pasca Deployment -->
                <div class="lg:col-span-2 bg-surface rounded-3xl border border-outline-variant/70 p-6 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant/40 pb-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">checklist</span>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-on-surface">Checklist Administrator Sebelum & Sesudah Update</h3>
                            <p class="text-xs text-on-surface-variant">Panduan operasional mitigasi risiko downtime di lingkungan instansi</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Pra Update -->
                        <div class="space-y-3 bg-surface-container-low/40 p-4 rounded-2xl border border-outline-variant/50">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-600 text-lg">flag</span>
                                <h4 class="text-xs font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Pra-Update (Sebelum Eksekusi)</h4>
                            </div>
                            <div class="space-y-2">
                                @foreach($vpsDeploymentInfo['preflight_checklist'] as $idx => $item)
                                <label class="flex items-start gap-2.5 text-xs text-on-surface cursor-pointer select-none">
                                    <input type="checkbox" x-model="checkedPreflight" value="{{ $idx }}"
                                        class="rounded border-outline-variant text-primary focus:ring-primary mt-0.5">
                                    <span :class="checkedPreflight.includes({{ $idx }}) ? 'line-through text-on-surface-variant/60 font-medium' : 'text-on-surface font-semibold'">
                                        {{ $item }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pasca Update -->
                        <div class="space-y-3 bg-surface-container-low/40 p-4 rounded-2xl border border-outline-variant/50">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-lg">verified</span>
                                <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Pasca-Update (Verifikasi Akhir)</h4>
                            </div>
                            <div class="space-y-2">
                                @foreach($vpsDeploymentInfo['postflight_checklist'] as $idx => $item)
                                <label class="flex items-start gap-2.5 text-xs text-on-surface cursor-pointer select-none">
                                    <input type="checkbox" x-model="checkedPostflight" value="{{ $idx }}"
                                        class="rounded border-outline-variant text-primary focus:ring-primary mt-0.5">
                                    <span :class="checkedPostflight.includes({{ $idx }}) ? 'line-through text-on-surface-variant/60 font-medium' : 'text-on-surface font-semibold'">
                                        {{ $item }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rollback & Emergency Toolkit -->
                <div class="bg-surface rounded-3xl border border-outline-variant/70 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3 border-b border-outline-variant/40 pb-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">emergency_home</span>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-on-surface">Prosedur Darurat & Rollback</h3>
                            <p class="text-xs text-on-surface-variant">Jika terjadi error pasca rilis</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($vpsDeploymentInfo['rollback_cmds'] as $name => $cmd)
                        <div class="p-3 rounded-2xl bg-slate-950 text-slate-200 border border-slate-800 space-y-1.5">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-slate-300">{{ $name }}</span>
                                <button @click="copyCommand('{{ $cmd }}', 'rollback_{{ $loop->index }}')"
                                    class="text-amber-400 hover:text-amber-300 transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]" x-text="copiedTarget === 'rollback_{{ $loop->index }}' ? 'check' : 'content_copy'"></span>
                                    <span x-text="copiedTarget === 'rollback_{{ $loop->index }}' ? 'Tersalin' : 'Copy'"></span>
                                </button>
                            </div>
                            <div class="font-mono text-[10px] text-emerald-400 bg-black/50 p-2 rounded-lg overflow-x-auto whitespace-nowrap">
                                {{ $cmd }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- TAB 2: VERSI LARAVEL & RADAR KEAMANAN SISTEM (CVE DEFENSE) -->
        <!-- ========================================================== -->
        <div x-show="activeTab === 'keamanan'" x-transition class="space-y-6">
            <!-- 1. Hero Banner: Laravel Version & Live CVE Scanner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-rose-950 text-white p-6 md:p-8 shadow-xl border border-slate-800">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-rose-600/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                    <div class="space-y-2.5 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                RADAR KEAMANAN RESMI &middot; CVE DEFENSE
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                {{ $laravelInfo['status'] }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-500/20 text-sky-400 border border-sky-500/30">
                                aaPanel PHP 8.4
                            </span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                            <span class="material-symbols-outlined text-rose-500 text-3xl">shield_with_heart</span>
                            Laravel Framework Engine v{{ $laravelInfo['version'] }}
                        </h2>
                        <p class="text-xs md:text-sm text-slate-300 leading-relaxed">
                            Sistem SiReKa ditenagai oleh <strong>Laravel v{{ $laravelInfo['version'] }}</strong> dengan perlindungan dependensi terenkripsi. Gunakan radar keamanan ini untuk mendeteksi potensi celah kerentanan (CVE), memeriksa kesesuaian platform server, serta mengikuti prosedur cepat tanggap penambalan (security patching).
                        </p>
                    </div>

                    <div class="w-full lg:w-auto shrink-0 flex flex-col gap-2.5">
                        <button @click="runSecurityScan()" :disabled="scanningSecurity"
                            class="w-full lg:w-auto px-6 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-black text-sm flex items-center justify-center gap-2.5 shadow-lg shadow-rose-600/30 transition-all active:scale-95 disabled:opacity-60 cursor-pointer group">
                            <span class="material-symbols-outlined text-xl" :class="scanningSecurity ? 'animate-spin' : 'group-hover:rotate-45 transition-transform'"
                                x-text="scanningSecurity ? 'progress_activity' : 'verified_user'"></span>
                            <span x-text="scanningSecurity ? 'Memindai Database CVE...' : 'Jalankan Audit Keamanan (Live Scan)'"></span>
                        </button>
                        <p class="text-[11px] text-center text-slate-400">Database: GitHub Advisories &middot; Packagist CVE</p>
                    </div>
                </div>

                <!-- Live Audit Result Alert (Dynamic) -->
                <div x-show="securityAuditResult" x-transition class="mt-6 pt-5 border-t border-slate-800">
                    <div class="p-4 rounded-2xl border transition-all"
                        :class="{
                            'bg-rose-500/15 border-rose-500/40 text-rose-200': securityAuditResult?.status === 'vulnerable',
                            'bg-emerald-500/15 border-emerald-500/40 text-emerald-200': securityAuditResult?.status === 'clean',
                            'bg-amber-500/15 border-amber-500/40 text-amber-200': securityAuditResult?.status !== 'vulnerable' && securityAuditResult?.status !== 'clean'
                        }">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-2xl mt-0.5 shrink-0"
                                    :class="{
                                        'text-rose-400': securityAuditResult?.status === 'vulnerable',
                                        'text-emerald-400': securityAuditResult?.status === 'clean',
                                        'text-amber-400': securityAuditResult?.status !== 'vulnerable' && securityAuditResult?.status !== 'clean'
                                    }"
                                    x-text="securityAuditResult?.status === 'vulnerable' ? 'gpp_bad' : (securityAuditResult?.status === 'clean' ? 'verified_user' : 'warning')"></span>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-black tracking-wide uppercase"
                                            :class="{
                                                'text-rose-300': securityAuditResult?.status === 'vulnerable',
                                                'text-emerald-300': securityAuditResult?.status === 'clean',
                                                'text-amber-300': securityAuditResult?.status !== 'vulnerable' && securityAuditResult?.status !== 'clean'
                                            }"
                                            x-text="securityAuditResult?.status === 'vulnerable' ? 'PERINGATAN: Ditemukan Celah Keamanan!' : (securityAuditResult?.status === 'clean' ? 'STATUS: SISTEM TERPROTEKSI (0 Kerentanan)' : 'SCAN TIDAK TERSEDIA — Verifikasi Manual Diperlukan')"></p>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-black/40 text-slate-200"
                                            x-text="'Scan: ' + (securityAuditResult?.scanned_at || 'WITA')"></span>
                                    </div>
                                    <p class="text-xs text-slate-300 leading-relaxed" x-text="securityAuditResult?.message"></p>
                                </div>
                            </div>
                            <button @click="securityAuditResult = null" class="text-slate-400 hover:text-white p-1">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CLI Audit Command Banner -->
                <div class="mt-6 pt-5 border-t border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-black/40 rounded-2xl p-4 font-mono text-xs md:text-sm border border-white/5">
                    <div class="flex items-center gap-3 overflow-x-auto">
                        <span class="text-rose-400 select-none font-bold">root@sireke:~#</span>
                        <span class="text-amber-300 font-bold tracking-wide">{{ $laravelInfo['composer_audit_cmd'] }}</span>
                    </div>
                    <button @click="copyCommand('{{ $laravelInfo['composer_audit_cmd'] }}', 'composer_audit_cmd')"
                        class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 text-xs font-semibold flex items-center justify-center gap-1.5 transition-all shrink-0">
                        <span class="material-symbols-outlined text-[15px]" x-text="copiedTarget === 'composer_audit_cmd' ? 'check' : 'content_copy'"></span>
                        <span x-text="copiedTarget === 'composer_audit_cmd' ? 'Tersalin' : 'Copy CLI Scan'"></span>
                    </button>
                </div>
            </div>

            <!-- 2. Arsitektur 4 Pilar Keamanan Framework -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Pilar 1: Versi Framework Aktif -->
                <div class="bg-surface rounded-3xl border border-outline-variant/70 p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">developer_board</span>
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/20 font-mono">
                            {{ $laravelInfo['series'] }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Versi Framework</p>
                        <p class="text-xl font-black text-on-surface">Laravel v{{ $laravelInfo['version'] }}</p>
                        <p class="text-xs text-on-surface-variant mt-1">Syarat PHP: <code class="font-mono text-primary font-bold">{{ $laravelInfo['php_min_req'] }}</code></p>
                    </div>
                </div>

                <!-- Pilar 2: Lingkungan PHP Server -->
                <div class="bg-surface rounded-3xl border border-outline-variant/70 p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">terminal</span>
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/10 text-sky-700 dark:text-sky-300 border border-sky-500/20 font-mono">
                            PHP 8.4 aaPanel
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Runtime PHP VPS</p>
                        <p class="text-xl font-black text-on-surface">v{{ $laravelInfo['php_runtime'] }}</p>
                        <p class="text-xs text-emerald-600 font-semibold mt-1">Jalur: <code class="font-mono text-[11px]">{{ $laravelInfo['vps_php_bin'] }}</code></p>
                    </div>
                </div>

                <!-- Pilar 3: Siklus Pemeliharaan & Patching -->
                <div class="bg-surface rounded-3xl border border-outline-variant/70 p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">verified</span>
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20">
                            STABLE
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status Pemeliharaan</p>
                        <p class="text-base font-black text-emerald-700 dark:text-emerald-300">Active Support</p>
                        <p class="text-xs text-on-surface-variant mt-1">Menerima patch bug & security updates berkala</p>
                    </div>
                </div>

                <!-- Pilar 4: Alat Audit Otomatis -->
                <div class="bg-surface rounded-3xl border border-outline-variant/70 p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl" data-weight="fill">policy</span>
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/10 text-purple-700 dark:text-purple-300 border border-purple-500/20">
                            COMPOSER 2
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Deteksi Kerentanan</p>
                        <p class="text-base font-black text-on-surface">Composer Audit</p>
                        <p class="text-xs text-on-surface-variant mt-1">Sinkronisasi database CVE & GitHub Security</p>
                    </div>
                </div>
            </div>

            <!-- 3. Kotak Solusi Error Terminal SSH VPS (Penyebab & Solusi CLI PHP 8.3 vs 8.4) -->
            <div class="bg-amber-500/10 border-2 border-amber-500/30 rounded-3xl p-6 md:p-8 space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl">build_circle</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-on-surface flex items-center gap-2">
                            <span>Solusi Khusus: Mengatasi Error <code>php artisan --version</code> di Terminal VPS aaPanel</span>
                        </h3>
                        <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed">
                            Jika Anda mengetik <code class="bg-surface-container px-1.5 py-0.5 rounded font-mono text-rose-600">php artisan --version</code> di terminal SSH server dan muncul pesan:
                            <em class="font-mono text-xs block my-1.5 bg-black/70 text-amber-300 p-2 rounded-xl">"Composer detected issues in your platform: Your Composer dependencies require a PHP version '>= 8.4.1'. You are running 8.3.6."</em>
                            Hal itu terjadi karena sistem terminal Linux secara bawaan masih memanggil <strong>PHP 8.3.6</strong> milik OS, bukan <strong>PHP 8.4</strong> milik aaPanel tempat SiReKa berjalan.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <!-- Solusi 1: Panggil Jalur Penuh PHP 8.4 -->
                    <div class="bg-surface rounded-2xl p-4 border border-outline-variant/60 shadow-xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-on-surface flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-600 text-xs flex items-center justify-center font-bold">1</span>
                                <span>Cara Cepat (Panggil Binary PHP 8.4)</span>
                            </span>
                            <span class="text-[10px] uppercase font-bold text-emerald-600 bg-emerald-500/10 px-2 py-0.5 rounded">Instan</span>
                        </div>
                        <p class="text-xs text-on-surface-variant">Jalankan perintah dengan menyebutkan jalur lengkap PHP 8.4 aaPanel:</p>
                        <div class="flex items-center justify-between gap-2 bg-surface-container-highest p-2.5 rounded-xl font-mono text-xs">
                            <span class="text-on-surface truncate">{{ $laravelInfo['vps_cli_check_cmd'] }}</span>
                            <button @click="copyCommand('{{ $laravelInfo['vps_cli_check_cmd'] }}', 'cli_check')"
                                class="px-2 py-1 rounded bg-primary text-on-primary text-[11px] font-bold shrink-0">
                                <span x-text="copiedTarget === 'cli_check' ? 'Disalin' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Solusi 2: Buat Symlink Permanen -->
                    <div class="bg-surface rounded-2xl p-4 border border-outline-variant/60 shadow-xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-on-surface flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-primary/20 text-primary text-xs flex items-center justify-center font-bold">2</span>
                                <span>Cara Permanen (Symlink Terminal Global)</span>
                            </span>
                            <span class="text-[10px] uppercase font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">Rekomendasi</span>
                        </div>
                        <p class="text-xs text-on-surface-variant">Arahkan symlink sistem global agar kata kunci <code>php</code> otomatis menggunakan PHP 8.4:</p>
                        <div class="flex items-center justify-between gap-2 bg-surface-container-highest p-2.5 rounded-xl font-mono text-xs">
                            <span class="text-on-surface truncate">{{ $laravelInfo['vps_symlink_cmd'] }}</span>
                            <button @click="copyCommand('{{ $laravelInfo['vps_symlink_cmd'] }}', 'symlink_check')"
                                class="px-2 py-1 rounded bg-primary text-on-primary text-[11px] font-bold shrink-0">
                                <span x-text="copiedTarget === 'symlink_check' ? 'Disalin' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. SOP Cepat Tanggap Penambalan Keamanan (Security Hotfix Playbook) -->
            <div class="bg-surface rounded-3xl border border-outline-variant/70 p-6 md:p-8 shadow-xs space-y-6">
                <div class="border-b border-outline-variant/40 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-rose-600 text-2xl" data-weight="fill">security</span>
                        <h3 class="text-lg md:text-xl font-black text-on-surface">Prosedur Cepat Tanggap Penambalan Celah Keamanan (Security Patching SOP)</h3>
                    </div>
                    <p class="text-xs md:text-sm text-on-surface-variant mt-1">
                        Pedoman 4 langkah standar jika tim keamanan siber atau komunitas Laravel merilis peringatan kerentanan baru (CVE).
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Step 1 -->
                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/50 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-rose-600 flex items-center gap-1.5 uppercase tracking-wider">
                                <span class="w-5 h-5 rounded-full bg-rose-500/20 text-rose-700 text-xs flex items-center justify-center font-bold">Langkah 1</span>
                                <span>Deteksi Keterdampakan di Server VPS</span>
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant">Jalankan audit dependensi di VPS untuk memastikan apakah SiReKa terdampak kerentanan:</p>
                        <div class="flex items-center justify-between gap-2 bg-surface p-2.5 rounded-xl font-mono text-xs border border-outline-variant/60">
                            <span class="text-on-surface truncate">{{ $laravelInfo['composer_audit_cmd'] }}</span>
                            <button @click="copyCommand('{{ $laravelInfo['composer_audit_cmd'] }}', 'sop_audit')"
                                class="px-2 py-1 rounded bg-surface-container-high hover:bg-primary hover:text-on-primary text-[11px] font-bold shrink-0 transition-colors">
                                <span x-text="copiedTarget === 'sop_audit' ? 'Disalin' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/50 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-primary flex items-center gap-1.5 uppercase tracking-wider">
                                <span class="w-5 h-5 rounded-full bg-primary/20 text-primary text-xs flex items-center justify-center font-bold">Langkah 2</span>
                                <span>Unduh Patch di Komputer Lokal</span>
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant">Update patch keamanan framework di laptop pengembang (jangan update langsung di VPS):</p>
                        <div class="flex items-center justify-between gap-2 bg-surface p-2.5 rounded-xl font-mono text-xs border border-outline-variant/60">
                            <span class="text-on-surface truncate">{{ $laravelInfo['local_update_cmd'] }}</span>
                            <button @click="copyCommand('{{ $laravelInfo['local_update_cmd'] }}', 'sop_update')"
                                class="px-2 py-1 rounded bg-surface-container-high hover:bg-primary hover:text-on-primary text-[11px] font-bold shrink-0 transition-colors">
                                <span x-text="copiedTarget === 'sop_update' ? 'Disalin' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/50 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-amber-600 flex items-center gap-1.5 uppercase tracking-wider">
                                <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-700 text-xs flex items-center justify-center font-bold">Langkah 3</span>
                                <span>Uji Coba Mandiri (Local Testing)</span>
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant">Jalankan unit testing di komputer lokal untuk memastikan tidak ada fitur yang patah:</p>
                        <div class="flex items-center justify-between gap-2 bg-surface p-2.5 rounded-xl font-mono text-xs border border-outline-variant/60">
                            <span class="text-on-surface truncate">php artisan test</span>
                            <button @click="copyCommand('php artisan test', 'sop_test')"
                                class="px-2 py-1 rounded bg-surface-container-high hover:bg-primary hover:text-on-primary text-[11px] font-bold shrink-0 transition-colors">
                                <span x-text="copiedTarget === 'sop_test' ? 'Disalin' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/50 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-emerald-600 flex items-center gap-1.5 uppercase tracking-wider">
                                <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-700 text-xs flex items-center justify-center font-bold">Langkah 4</span>
                                <span>Deploy Patch ke Server Produksi (VPS)</span>
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant">Push commit ke GitHub, lalu eksekusi script penarik otomatis di VPS aaPanel:</p>
                        <div class="flex items-center justify-between gap-2 bg-surface p-2.5 rounded-xl font-mono text-xs border border-outline-variant/60">
                            <span class="text-on-surface truncate">{{ $laravelInfo['vps_pull_cmd'] }}</span>
                            <button @click="copyCommand('{{ $laravelInfo['vps_pull_cmd'] }}', 'sop_pull')"
                                class="px-2 py-1 rounded bg-surface-container-high hover:bg-primary hover:text-on-primary text-[11px] font-bold shrink-0 transition-colors">
                                <span x-text="copiedTarget === 'sop_pull' ? 'Disalin' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Radar Eksternal: Tautan Resmi Pemantauan Celah Keamanan -->
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg md:text-xl font-black text-on-surface">Pusat Radar Eksternal & Informasi Celah Keamanan (Zero-Day Watch)</h3>
                    <p class="text-xs text-on-surface-variant">Tautan langsung ke basis data pengumuman kerentanan resmi dunia</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Link 1: GitHub Laravel Security -->
                    <a href="{{ $laravelInfo['advisory_url'] }}" target="_blank" rel="noopener noreferrer"
                        class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:border-rose-500/50 hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">security</span>
                            </div>
                            <h4 class="text-sm font-black text-on-surface group-hover:text-rose-600 transition-colors">GitHub Security Advisories</h4>
                            <p class="text-xs text-on-surface-variant leading-relaxed">Papan rilis resmi celah keamanan yang ditemukan dan ditambal oleh tim pengembang inti Laravel.</p>
                        </div>
                        <div class="pt-4 mt-3 border-t border-outline-variant/30 flex items-center justify-between text-xs font-bold text-rose-600">
                            <span>Buka GitHub Advisories</span>
                            <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">open_in_new</span>
                        </div>
                    </a>

                    <!-- Link 2: Packagist Security Tracker -->
                    <a href="{{ $laravelInfo['packagist_url'] }}" target="_blank" rel="noopener noreferrer"
                        class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:border-primary/50 hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">inventory_2</span>
                            </div>
                            <h4 class="text-sm font-black text-on-surface group-hover:text-primary transition-colors">Packagist Package Tracker</h4>
                            <p class="text-xs text-on-surface-variant leading-relaxed">Basis data repositori Composer untuk melacak riwayat versi dan status ketergantungan library.</p>
                        </div>
                        <div class="pt-4 mt-3 border-t border-outline-variant/30 flex items-center justify-between text-xs font-bold text-primary">
                            <span>Buka Packagist</span>
                            <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">open_in_new</span>
                        </div>
                    </a>

                    <!-- Link 3: Laravel Official Releases -->
                    <a href="{{ $laravelInfo['release_notes_url'] }}" target="_blank" rel="noopener noreferrer"
                        class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:border-emerald-500/50 hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">release_alert</span>
                            </div>
                            <h4 class="text-sm font-black text-on-surface group-hover:text-emerald-600 transition-colors">Laravel Release Changelog</h4>
                            <p class="text-xs text-on-surface-variant leading-relaxed">Catatan pembaruan berkala setiap rilis patch versi baru beserta daftar bug yang diperbaiki.</p>
                        </div>
                        <div class="pt-4 mt-3 border-t border-outline-variant/30 flex items-center justify-between text-xs font-bold text-emerald-600">
                            <span>Buka Riwayat Rilis</span>
                            <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">open_in_new</span>
                        </div>
                    </a>

                    <!-- Link 4: MITRE CVE Directory -->
                    <a href="{{ $laravelInfo['cve_tracker_url'] }}" target="_blank" rel="noopener noreferrer"
                        class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:border-purple-500/50 hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">shield</span>
                            </div>
                            <h4 class="text-sm font-black text-on-surface group-hover:text-purple-600 transition-colors">MITRE CVE Global Directory</h4>
                            <p class="text-xs text-on-surface-variant leading-relaxed">Katalog resmi identifikasi kerentanan keamanan siber dunia standar NIST / US-CERT.</p>
                        </div>
                        <div class="pt-4 mt-3 border-t border-outline-variant/30 flex items-center justify-between text-xs font-bold text-purple-600">
                            <span>Buka Direktori CVE</span>
                            <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">open_in_new</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- TAB 3: LOG PEMBARUAN & RIWAYAT VERSI (CHANGELOG)           -->
        <!-- ========================================================== -->
        <div x-show="activeTab === 'changelog'" x-transition class="space-y-6">
            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap items-center gap-2 pt-1">
                <span class="text-xs font-bold text-on-surface-variant mr-1">Filter Kategori:</span>
                <button @click="selectedCategory = 'all'"
                    :class="selectedCategory === 'all' ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 font-bold' : 'bg-surface border border-outline-variant text-on-surface-variant hover:text-on-surface'"
                    class="px-3 py-1 rounded-full text-xs transition-all">Semua Pembaruan</button>
                <button @click="selectedCategory = 'new'"
                    :class="selectedCategory === 'new' ? 'bg-emerald-600 text-white font-bold' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200 hover:bg-emerald-100'"
                    class="px-3 py-1 rounded-full text-xs transition-all flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Fitur Baru
                </button>
                <button @click="selectedCategory = 'improvement'"
                    :class="selectedCategory === 'improvement' ? 'bg-sky-600 text-white font-bold' : 'bg-sky-50 dark:bg-sky-950/40 text-sky-800 dark:text-sky-300 border border-sky-200 hover:bg-sky-100'"
                    class="px-3 py-1 rounded-full text-xs transition-all flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Peningkatan UI/UX
                </button>
                <button @click="selectedCategory = 'security'"
                    :class="selectedCategory === 'security' ? 'bg-purple-600 text-white font-bold' : 'bg-purple-50 dark:bg-purple-950/40 text-purple-800 dark:text-purple-300 border border-purple-200 hover:bg-purple-100'"
                    class="px-3 py-1 rounded-full text-xs transition-all flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Keamanan & Audit
                </button>
                <button @click="selectedCategory = 'fix'"
                    :class="selectedCategory === 'fix' ? 'bg-amber-600 text-white font-bold' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200 hover:bg-amber-100'"
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
                    <div class="absolute left-2 md:left-6 top-5 -translate-x-1/2 w-6 h-6 rounded-full flex items-center justify-center shadow-sm z-10 transition-transform group-hover:scale-110 {{ $index === 0 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 dark:ring-emerald-900/50' : 'bg-surface border-2 border-primary text-primary' }}">
                        <span class="material-symbols-outlined text-[14px]">{{ $index === 0 ? 'star' : 'commit' }}</span>
                    </div>

                    <!-- Changelog Card -->
                    <div class="bg-surface rounded-3xl border border-outline-variant/70 p-5 md:p-6 shadow-xs hover:shadow-md transition-all {{ $index === 0 ? 'ring-2 ring-emerald-500/40 border-emerald-500/40' : '' }}">
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
                            <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-surface-container-low/60 hover:bg-surface-container-low transition-colors"
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

        <!-- ========================================================== -->
        <!-- TAB 3: MATRIKS HAK AKSES PERAN PENGGUNA (ACCESS MATRIX)    -->
        <!-- ========================================================== -->
        <div x-show="activeTab === 'hak_akses'" x-transition class="space-y-6">
            <!-- Header Card Penjelasan Hak Akses -->
            <div class="bg-surface rounded-3xl border border-outline-variant/70 p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-2xl" data-weight="fill">shield_person</span>
                        <h2 class="text-lg md:text-xl font-black text-on-surface">Matriks Hak Akses & Pembagian Wewenang Peran</h2>
                    </div>
                    <p class="text-xs md:text-sm text-on-surface-variant">
                        Daftar lengkap wewenang operasional antara 4 peran pengguna SiReKa: Administrator BKAD, Konsolidator, Operator SKPD, dan Auditor Inspektorat/BPK.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold">
                    <span class="px-2 py-1 rounded-lg bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Akses Penuh
                    </span>
                    <span class="px-2 py-1 rounded-lg bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">visibility</span> Read-Only Audit
                    </span>
                    <span class="px-2 py-1 rounded-lg bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">tune</span> Terbatas SKPD
                    </span>
                    <span class="px-2 py-1 rounded-lg bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">block</span> Dilarang / Eksklusi
                    </span>
                </div>
            </div>

            <!-- Role Matrix Table -->
            <div class="bg-surface rounded-3xl border border-outline-variant/70 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs md:text-sm">
                        <thead>
                            <tr class="bg-surface-container-high border-b border-outline-variant/60 text-on-surface font-black">
                                <th class="p-4 pl-6">Pilar Modul / Fitur</th>
                                <th class="p-4 w-1/5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-primary text-[18px]">admin_panel_settings</span>
                                        <span>Administrator</span>
                                    </div>
                                    <span class="text-[10px] text-on-surface-variant font-normal">Super Admin BKAD</span>
                                </th>
                                <th class="p-4 w-1/5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-indigo-600 text-[18px]">fact_check</span>
                                        <span>Konsolidator</span>
                                    </div>
                                    <span class="text-[10px] text-on-surface-variant font-normal">Bidang Akuntansi</span>
                                </th>
                                <th class="p-4 w-1/5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-amber-600 text-[18px]">domain</span>
                                        <span>Operator SKPD</span>
                                    </div>
                                    <span class="text-[10px] text-on-surface-variant font-normal">Bendahara Instansi</span>
                                </th>
                                <th class="p-4 pr-6 w-1/5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-purple-600 text-[18px]">policy</span>
                                        <span>Auditor (Inspektorat / BPK)</span>
                                    </div>
                                    <span class="text-[10px] text-on-surface-variant font-normal">Strict Read-Only Audit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @foreach($roleMatrix as $row)
                            <tr class="hover:bg-surface-container-low/50 transition-colors"
                                x-show="matchesSearch('{{ addslashes($row['modul'] . ' ' . $row['kategori']) }}')">
                                <td class="p-4 pl-6 align-top">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-primary">{{ $row['kategori'] }}</span>
                                    <p class="font-bold text-on-surface mt-0.5">{{ $row['modul'] }}</p>
                                </td>

                                <!-- Admin -->
                                <td class="p-4 align-top">
                                    @if($row['admin']['status'] === 'full')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span> Akses Penuh
                                    </span>
                                    @endif
                                    <p class="text-[11px] text-on-surface-variant mt-1 leading-snug">{{ $row['admin']['desc'] }}</p>
                                </td>

                                <!-- Konsolidator -->
                                <td class="p-4 align-top">
                                    @if($row['konsolidator']['status'] === 'full')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span> Akses Penuh
                                    </span>
                                    @elseif($row['konsolidator']['status'] === 'readonly')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30">
                                        <span class="material-symbols-outlined text-[13px]">visibility</span> Cek / Validasi
                                    </span>
                                    @elseif($row['konsolidator']['status'] === 'bypass')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-500/30">
                                        <span class="material-symbols-outlined text-[13px]">key</span> Bypass Akses
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        <span class="material-symbols-outlined text-[13px]">block</span> Dilarang
                                    </span>
                                    @endif
                                    <p class="text-[11px] text-on-surface-variant mt-1 leading-snug">{{ $row['konsolidator']['desc'] }}</p>
                                </td>

                                <!-- Operator -->
                                <td class="p-4 align-top">
                                    @if($row['operator']['status'] === 'limited')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                        <span class="material-symbols-outlined text-[13px]">tune</span> Terbatas SKPD
                                    </span>
                                    @elseif($row['operator']['status'] === 'locked')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        <span class="material-symbols-outlined text-[13px]">lock</span> Terkunci
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        <span class="material-symbols-outlined text-[13px]">block</span> Dilarang
                                    </span>
                                    @endif
                                    <p class="text-[11px] text-on-surface-variant mt-1 leading-snug">{{ $row['operator']['desc'] }}</p>
                                </td>

                                <!-- Auditor -->
                                <td class="p-4 pr-6 align-top">
                                    @if($row['auditor']['status'] === 'full')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-purple-500/15 text-purple-700 dark:text-purple-300 border border-purple-500/30">
                                        <span class="material-symbols-outlined text-[13px]">visibility</span> Pengawasan Penuh
                                    </span>
                                    @elseif($row['auditor']['status'] === 'locked')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        <span class="material-symbols-outlined text-[13px]">lock</span> Terkunci
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        <span class="material-symbols-outlined text-[13px]">block</span> Dilarang / Eksklusi
                                    </span>
                                    @endif
                                    <p class="text-[11px] text-on-surface-variant mt-1 leading-snug">{{ $row['auditor']['desc'] }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Catatan Standar Independensi Auditor -->
            <div class="bg-amber-500/10 border border-amber-500/30 p-5 rounded-3xl flex items-start gap-3.5">
                <span class="material-symbols-outlined text-amber-600 text-2xl mt-0.5 shrink-0" data-weight="fill">info</span>
                <div class="space-y-1 text-xs leading-relaxed text-amber-900 dark:text-amber-200">
                    <p class="font-black text-sm">Prinsip Independensi Audit (Standar Pemeriksaan Keuangan Negara - SPKN):</p>
                    <p>
                        Akun peran <strong>Auditor (Inspektorat / BPK)</strong> dirancang dengan prinsip <em>Strict Read-Only & Zero-Interference</em>. Auditor memiliki hak akses membaca seluruh data rekonsiliasi kas, dashboard eksekutif, dan brankas berkas digital se-Kabupaten Tapin tanpa dibebani akses mutasi transaksi atau fitur komunikasi langsung WhatsApp guna menjaga integritas dan netralitas pengawasan pemeriksaan.
                    </p>
                </div>
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- TAB 4: KATALOG MODUL & ARSITEKTUR SPESIFIKASI SISTEM       -->
        <!-- ========================================================== -->
        <div x-show="activeTab === 'katalog'" x-transition class="space-y-6">
            <!-- 1. Katalog Modul SiReKa -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg md:text-xl font-black text-on-surface">Katalog Modul Unggulan SiReKa</h2>
                        <p class="text-xs text-on-surface-variant">Daftar pilar modul inti yang terintegrasi di dalam aplikasi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($modulKatalog as $modul)
                    <div class="bg-surface rounded-3xl border border-outline-variant/70 p-5 shadow-xs hover:shadow-md hover:border-primary/40 transition-all flex flex-col justify-between"
                        x-show="matchesSearch('{{ addslashes($modul['nama'] . ' ' . $modul['deskripsi'] . ' ' . implode(' ', $modul['fitur_kunci'])) }}')">
                        <div>
                            <!-- Header Card -->
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $modulColorClasses[$modul['color']] ?? 'bg-slate-500/15 text-slate-700' }}">
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
                            <h3 class="text-base font-extrabold text-on-surface tracking-tight">{{ $modul['nama'] }}</h3>
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
                                class="w-full py-2.5 px-3 rounded-2xl bg-surface-container hover:bg-primary hover:text-on-primary text-on-surface text-xs font-bold flex items-center justify-center gap-1.5 transition-all group">
                                <span>{{ $modul['url_label'] }}</span>
                                <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Arsitektur & Spesifikasi Server Environment -->
            <div class="pt-4 border-t border-outline-variant/40 space-y-4">
                <div>
                    <h2 class="text-lg md:text-xl font-black text-on-surface">Arsitektur & Spesifikasi Runtime Server</h2>
                    <p class="text-xs text-on-surface-variant">Informasi lingkungan teknis dan konfigurasi aktif sistem</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Card 1: Server Environment -->
                    <div class="bg-surface rounded-3xl border border-outline-variant/70 p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl" data-weight="fill">developer_board</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-on-surface">Lingkungan Server</h3>
                                <p class="text-xs text-on-surface-variant">Konfigurasi runtime aktif</p>
                            </div>
                        </div>

                        <div class="space-y-2.5 pt-2">
                            @foreach($systemSpecs as $key => $val)
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-outline-variant/30 last:border-0">
                                <span class="text-on-surface-variant font-medium uppercase tracking-wider text-[11px]">{{ str_replace('_', ' ', $key) }}</span>
                                <span class="font-mono font-bold text-on-surface bg-surface-container px-2 py-0.5 rounded">{{ $val }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Card 2: Keamanan & Kepatuhan BPK -->
                    <div class="bg-surface rounded-3xl border border-outline-variant/70 p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl" data-weight="fill">shield</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-on-surface">Keamanan & Audit BPK</h3>
                                <p class="text-xs text-on-surface-variant">Prinsip kepatuhan standar</p>
                            </div>
                        </div>

                        <ul class="space-y-3 pt-2 text-xs text-on-surface-variant">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                                <span><strong>Audit Trail Forensik:</strong> Seluruh modifikasi data dan penimpaan file dicatat oleh Spatie ActivityLog v5.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                                <span><strong>Anti-Tampering Lock:</strong> Formulir pemeriksaan terkunci otomatis saat status rekonsiliasi disahkan VALID.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                                <span><strong>Proteksi Anti-IDOR:</strong> Pembatasan hak akses berbasis instansi untuk mencegah eksploitasi URL transaksi.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">verified</span>
                                <span><strong>Fail-Safe Architecture:</strong> Kegagalan koneksi pihak ketiga (Telegram) tidak mengganggu transaksi SKPD.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Card 3: Storage & Maintenance Quick Access -->
                    <div class="bg-surface rounded-3xl border border-outline-variant/70 p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl" data-weight="fill">settings_suggest</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-on-surface">Panel Administrasi Cepat</h3>
                                <p class="text-xs text-on-surface-variant">Tautan pintas modul sistem</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <a href="{{ route('pengaturan.storage.index') }}"
                                class="w-full p-3 rounded-2xl bg-surface-container hover:bg-surface-container-high border border-outline-variant flex items-center justify-between text-xs font-bold text-on-surface transition-all">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-amber-600">dns</span>
                                    <span>Manajemen Storage & NAS</span>
                                </div>
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </a>

                            <a href="{{ route('pengaturan.maintenance.index') }}"
                                class="w-full p-3 rounded-2xl bg-surface-container hover:bg-surface-container-high border border-outline-variant flex items-center justify-between text-xs font-bold text-on-surface transition-all">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-rose-600">lock_reset</span>
                                    <span>Mode Lockdown & Backup</span>
                                </div>
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </a>

                            <a href="{{ route('pengaturan.telegram.index') }}"
                                class="w-full p-3 rounded-2xl bg-surface-container hover:bg-surface-container-high border border-outline-variant flex items-center justify-between text-xs font-bold text-on-surface transition-all">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-sky-600">send</span>
                                    <span>Konfigurasi Bot Telegram</span>
                                </div>
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </a>

                            <a href="{{ route('log.index') }}"
                                class="w-full p-3 rounded-2xl bg-surface-container hover:bg-surface-container-high border border-outline-variant flex items-center justify-between text-xs font-bold text-on-surface transition-all">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-slate-600">history</span>
                                    <span>Jejak Audit Forensik</span>
                                </div>
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
