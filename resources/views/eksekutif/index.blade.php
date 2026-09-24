<x-app-layout>
    <!-- Top Executive Command Bar -->
    <div class="mb-6 space-y-4">
        <!-- Title & TV Command Center Controls -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-indigo-500/20 relative overflow-hidden">
            <!-- Background glow decoration -->
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <!-- Branding & Title -->
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-amber-300 text-slate-950 flex items-center justify-center font-black shadow-lg shadow-amber-500/20 shrink-0">
                        <span class="material-symbols-outlined text-[32px]">query_stats</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/30 font-mono">
                                EXECUTIVE COMMAND CENTER
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-slate-300 border border-white/10">
                                KDH & SEKDA VIEW
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> LIVE MONITORING
                            </span>
                        </div>
                        <h1 class="text-xl md:text-2xl font-black text-white mt-1 tracking-tight">
                            Tinjauan Strategis Rekonsiliasi Kas Daerah
                        </h1>
                        <p class="text-xs text-slate-300 mt-0.5">
                            Pemerintah Kabupaten Tapin &bull; Tahun Anggaran <span class="text-amber-300 font-bold font-mono">{{ $tahunAktif }}</span> &bull; Periode <span class="text-white font-bold">{{ $namaBulan[$bulanAktif - 1] }}</span>
                        </p>
                    </div>
                </div>

                <!-- Live Tools & Kiosk Display Controls -->
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-start lg:justify-end">
                    <!-- Live WITA Clock -->
                    <div class="px-4 py-2 rounded-xl bg-slate-800/80 border border-slate-700/80 flex items-center gap-2 shadow-inner">
                        <span class="material-symbols-outlined text-amber-400 text-lg">schedule</span>
                        <div class="text-left font-mono">
                            <div class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Waktu WITA</div>
                            <div id="liveClock" class="text-xs font-bold text-white tracking-wider">00:00:00 WITA</div>
                        </div>
                    </div>

                    <!-- Auto-Refresh Kiosk Engine -->
                    <div class="px-3.5 py-2 rounded-xl bg-slate-800/80 border border-slate-700/80 flex items-center gap-2.5 shadow-inner">
                        <button id="toggleRefreshBtn" onclick="toggleAutoRefresh()" class="text-slate-300 hover:text-white transition-colors" title="Jeda/Lanjutkan Refresh Otomatis">
                            <span id="refreshIcon" class="material-symbols-outlined text-[18px]">sync</span>
                        </button>
                        <div class="text-left font-mono text-[11px]">
                            <span class="text-slate-400 text-[9px] block uppercase font-bold">Auto-Update</span>
                            <span id="countdownText" class="text-sky-300 font-bold font-mono">60s</span>
                        </div>
                    </div>

                    <!-- Fullscreen TV Mode -->
                    <button onclick="toggleFullscreen()" class="px-3.5 py-2.5 rounded-xl bg-slate-800/90 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm active:scale-95 cursor-pointer" title="Mode Layar Penuh (TV Monitor)">
                        <span class="material-symbols-outlined text-[18px]">fullscreen</span>
                        <span class="hidden sm:inline">Layar Penuh</span>
                    </button>

                    <!-- Cetak Briefing Eksekutif -->
                    <a href="{{ route('eksekutif.cetak-brief', ['bulan' => $bulanAktif]) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 text-xs font-extrabold flex items-center gap-1.5 shadow-lg shadow-amber-500/20 transition-all active:scale-95 shrink-0">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        <span>Cetak Ringkasan Eksekutif</span>
                    </a>
                </div>
            </div>

            <!-- Month Quick Filter Bar -->
            <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-300">
                    <span class="material-symbols-outlined text-amber-400 text-sm">calendar_month</span>
                    <span>PILIH PERIODE BULAN:</span>
                </div>
                <div class="flex items-center gap-1 overflow-x-auto pb-1 max-w-full custom-scrollbar">
                    @foreach($namaBulan as $idx => $mName)
                        @php $mNum = $idx + 1; @endphp
                        <a href="{{ route('eksekutif.index', ['bulan' => $mNum]) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $bulanAktif == $mNum ? 'bg-amber-400 text-slate-950 shadow-md font-black' : 'bg-slate-800/60 hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                            {{ substr($mName, 0, 3) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- 1. BARIS KPI MAKRO (4 BENTO CARDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Likuiditas Kas Pemda di SKPD -->
        <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-sky-500/10 rounded-full blur-2xl group-hover:bg-sky-500/20 transition-all"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Likuiditas Kas di SKPD</span>
                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">account_balance</span>
                    </div>
                </div>
                <div class="mt-2">
                    <h3 class="text-xl lg:text-2xl font-black text-on-surface tracking-tight font-mono">
                        Rp {{ number_format($totalBank, 2, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">Saldo di Bank Kalsel seluruh SKPD</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 flex items-center justify-between text-xs">
                <span class="text-on-surface-variant text-[11px]">Total BKU: Rp {{ number_format($totalBku, 2, ',', '.') }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $totalSelisih == 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $totalSelisih == 0 ? 'STATUS KLOP' : 'ADA SELISIH' }}
                </span>
            </div>
        </div>

        <!-- Card 2: Indeks Kepatuhan Rekonsiliasi Daerah -->
        <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Indeks Kepatuhan Pemda</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <h3 class="text-xl lg:text-2xl font-black text-emerald-600 tracking-tight font-mono">
                        {{ $kepatuhanRate }}%
                    </h3>
                    <span class="text-xs font-bold text-on-surface-variant">({{ $countKlop }} / {{ $totalSkpdCount }} SKPD)</span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden">
                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" style="width: {{ min(100, $kepatuhanRate) }}%"></div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 flex items-center justify-between text-[11px] text-on-surface-variant">
                <span>Belum Klop: <b>{{ $totalSkpdCount - $countKlop }}</b> SKPD</span>
                <span class="font-bold text-emerald-700">Target BPK: 100%</span>
            </div>
        </div>

        <!-- Card 3: Akumulasi Selisih Kas -->
        <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-6 -top-6 w-24 h-24 {{ $totalSelisih == 0 ? 'bg-emerald-500/10' : 'bg-rose-500/10' }} rounded-full blur-2xl transition-all"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Akumulasi Selisih Kas</span>
                    <div class="w-8 h-8 rounded-lg {{ $totalSelisih == 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">{{ $totalSelisih == 0 ? 'check_circle' : 'warning' }}</span>
                    </div>
                </div>
                <div class="mt-2">
                    <h3 class="text-xl lg:text-2xl font-black {{ $totalSelisih == 0 ? 'text-emerald-600' : 'text-rose-600' }} tracking-tight font-mono">
                        Rp {{ number_format($totalSelisih, 2, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">
                        {{ $totalSelisih == 0 ? 'Seluruh saldo fisik klop sempurna' : 'Ditemukan selisih pada ' . $countSelisih . ' SKPD' }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 flex items-center justify-between text-xs">
                <span class="text-[11px] text-on-surface-variant">Jumlah SKPD Selisih:</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $countSelisih == 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $countSelisih }} SKPD
                </span>
            </div>
        </div>

        <!-- Card 4: Ketepatan Waktu Lapor -->
        <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Ketepatan Waktu Lapor</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <h3 class="text-xl lg:text-2xl font-black text-amber-600 tracking-tight font-mono">
                        {{ $timelinessRate }}%
                    </h3>
                    <span class="text-xs font-bold text-on-surface-variant">Lapor &le; tgl 10</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden">
                    <div class="bg-amber-500 h-2 rounded-full transition-all duration-1000" style="width: {{ min(100, $timelinessRate) }}%"></div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 flex items-center justify-between text-[11px] text-on-surface-variant">
                <span>Kesiapan Berkas BPK:</span>
                <span class="font-bold text-on-surface font-mono">{{ $auditReadinessRate }}% Lengkap</span>
            </div>
        </div>
    </div>

    <!-- 2. DECISION SUPPORT SYSTEM: REKOMENDASI KEBIJAKAN UNTUK KDH / SEKDA -->
    <div class="mb-6">
        <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-5 text-white shadow-md border border-indigo-500/20 space-y-3">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-400 text-slate-950 flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[20px]">psychology</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-white">Decision Support System &bull; Rekomendasi Kebijakan Pimpinan Daerah</h2>
                        <p class="text-[11px] text-slate-300">Hasil telaah analitik otomatis terhadap rekonsiliasi kas periode {{ $namaBulan[$bulanAktif - 1] }} {{ $tahunAktif }}</p>
                    </div>
                </div>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-white/10 text-amber-300 border border-amber-300/30 font-bold hidden sm:inline">
                    TAPIN POLICY ENGINE
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                @foreach($rekomendasiKebijakan as $rek)
                    @php
                        $borderCol = match($rek['tipe']) {
                            'danger' => 'border-rose-500/40 bg-rose-950/30 text-rose-200',
                            'warning' => 'border-amber-500/40 bg-amber-950/30 text-amber-200',
                            'success' => 'border-emerald-500/40 bg-emerald-950/30 text-emerald-200',
                            default => 'border-sky-500/40 bg-sky-950/30 text-sky-200',
                        };
                        $iconCol = match($rek['tipe']) {
                            'danger' => 'text-rose-400',
                            'warning' => 'text-amber-400',
                            'success' => 'text-emerald-400',
                            default => 'text-sky-400',
                        };
                    @endphp
                    <div class="p-3.5 rounded-xl border {{ $borderCol }} flex items-start gap-3 backdrop-blur-xs">
                        <span class="material-symbols-outlined {{ $iconCol }} text-xl shrink-0 mt-0.5">{{ $rek['icon'] }}</span>
                        <div class="space-y-1">
                            <h3 class="text-xs font-bold text-white uppercase tracking-wider">{{ $rek['judul'] }}</h3>
                            <p class="text-[11px] leading-relaxed text-slate-200/90">{{ $rek['pesan'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 3. GRAFIK ANALITIK: TREN LIKUIDITAS 12 BULAN & KOMPOSISI KEPATUHAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Grafik 1: Tren Likuiditas 12 Bulan (Line Chart BKU vs Bank) -->
        <div class="lg:col-span-2 bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-outline-variant/40 pb-3 mb-4">
                <div>
                    <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">show_chart</span>
                        Tren Fluktuasi Likuiditas Kas Pemda 12 Bulan (TA {{ $tahunAktif }})
                    </h3>
                    <p class="text-[11px] text-on-surface-variant">Perbandingan Akumulasi Saldo BKU Bendahara vs Saldo Rekening Koran Bank Kalsel</p>
                </div>
                <div class="flex items-center gap-3 text-[11px]">
                    <span class="flex items-center gap-1 font-bold text-sky-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> BKU
                    </span>
                    <span class="flex items-center gap-1 font-bold text-emerald-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Bank
                    </span>
                </div>
            </div>
            <div class="h-64 w-full relative">
                <canvas id="chartLikuiditas12Bulan"></canvas>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Status SKPD Bulan Ini (Donut Chart) -->
        <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs flex flex-col justify-between">
            <div class="border-b border-outline-variant/40 pb-3 mb-4">
                <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-amber-500 text-[18px]">pie_chart</span>
                    Distribusi Kepatuhan SKPD ({{ $namaBulan[$bulanAktif - 1] }})
                </h3>
                <p class="text-[11px] text-on-surface-variant">Komposisi status dari total {{ $totalSkpdCount }} SKPD aktif</p>
            </div>
            <div class="h-48 w-full relative flex items-center justify-center">
                <canvas id="chartDonutKepatuhan"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-3 border-t border-outline-variant/40 text-[11px]">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-on-surface-variant">Klop: <b>{{ $countKlop }}</b></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500 shrink-0"></span>
                    <span class="text-on-surface-variant">Draft: <b>{{ $countDraft }}</b></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                    <span class="text-on-surface-variant">Selisih: <b>{{ $countSelisih }}</b></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shrink-0"></span>
                    <span class="text-on-surface-variant">Belum: <b>{{ $countBelumLapor }}</b></span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. EARLY WARNING SYSTEM (EWS): TOP 5 TERBAIK vs BOTTOM 5 RAWAN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top 5 Disiplin -->
        <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs">
            <div class="flex items-center justify-between border-b border-outline-variant/40 pb-3 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[18px]">trophy</span>
                    </div>
                    <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider">Top 5 SKPD Paling Tertib & Disiplin (TA {{ $tahunAktif }})</h3>
                </div>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Kinerja A</span>
            </div>
            <div class="space-y-2">
                @foreach($top5Skpd as $idx => $s)
                    <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/50 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-bold text-[11px] flex items-center justify-center shrink-0">
                                {{ $idx + 1 }}
                            </span>
                            <div class="truncate">
                                <p class="font-bold text-on-surface truncate">{{ $s->nama }}</p>
                                <p class="text-[10px] text-on-surface-variant font-mono">{{ $s->kode }} &bull; Bendahara: {{ $s->nama_bendahara ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold font-mono text-[11px]">
                                Skor {{ $s->skor_kinerja }}
                            </span>
                            <span class="text-[10px] text-slate-500 block mt-0.5">{{ $s->verified_count }} bln Klop</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom 5 Butuh Perhatian (EWS) -->
        <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs">
            <div class="flex items-center justify-between border-b border-outline-variant/40 pb-3 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[18px]">emergency</span>
                    </div>
                    <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider">Early Warning System: 5 SKPD Butuh Atensi Pimpinan</h3>
                </div>
                <span class="text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">Perhatian Khusus</span>
            </div>
            <div class="space-y-2">
                @foreach($bottom5Skpd as $idx => $s)
                    <div class="p-2.5 rounded-xl bg-rose-50/50 border border-rose-200/60 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-rose-600 text-white font-bold text-[11px] flex items-center justify-center shrink-0">
                                !
                            </span>
                            <div class="truncate">
                                <p class="font-bold text-on-surface truncate">{{ $s->nama }}</p>
                                <p class="text-[10px] text-rose-700 font-mono">{{ $s->kode }} &bull; Selisih Kas: {{ $s->selisih_count }}x</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 flex items-center gap-2">
                            <div>
                                <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 font-bold font-mono text-[11px]">
                                    Skor {{ $s->skor_kinerja }}
                                </span>
                                <span class="text-[10px] text-rose-700 block mt-0.5">{{ $s->verified_count }} bln lapor</span>
                            </div>
                            @if($s->no_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($s->no_whatsapp, '08') ? '628' . substr($s->no_whatsapp, 2) : $s->no_whatsapp) }}?text={{ urlencode('Pemberitahuan Pimpinan BKAD Tapin: Mohon percepatan penyelesaian rekonsiliasi kas SKPD ' . $s->nama . '.') }}"
                                   target="_blank" class="w-7 h-7 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shadow-xs" title="Kirim Teguran via WhatsApp">
                                    <span class="material-symbols-outlined text-[15px]">chat</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 5. MATRIKS VISUAL STATUS SELURUH SKPD KABUPATEN TAPIN -->
    <div class="bg-surface rounded-2xl border border-outline-variant/70 p-5 shadow-xs mb-8" x-data="{
        search: '',
        statusFilter: 'all',
        matchesFilter(status) {
            if (this.statusFilter === 'all') return true;
            return this.statusFilter === status;
        },
        matchesSearch(text) {
            if (!this.search.trim()) return true;
            return text.toLowerCase().includes(this.search.toLowerCase());
        }
    }">
        <!-- Header & Live Search Bar -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-outline-variant/40 pb-4 mb-4">
            <div>
                <h3 class="text-sm font-extrabold text-on-surface uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">grid_view</span>
                    Matriks Kepatuhan Seluruh SKPD Kabupaten Tapin ({{ $totalSkpdCount }} Instansi)
                </h3>
                <p class="text-xs text-on-surface-variant">Status rekonsiliasi kas periode <strong>{{ $namaBulan[$bulanAktif - 1] }} {{ $tahunAktif }}</strong></p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" x-model="search" placeholder="Cari nama atau kode SKPD..."
                        class="w-full h-9 pl-9 pr-3 rounded-xl border border-outline-variant bg-surface text-xs text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>

                <!-- Filter Chips -->
                <div class="flex items-center gap-1 overflow-x-auto pb-1">
                    <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high'" class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-colors">
                        Semua
                    </button>
                    <button @click="statusFilter = 'klop'" :class="statusFilter === 'klop' ? 'bg-emerald-600 text-white' : 'bg-surface-container text-emerald-800 hover:bg-emerald-100'" class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-colors">
                        KLOP ({{ $countKlop }})
                    </button>
                    <button @click="statusFilter = 'draft'" :class="statusFilter === 'draft' ? 'bg-sky-600 text-white' : 'bg-surface-container text-sky-800 hover:bg-sky-100'" class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-colors">
                        Draft ({{ $countDraft }})
                    </button>
                    <button @click="statusFilter = 'selisih'" :class="statusFilter === 'selisih' ? 'bg-rose-600 text-white' : 'bg-surface-container text-rose-800 hover:bg-rose-100'" class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-colors">
                        Selisih ({{ $countSelisih }})
                    </button>
                    <button @click="statusFilter = 'belum'" :class="statusFilter === 'belum' ? 'bg-amber-500 text-slate-950' : 'bg-surface-container text-amber-800 hover:bg-amber-100'" class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-colors">
                        Belum ({{ $countBelumLapor }})
                    </button>
                </div>
            </div>
        </div>

        <!-- SKPD Matrix Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5">
            @foreach($skpdMatrix as $item)
                @php
                    $cardTheme = match($item['status']) {
                        'klop' => 'border-emerald-300 bg-emerald-50/30 text-emerald-950 hover:border-emerald-500',
                        'draft' => 'border-sky-300 bg-sky-50/30 text-sky-950 hover:border-sky-500',
                        'selisih' => 'border-rose-400 bg-rose-50/40 text-rose-950 hover:border-rose-600 shadow-xs',
                        default => 'border-amber-300 bg-amber-50/30 text-amber-950 hover:border-amber-500',
                    };
                    $badgeTheme = match($item['status']) {
                        'klop' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'draft' => 'bg-sky-100 text-sky-800 border-sky-300',
                        'selisih' => 'bg-rose-100 text-rose-800 border-rose-300 font-black animate-pulse',
                        default => 'bg-amber-100 text-amber-900 border-amber-300',
                    };
                @endphp
                <div class="p-3.5 rounded-xl border {{ $cardTheme }} transition-all flex flex-col justify-between space-y-3"
                    x-show="matchesFilter('{{ $item['status'] }}') && matchesSearch('{{ addslashes($item['kode'] . ' ' . $item['nama'] . ' ' . $item['bendahara']) }}')">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="font-mono text-[10px] font-bold px-1.5 py-0.5 rounded bg-surface/80 border border-outline-variant/40">
                                {{ $item['kode'] }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $badgeTheme }}">
                                {{ $item['status_label'] }}
                            </span>
                        </div>
                        <h4 class="text-xs font-bold text-on-surface line-clamp-2 leading-snug" title="{{ $item['nama'] }}">
                            {{ $item['nama'] }}
                        </h4>
                        <p class="text-[10px] text-on-surface-variant mt-0.5">Bendahara: {{ $item['bendahara'] }}</p>
                    </div>

                    <!-- Nominal Financial Details -->
                    <div class="pt-2 border-t border-outline-variant/40 space-y-1 font-mono text-[11px]">
                        @if($item['status'] !== 'belum')
                            <div class="flex items-center justify-between text-on-surface-variant">
                                <span>BKU:</span>
                                <span class="font-bold text-on-surface">Rp {{ number_format($item['bku'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-on-surface-variant">
                                <span>Bank:</span>
                                <span class="font-bold text-on-surface">Rp {{ number_format($item['bank'], 2, ',', '.') }}</span>
                            </div>
                            @if($item['selisih'] > 0)
                                <div class="flex items-center justify-between text-rose-700 font-bold bg-rose-100/80 px-1.5 py-0.5 rounded">
                                    <span>Selisih:</span>
                                    <span>Rp {{ number_format($item['selisih'], 2, ',', '.') }}</span>
                                </div>
                            @endif
                        @else
                            <div class="py-2 text-center text-amber-800 text-[10px] font-sans italic">
                                Belum ada berkas entri rekonsiliasi bulan ini
                            </div>
                        @endif
                    </div>

                    <!-- Footer Info & Direct WA Action -->
                    <div class="pt-2 border-t border-outline-variant/30 flex items-center justify-between text-[10px] text-on-surface-variant">
                        <span>{{ $item['status'] !== 'belum' ? 'Berkas: ' . $item['berkas_persen'] . '%' : 'Menunggu Pelaporan' }}</span>
                        @if($item['no_wa'])
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($item['no_wa'], '08') ? '628' . substr($item['no_wa'], 2) : $item['no_wa']) }}?text={{ urlencode('Halo Bendahara ' . $item['nama'] . ', mohon konfirmasi status rekonsiliasi kas periode ' . $namaBulan[$bulanAktif - 1] . ' ' . $tahunAktif . '.') }}"
                               target="_blank" class="text-emerald-700 hover:text-emerald-900 font-bold flex items-center gap-0.5" title="Hubungi via WhatsApp">
                                <span class="material-symbols-outlined text-[13px]">chat</span> WA
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chart.js & Command Center Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Live WITA Clock
        function updateWitaClock() {
            const now = new Date();
            // Offset to UTC+8 (WITA)
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const witaTime = new Date(utc + (3600000 * 8));
            
            const hours = String(witaTime.getHours()).padStart(2, '0');
            const minutes = String(witaTime.getMinutes()).padStart(2, '0');
            const seconds = String(witaTime.getSeconds()).padStart(2, '0');
            
            const clockEl = document.getElementById('liveClock');
            if (clockEl) {
                clockEl.innerText = `${hours}:${minutes}:${seconds} WITA`;
            }
        }
        setInterval(updateWitaClock, 1000);
        updateWitaClock();

        // 2. Auto-Refresh Kiosk Engine (60s countdown)
        let refreshSeconds = 60;
        let isPaused = false;
        const countdownEl = document.getElementById('countdownText');
        const refreshIcon = document.getElementById('refreshIcon');

        function startCountdown() {
            setInterval(() => {
                if (!isPaused) {
                    refreshSeconds--;
                    if (countdownEl) {
                        countdownEl.innerText = `${refreshSeconds}s`;
                    }
                    if (refreshSeconds <= 0) {
                        window.location.reload();
                    }
                }
            }, 1000);
        }
        startCountdown();

        window.toggleAutoRefresh = function() {
            isPaused = !isPaused;
            if (isPaused) {
                if (countdownEl) countdownEl.innerText = 'PAUSED';
                if (refreshIcon) refreshIcon.innerText = 'play_arrow';
            } else {
                refreshSeconds = 60;
                if (countdownEl) countdownEl.innerText = '60s';
                if (refreshIcon) refreshIcon.innerText = 'sync';
            }
        };

        // 3. Fullscreen Kiosk Display Toggle
        window.toggleFullscreen = function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Error attempting fullscreen:', err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        };

        // 4. Chart 1: Line Chart Likuiditas 12 Bulan
        document.addEventListener('DOMContentLoaded', function() {
            const ctxLikuiditas = document.getElementById('chartLikuiditas12Bulan');
            if (ctxLikuiditas) {
                new Chart(ctxLikuiditas, {
                    type: 'line',
                    data: {
                        labels: @json($chart12Bulan['labels']),
                        datasets: [
                            {
                                label: 'Total BKU Bendahara (Rp)',
                                data: @json($chart12Bulan['bku']),
                                borderColor: '#0284c7',
                                backgroundColor: 'rgba(2, 132, 199, 0.08)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            },
                            {
                                label: 'Total Saldo Bank Kalsel (Rp)',
                                data: @json($chart12Bulan['bank']),
                                borderColor: '#10b981',
                                backgroundColor: 'transparent',
                                borderWidth: 3,
                                borderDash: [4, 4],
                                tension: 0.35,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: { family: 'monospace', size: 10 },
                                    callback: function(val) {
                                        if (val >= 1000000000) return (val / 1000000000).toFixed(1) + ' M';
                                        if (val >= 1000000) return (val / 1000000).toFixed(0) + ' Jt';
                                        return val;
                                    }
                                },
                                grid: { color: 'rgba(200, 200, 200, 0.2)' }
                            },
                            x: {
                                ticks: { font: { weight: 'bold', size: 11 } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // 5. Chart 2: Donut Chart Kepatuhan
            const ctxDonut = document.getElementById('chartDonutKepatuhan');
            if (ctxDonut) {
                new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: ['KLOP', 'Dalam Proses', 'Selisih', 'Belum Lapor'],
                        datasets: [{
                            data: [{{ $countKlop }}, {{ $countDraft }}, {{ $countSelisih }}, {{ $countBelumLapor }}],
                            backgroundColor: ['#10b981', '#0ea5e9', '#f43f5e', '#fbbf24'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = {{ max(1, $totalSkpdCount) }};
                                        const pct = ((context.parsed / total) * 100).toFixed(1);
                                        return `${context.label}: ${context.parsed} SKPD (${pct}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '72%'
                    }
                });
            }
        });
    </script>
</x-app-layout>
