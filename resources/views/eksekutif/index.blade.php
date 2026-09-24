<x-app-layout>
    <!-- Top Executive Header Bar (Clean Institutional Theme) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-xs mb-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
            <!-- Branding & Title -->
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[26px]">query_stats</span>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            Pusat Komando Eksekutif
                        </span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Pemantauan Aktif
                        </span>
                    </div>
                    <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">
                        Tinjauan Eksekutif Rekonsiliasi Kas Daerah
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5 flex-wrap">
                        <span>Pemerintah Kabupaten Tapin</span>
                        <span>&bull;</span>
                        <span>Tahun Anggaran <strong class="text-slate-700 font-semibold">{{ $tahunAktif }}</strong></span>
                        <span>&bull;</span>
                        <span>Periode Pelaporan <strong class="text-slate-900 font-semibold">{{ $namaBulan[$bulanAktif - 1] }}</strong></span>
                    </p>
                </div>
            </div>

            <!-- Controls (Clock, Refresh, Fullscreen, Print) -->
            <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-start lg:justify-end">
                <!-- Live WITA Clock -->
                <div class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 flex items-center gap-2 font-mono text-xs">
                    <span class="material-symbols-outlined text-slate-400 text-[18px]">schedule</span>
                    <div class="text-left">
                        <div class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold leading-tight">WITA</div>
                        <div id="liveClock" class="font-bold text-slate-700 leading-tight">00:00:00 WITA</div>
                    </div>
                </div>

                <!-- Auto-Refresh Toggle -->
                <div class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 flex items-center gap-2 font-mono text-xs">
                    <button id="toggleRefreshBtn" onclick="toggleAutoRefresh()" class="text-slate-400 hover:text-slate-700 transition-colors cursor-pointer" title="Jeda / Lanjutkan Auto-Refresh">
                        <span id="refreshIcon" class="material-symbols-outlined text-[18px]">sync</span>
                    </button>
                    <div class="text-left">
                        <div class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold leading-tight">Pembaruan</div>
                        <div id="countdownText" class="font-bold text-slate-700 leading-tight">60s</div>
                    </div>
                </div>

                <!-- Fullscreen Mode Button -->
                <button onclick="toggleFullscreen()" class="px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-xs cursor-pointer" title="Tampilan Layar Penuh">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">fullscreen</span>
                    <span class="hidden sm:inline">Layar Penuh</span>
                </button>

                <!-- Cetak Briefing Eksekutif -->
                <a href="{{ route('eksekutif.cetak-brief', ['bulan' => $bulanAktif]) }}" target="_blank" class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold flex items-center gap-1.5 shadow-xs transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    <span>Cetak Ringkasan Eksekutif</span>
                </a>
            </div>
        </div>

        <!-- Month Quick Filter Bar -->
        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                <span class="material-symbols-outlined text-slate-400 text-[18px]">calendar_month</span>
                <span>Pilih Periode Bulan:</span>
            </div>
            <div class="inline-flex bg-slate-100 p-1 rounded-xl border border-slate-200/80 overflow-x-auto max-w-full">
                @foreach($namaBulan as $idx => $mName)
                    @php $mNum = $idx + 1; @endphp
                    <a href="{{ route('eksekutif.index', ['bulan' => $mNum]) }}"
                        class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap {{ $bulanAktif == $mNum ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 font-medium' }}">
                        {{ substr($mName, 0, 3) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 1. BARIS KPI MAKRO (4 KARTU BERSIH & FORMAL) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Likuiditas Kas Pemda di Bank Kalsel -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Likuiditas Kas di SKPD</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">account_balance</span>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight font-mono">
                        Rp {{ number_format($totalBank, 2, ',', '.') }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Saldo Rekening Bank Kalsel seluruh SKPD</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">BKU: Rp {{ number_format($totalBku, 2, ',', '.') }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $totalSelisih == 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    {{ $totalSelisih == 0 ? 'Sesuai' : 'Ada Selisih' }}
                </span>
            </div>
        </div>

        <!-- Card 2: Indeks Kepatuhan Rekonsiliasi Daerah -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Indeks Kepatuhan Pemda</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight font-mono">
                        {{ $kepatuhanRate }}%
                    </h3>
                    <span class="text-xs font-medium text-slate-500">({{ $countKlop }} / {{ $totalSkpdCount }} SKPD)</span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                    <div class="bg-emerald-600 h-1.5 rounded-full transition-all duration-700" style="width: {{ min(100, $kepatuhanRate) }}%"></div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Belum Klop: <strong class="text-slate-700 font-semibold">{{ $totalSkpdCount - $countKlop }}</strong> SKPD</span>
                <span class="font-semibold text-slate-600">Target: 100%</span>
            </div>
        </div>

        <!-- Card 3: Akumulasi Selisih Kas -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Akumulasi Selisih Kas</span>
                    <div class="w-8 h-8 rounded-lg {{ $totalSelisih == 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">{{ $totalSelisih == 0 ? 'check_circle' : 'warning' }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-bold {{ $totalSelisih == 0 ? 'text-slate-900' : 'text-rose-700' }} tracking-tight font-mono">
                        Rp {{ number_format($totalSelisih, 2, ',', '.') }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $totalSelisih == 0 ? 'Saldo fisik klop sempurna dengan rekening bank' : 'Ditemukan selisih pada ' . $countSelisih . ' SKPD' }}
                    </p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Jumlah SKPD Selisih:</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $countSelisih == 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    {{ $countSelisih }} SKPD
                </span>
            </div>
        </div>

        <!-- Card 4: Ketepatan Waktu Lapor -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ketepatan Waktu Lapor</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight font-mono">
                        {{ $timelinessRate }}%
                    </h3>
                    <span class="text-xs font-medium text-slate-500">Pelaporan &le; tgl 10</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                    <div class="bg-slate-700 h-1.5 rounded-full transition-all duration-700" style="width: {{ min(100, $timelinessRate) }}%"></div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Kesiapan Berkas BPK:</span>
                <span class="font-semibold text-slate-700 font-mono">{{ $auditReadinessRate }}% Lengkap</span>
            </div>
        </div>
    </div>

    <!-- 2. DECISION SUPPORT SYSTEM: TELAAH & REKOMENDASI KEBIJAKAN PIMPINAN DAERAH -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs mb-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3.5 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">psychology</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Telaah & Rekomendasi Kebijakan Pimpinan Daerah</h2>
                    <p class="text-xs text-slate-500">Hasil analisis sistem cerdas terhadap rekonsiliasi kas periode {{ $namaBulan[$bulanAktif - 1] }} {{ $tahunAktif }}</p>
                </div>
            </div>
            <span class="text-[11px] font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded hidden sm:inline">
                Sistem Pendukung Keputusan (DSS)
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
            @foreach($rekomendasiKebijakan as $rek)
                @php
                    $cardTheme = match($rek['tipe']) {
                        'danger' => 'border-rose-200 bg-rose-50/50',
                        'warning' => 'border-amber-200 bg-amber-50/50',
                        'success' => 'border-emerald-200 bg-emerald-50/50',
                        default => 'border-blue-200 bg-blue-50/50',
                    };
                    $titleColor = match($rek['tipe']) {
                        'danger' => 'text-rose-950',
                        'warning' => 'text-amber-950',
                        'success' => 'text-emerald-950',
                        default => 'text-blue-950',
                    };
                    $iconColor = match($rek['tipe']) {
                        'danger' => 'text-rose-600',
                        'warning' => 'text-amber-600',
                        'success' => 'text-emerald-600',
                        default => 'text-blue-600',
                    };
                @endphp
                <div class="p-4 rounded-lg border {{ $cardTheme }} flex items-start gap-3">
                    <span class="material-symbols-outlined {{ $iconColor }} text-[20px] shrink-0 mt-0.5">{{ $rek['icon'] }}</span>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold {{ $titleColor }} uppercase tracking-wider">{{ $rek['judul'] }}</h3>
                        <p class="text-xs leading-relaxed text-slate-600">{{ $rek['pesan'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. GRAFIK ANALITIK: TREN LIKUIDITAS 12 BULAN & KOMPOSISI KEPATUHAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Grafik 1: Tren Likuiditas 12 Bulan (Line Chart BKU vs Bank) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue-600 text-[18px]">show_chart</span>
                        Tren Likuiditas Kas Pemda 12 Bulan (TA {{ $tahunAktif }})
                    </h3>
                    <p class="text-xs text-slate-500">Perbandingan Saldo Kas BKU Bendahara vs Saldo Rekening Bank Kalsel</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1 font-semibold text-blue-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> BKU
                    </span>
                    <span class="flex items-center gap-1 font-semibold text-emerald-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span> Bank
                    </span>
                </div>
            </div>
            <div class="h-64 w-full relative">
                <canvas id="chartLikuiditas12Bulan"></canvas>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Status SKPD Bulan Ini (Donut Chart) -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-3 mb-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-slate-600 text-[18px]">pie_chart</span>
                    Distribusi Kepatuhan SKPD ({{ $namaBulan[$bulanAktif - 1] }})
                </h3>
                <p class="text-xs text-slate-500">Status dari total {{ $totalSkpdCount }} SKPD aktif</p>
            </div>
            <div class="h-48 w-full relative flex items-center justify-center">
                <canvas id="chartDonutKepatuhan"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 shrink-0"></span>
                    <span class="text-slate-600">Klop: <strong class="text-slate-800">{{ $countKlop }}</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0"></span>
                    <span class="text-slate-600">Proses: <strong class="text-slate-800">{{ $countDraft }}</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 shrink-0"></span>
                    <span class="text-slate-600">Selisih: <strong class="text-slate-800">{{ $countSelisih }}</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                    <span class="text-slate-600">Belum: <strong class="text-slate-800">{{ $countBelumLapor }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. EARLY WARNING SYSTEM (EWS): TOP 5 TERTIB vs BOTTOM 5 ATENSI -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top 5 Disiplin -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Top 5 SKPD Paling Tertib & Disiplin (TA {{ $tahunAktif }})</h3>
                </div>
                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Kinerja Terbaik</span>
            </div>
            <div class="space-y-2">
                @foreach($top5Skpd as $idx => $s)
                    <div class="p-3 rounded-lg bg-slate-50/70 border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                                {{ $idx + 1 }}
                            </span>
                            <div class="truncate">
                                <p class="font-semibold text-slate-900 truncate">{{ $s->nama }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $s->kode }} &bull; Bendahara: {{ $s->nama_bendahara ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold font-mono text-[11px]">
                                Skor {{ $s->skor_kinerja }}
                            </span>
                            <span class="text-[10px] text-slate-500 block mt-0.5">{{ $s->verified_count }} bln Tertib</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom 5 Butuh Perhatian (EWS) -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-700 border border-rose-100 flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Early Warning System: 5 SKPD Butuh Atensi Pimpinan</h3>
                </div>
                <span class="text-[10px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">Perhatian Khusus</span>
            </div>
            <div class="space-y-2">
                @foreach($bottom5Skpd as $idx => $s)
                    <div class="p-3 rounded-lg bg-rose-50/30 border border-rose-200/60 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-800 font-bold text-xs flex items-center justify-center shrink-0">
                                !
                            </span>
                            <div class="truncate">
                                <p class="font-semibold text-slate-900 truncate">{{ $s->nama }}</p>
                                <p class="text-[11px] text-rose-700 font-mono">{{ $s->kode }} &bull; Selisih Kas: {{ $s->selisih_count }}x</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 flex items-center gap-2">
                            <div>
                                <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 font-semibold font-mono text-[11px]">
                                    Skor {{ $s->skor_kinerja }}
                                </span>
                                <span class="text-[10px] text-rose-600 block mt-0.5">{{ $s->verified_count }} bln lapor</span>
                            </div>
                            @if($s->no_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($s->no_whatsapp, '08') ? '628' . substr($s->no_whatsapp, 2) : $s->no_whatsapp) }}?text={{ urlencode('Pemberitahuan Pimpinan BKAD Tapin: Mohon percepatan penyelesaian rekonsiliasi kas SKPD ' . $s->nama . '.') }}"
                                   target="_blank" class="px-2 py-1 rounded bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-300 font-medium text-xs flex items-center gap-1 shadow-xs transition-colors" title="Kirim Teguran via WhatsApp">
                                    <span class="material-symbols-outlined text-[14px]">chat</span>
                                    <span>WA</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 5. MATRIKS STATUS SELURUH SKPD KABUPATEN TAPIN -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs mb-8" x-data="{
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
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-600 text-[20px]">grid_view</span>
                    Matriks Status Rekonsiliasi SKPD Kabupaten Tapin ({{ $totalSkpdCount }} Instansi)
                </h3>
                <p class="text-xs text-slate-500">Status rekonsiliasi kas periode <strong class="text-slate-700 font-semibold">{{ $namaBulan[$bulanAktif - 1] }} {{ $tahunAktif }}</strong></p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input type="text" x-model="search" placeholder="Cari nama atau kode SKPD..."
                        class="w-full h-9 pl-9 pr-3 rounded-lg border border-slate-200 bg-white text-xs text-slate-800 focus:border-slate-400 focus:ring-0 outline-none placeholder:text-slate-400">
                </div>

                <!-- Filter Chips -->
                <div class="flex items-center gap-1 overflow-x-auto pb-1">
                    <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-slate-900 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70 border border-slate-200/80'" class="px-2.5 py-1.5 rounded-lg text-xs transition-colors">
                        Semua
                    </button>
                    <button @click="statusFilter = 'klop'" :class="statusFilter === 'klop' ? 'bg-emerald-700 text-white font-semibold' : 'bg-slate-100 text-emerald-800 hover:bg-emerald-50 border border-slate-200/80'" class="px-2.5 py-1.5 rounded-lg text-xs transition-colors">
                        Klop ({{ $countKlop }})
                    </button>
                    <button @click="statusFilter = 'draft'" :class="statusFilter === 'draft' ? 'bg-blue-700 text-white font-semibold' : 'bg-slate-100 text-blue-800 hover:bg-blue-50 border border-slate-200/80'" class="px-2.5 py-1.5 rounded-lg text-xs transition-colors">
                        Proses ({{ $countDraft }})
                    </button>
                    <button @click="statusFilter = 'selisih'" :class="statusFilter === 'selisih' ? 'bg-rose-700 text-white font-semibold' : 'bg-slate-100 text-rose-800 hover:bg-rose-50 border border-slate-200/80'" class="px-2.5 py-1.5 rounded-lg text-xs transition-colors">
                        Selisih ({{ $countSelisih }})
                    </button>
                    <button @click="statusFilter = 'belum'" :class="statusFilter === 'belum' ? 'bg-amber-600 text-white font-semibold' : 'bg-slate-100 text-amber-800 hover:bg-amber-50 border border-slate-200/80'" class="px-2.5 py-1.5 rounded-lg text-xs transition-colors">
                        Belum ({{ $countBelumLapor }})
                    </button>
                </div>
            </div>
        </div>

        <!-- SKPD Matrix Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5">
            @foreach($skpdMatrix as $item)
                @php
                    $cardBorder = match($item['status']) {
                        'klop' => 'border-slate-200 hover:border-emerald-300 bg-white',
                        'draft' => 'border-slate-200 hover:border-blue-300 bg-white',
                        'selisih' => 'border-rose-200 bg-rose-50/20 hover:border-rose-400',
                        default => 'border-slate-200 hover:border-amber-300 bg-white',
                    };
                    $badgeStyle = match($item['status']) {
                        'klop' => 'bg-emerald-50 text-emerald-700 border-emerald-200 font-semibold',
                        'draft' => 'bg-blue-50 text-blue-700 border-blue-200 font-semibold',
                        'selisih' => 'bg-rose-50 text-rose-700 border-rose-200 font-bold',
                        default => 'bg-amber-50 text-amber-700 border-amber-200 font-semibold',
                    };
                @endphp
                <div class="p-4 rounded-xl border {{ $cardBorder }} transition-all flex flex-col justify-between space-y-3"
                    x-show="matchesFilter('{{ $item['status'] }}') && matchesSearch('{{ addslashes($item['kode'] . ' ' . $item['nama'] . ' ' . $item['bendahara']) }}')">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="font-mono text-[10px] font-bold px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-700">
                                {{ $item['kode'] }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase border {{ $badgeStyle }}">
                                {{ $item['status_label'] }}
                            </span>
                        </div>
                        <h4 class="text-xs font-semibold text-slate-900 line-clamp-2 leading-snug" title="{{ $item['nama'] }}">
                            {{ $item['nama'] }}
                        </h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Bendahara: {{ $item['bendahara'] }}</p>
                    </div>

                    <!-- Nominal Financial Details -->
                    <div class="pt-2.5 border-t border-slate-100 space-y-1 font-mono text-xs">
                        @if($item['status'] !== 'belum')
                            <div class="flex items-center justify-between text-slate-600">
                                <span>BKU:</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format($item['bku'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Bank:</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format($item['bank'], 2, ',', '.') }}</span>
                            </div>
                            @if($item['selisih'] > 0)
                                <div class="flex items-center justify-between text-rose-700 font-bold bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">
                                    <span>Selisih:</span>
                                    <span>Rp {{ number_format($item['selisih'], 2, ',', '.') }}</span>
                                </div>
                            @endif
                        @else
                            <div class="py-2 text-center text-slate-400 text-xs italic">
                                Belum ada berkas rekonsiliasi
                            </div>
                        @endif
                    </div>

                    <!-- Footer Info & Direct WA Action -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $item['status'] !== 'belum' ? 'Berkas: ' . $item['berkas_persen'] . '%' : 'Menunggu Pelaporan' }}</span>
                        @if($item['no_wa'])
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($item['no_wa'], '08') ? '628' . substr($item['no_wa'], 2) : $item['no_wa']) }}?text={{ urlencode('Halo Bendahara ' . $item['nama'] . ', mohon konfirmasi status rekonsiliasi kas periode ' . $namaBulan[$bulanAktif - 1] . ' ' . $tahunAktif . '.') }}"
                               target="_blank" class="text-emerald-700 hover:text-emerald-800 font-semibold flex items-center gap-1" title="Hubungi via WhatsApp">
                                <span class="material-symbols-outlined text-[14px]">chat</span>
                                <span>WA</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chart.js & Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Live WITA Clock
        function updateWitaClock() {
            const now = new Date();
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
                if (countdownEl) countdownEl.innerText = 'JEDA';
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

        // 4. Chart 1: Line Chart Likuiditas 12 Bulan (Clean Corporate Style)
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
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.25,
                                pointRadius: 3.5,
                                pointHoverRadius: 5.5,
                            },
                            {
                                label: 'Total Saldo Bank Kalsel (Rp)',
                                data: @json($chart12Bulan['bank']),
                                borderColor: '#059669',
                                backgroundColor: 'transparent',
                                borderWidth: 2.5,
                                borderDash: [4, 4],
                                tension: 0.25,
                                pointRadius: 3.5,
                                pointHoverRadius: 5.5,
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
                                grid: { color: 'rgba(226, 232, 240, 0.7)' }
                            },
                            x: {
                                ticks: { font: { weight: '600', size: 11 } },
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
                        labels: ['Klop', 'Dalam Proses', 'Selisih', 'Belum Lapor'],
                        datasets: [{
                            data: [{{ $countKlop }}, {{ $countDraft }}, {{ $countSelisih }}, {{ $countBelumLapor }}],
                            backgroundColor: ['#059669', '#2563eb', '#e11d48', '#d97706'],
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
