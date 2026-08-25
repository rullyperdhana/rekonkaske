<x-app-layout>

    <div class="mb-8">
        <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2">Tinjauan Rekonsiliasi</h2>
        @if($summary['has_data'])
            <p class="text-body-md font-body-md text-on-surface-variant">{{ $summary['info'] }}</p>
        @else
            <p class="text-body-md font-body-md text-on-surface-variant">Belum ada data rekonsiliasi</p>
        @endif
    </div>

    @if($missingMonth)
    <div class="mb-8 p-4 bg-error-container text-on-error-container rounded-lg border border-error-container/50 flex items-center gap-3">
        <span class="material-symbols-outlined">warning</span>
        <div>
            <h3 class="font-bold text-label-md">Perhatian!</h3>
            <p class="text-body-sm">Anda belum mengisi atau menyelesaikan rekonsiliasi untuk bulan <strong>{{ $missingMonth }}</strong>. Harap segera melengkapinya.</p>
        </div>
    </div>
    @endif
    
    <!-- Modern Pill Tabs Navigation -->
    <div class="mb-8 overflow-x-auto pb-2 custom-scrollbar">
        <div class="inline-flex bg-slate-100/80 p-1.5 rounded-xl border border-slate-200 min-w-max">
            <button onclick="switchTab('tab-overview')" id="btn-tab-overview" class="tab-btn active-tab relative whitespace-nowrap py-2.5 px-5 rounded-lg font-bold text-sm transition-all duration-300 bg-white text-blue-600 shadow-sm border border-slate-200">
                <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">dashboard</span> Ringkasan Utama</div>
            </button>
            <button onclick="switchTab('tab-status')" id="btn-tab-status" class="tab-btn relative whitespace-nowrap py-2.5 px-5 rounded-lg font-medium text-sm transition-all duration-300 text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 border border-transparent">
                <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">list_alt</span> Status SKPD</div>
            </button>
            @if(isset($topSkpds) || isset($advChartData))
            <button onclick="switchTab('tab-analytics')" id="btn-tab-analytics" class="tab-btn relative whitespace-nowrap py-2.5 px-5 rounded-lg font-medium text-sm transition-all duration-300 text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 border border-transparent">
                <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">monitoring</span> Analitik Eksekutif</div>
            </button>
            <button onclick="switchTab('tab-activity')" id="btn-tab-activity" class="tab-btn relative whitespace-nowrap py-2.5 px-5 rounded-lg font-medium text-sm transition-all duration-300 text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 border border-transparent">
                <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">history</span> Aktivitas & Peringkat</div>
            </button>
            @endif
        </div>
    </div>

    <!-- TAB: OVERVIEW -->
    <div id="tab-overview" class="tab-content block animate-[fadeIn_0.3s_ease-in-out]">

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-{{ isset($kepatuhanData) ? '4' : '3' }} gap-6 mb-8">
        
        <!-- CARD 1: Total BKU -->
        <div class="relative overflow-hidden bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-hover:text-blue-500 transition-colors">Total BKU (SIPANDA)</h3>
                    <p class="text-2xl font-black text-slate-800 tracking-tight">
                        Rp {{ $summary['has_data'] ? number_format($summary['bku'], 2, ',', '.') : '0,00' }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shrink-0">
                    <span class="material-symbols-outlined text-[24px]">account_balance_wallet</span>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm text-slate-500 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                Saldo BKU Bendahara
            </div>
        </div>

        <!-- CARD 2: Total Bank -->
        <div class="relative overflow-hidden bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-hover:text-emerald-500 transition-colors">Total Bank Kalsel</h3>
                    <p class="text-2xl font-black text-slate-800 tracking-tight">
                        Rp {{ $summary['has_data'] ? number_format($summary['bank'], 2, ',', '.') : '0,00' }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shrink-0">
                    <span class="material-symbols-outlined text-[24px]">account_balance</span>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm text-slate-500 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Saldo Rekening Koran
            </div>
        </div>

        <!-- CARD 3: Status Rekonsiliasi -->
        @php
            $statusColor = $summary['is_matched'] ? 'emerald' : 'rose';
            $statusGradient = $summary['is_matched'] ? 'from-emerald-500 to-green-500' : 'from-rose-500 to-red-500';
            $statusBg = $summary['is_matched'] ? 'bg-emerald-50' : 'bg-rose-50';
            $statusText = $summary['is_matched'] ? 'text-emerald-700' : 'text-rose-700';
            $statusIcon = $summary['is_matched'] ? 'check_circle' : 'warning';
        @endphp
        <div class="relative overflow-hidden bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-{{$statusColor}}-500/10 rounded-full blur-2xl group-hover:bg-{{$statusColor}}-500/20 transition-all duration-500"></div>
            
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-hover:text-{{$statusColor}}-500 transition-colors">Status Rekonsiliasi</h3>
                    <div class="flex items-center gap-3 mt-1">
                        <p class="text-2xl font-black text-slate-800 tracking-tight">
                            {{ $summary['has_data'] ? ($summary['is_matched'] ? '100%' : 'Selisih') : '-' }}
                        </p>
                        @if($summary['has_data'])
                            <span class="{{$statusBg}} {{$statusText}} px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1 shadow-sm">
                                <span class="material-symbols-outlined text-[14px]">{{$statusIcon}}</span> 
                                {{ $summary['is_matched'] ? 'Matched' : 'Discrepancy' }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-3 bg-gradient-to-br {{$statusGradient}} rounded-xl text-white shadow-lg shadow-{{$statusColor}}-500/30 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shrink-0">
                    <span class="material-symbols-outlined text-[24px]">fact_check</span>
                </div>
            </div>
            
            @if($summary['has_data'])
            <div class="mt-5 w-full bg-slate-100 h-2 rounded-full overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r {{$statusGradient}} h-full w-full rounded-full relative overflow-hidden">
                    @if(!$summary['is_matched'])
                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    @endif
                </div>
            </div>
            @else
            <div class="mt-4 flex items-center gap-2 text-sm text-slate-400 font-medium">
                Belum ada data
            </div>
            @endif
        </div>
        
        <!-- CARD 4: Kepatuhan -->
        @if(isset($kepatuhanData))
        <div class="relative overflow-hidden bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 group flex flex-col">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-violet-500/10 rounded-full blur-2xl group-hover:bg-violet-500/20 transition-all duration-500"></div>
            
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-hover:text-violet-500 transition-colors relative z-10 flex items-center justify-between">
                Kepatuhan (Bulan {{ $kepatuhanData['target_bulan'] }})
                <span class="material-symbols-outlined text-[16px] text-violet-400 group-hover:animate-spin">data_usage</span>
            </h3>
            <div class="flex-grow flex flex-col items-center justify-center relative w-full h-28 z-10 my-2">
                <canvas id="kepatuhanChart"></canvas>
            </div>
            <div class="text-center relative z-10">
                <p class="text-xl font-black text-slate-800 tracking-tight">
                    {{ $kepatuhanData['persentase'] }}%
                </p>
                <p class="text-[11px] font-medium text-slate-500 mt-0.5">({{ $kepatuhanData['patuh'] }} dari {{ $kepatuhanData['total_skpd'] }} Lapor)</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Removed Chart Analytics from here, moved to Main Content Area -->

    <!-- Chart Analytics -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Tren Saldo Kas Daerah ({{ $tahunAktif }})</h3>
        <p class="text-body-md font-body-md text-on-surface-variant mb-6">Perbandingan Total Saldo Buku Kas Umum vs Rekening Koran Bank seluruh SKPD.</p>
        <div class="w-full relative h-72">
            <canvas id="rekonChart"></canvas>
        </div>
    </div>
    
    @if(isset($advChartData))
    <!-- Tren Penyerapan Kas (Penerimaan vs Pengeluaran) -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Tren Penyerapan Kas Daerah (Penerimaan vs Belanja) - {{ $tahunAktif }}</h3>
        <p class="text-body-md font-body-md text-on-surface-variant mb-6">Perbandingan antara Kas Masuk dan Keluar dari semua SKPD.</p>
        <div class="w-full relative h-72">
            <canvas id="advPenyerapanKas"></canvas>
        </div>
    </div>
    @endif
    
    <!-- Discrepancy Summary Dipindahkan ke Menu Laporan -->

    </div> <!-- END TAB OVERVIEW -->

    <!-- TAB: STATUS -->
    <div id="tab-status" class="tab-content hidden animate-[fadeIn_0.3s_ease-in-out]">
    
    <!-- Status Rekonsiliasi Per SKPD -->
    <!-- Status Rekonsiliasi Per SKPD -->
    @php
        $bulanSingkat = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    @endphp
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-8 relative overflow-hidden">
        <!-- Decorative blob -->
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex justify-between items-end mb-6 relative z-10">
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">domain</span>
                    Status Rekonsiliasi Per SKPD — {{ $tahunAktif }}
                </h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Pantau kelengkapan laporan dari total {{ count($skpdRekonStatus) }} SKPD</p>
            </div>
            
            <div class="hidden sm:flex items-center gap-4 bg-slate-50 px-4 py-2 rounded-xl border border-slate-200">
                <span class="flex items-center gap-1.5 text-xs font-bold text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Lengkap</span>
                <span class="flex items-center gap-1.5 text-xs font-bold text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Sebagian</span>
                <span class="flex items-center gap-1.5 text-xs font-bold text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Kosong</span>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-sm">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Instansi SKPD</th>
                        @for($i = 1; $i <= 12; $i++)
                            <th class="px-2 py-4 text-[11px] font-bold text-slate-500 uppercase text-center w-10" title="Bulan {{ $i }}">{{ $bulanSingkat[$i-1] }}</th>
                        @endfor
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100 bg-white">
                    @foreach($skpdRekonStatus as $skpdStatus)
                    <tr class="hover:bg-blue-50/60 transition-colors group">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @php
                                    $namaBersih = preg_replace('/[^A-Za-z]/', '', $skpdStatus['nama']);
                                    $inisial = $namaBersih ? strtoupper(substr($namaBersih, 0, 1)) : '?';
                                @endphp
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0 shadow-inner group-hover:scale-110 transition-transform">
                                    {{ $inisial }}
                                </div>
                                <span class="font-bold text-slate-700 group-hover:text-blue-700 transition-colors">{{ $skpdStatus['nama'] }}</span>
                            </div>
                        </td>
                        @for($i = 1; $i <= 12; $i++)
                            <td class="px-2 py-3 text-center">
                                @if(in_array($i, $skpdStatus['bulan_list']))
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 shadow-sm" title="Bulan {{ $i }} Selesai">
                                        <span class="material-symbols-outlined text-[14px] font-bold">check</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-50 text-slate-300" title="Bulan {{ $i }} Belum">
                                        <span class="material-symbols-outlined text-[14px]">remove</span>
                                    </span>
                                @endif
                            </td>
                        @endfor
                        <td class="px-5 py-3 text-right">
                            @if($skpdStatus['bulan_selesai'] >= 12)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981]"></span> Lengkap
                                </span>
                            @elseif($skpdStatus['bulan_selesai'] > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_5px_#f59e0b]"></span> Sebagian
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shadow-[0_0_5px_#f43f5e]"></span> Belum Lapor
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5 flex justify-end">
            {{ $skpdsPaginated->links() }}
        </div>
    </div>

    </div> <!-- END TAB STATUS -->

    <!-- TAB: ANALYTICS -->
    <div id="tab-analytics" class="tab-content hidden animate-[fadeIn_0.3s_ease-in-out]">

    <!-- Executive Analytics & Leaderboard Section -->
    @if(isset($topSkpds) && count($topSkpds) > 0)
    <div class="mt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 rounded-xl shadow-md border border-outline-variant relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="z-10">
            <h2 class="text-lg font-bold flex flex-wrap items-center gap-2">
                <span class="material-symbols-outlined text-amber-400" data-weight="fill">analytics</span>
                <span>Rapor Kepatuhan & Analisis Kedisiplinan Waktu (Timeliness Score)</span>
                <span class="text-[11px] px-2.5 py-0.5 bg-amber-400/20 text-amber-300 rounded-full font-mono border border-amber-400/30">Executive EWS</span>
            </h2>
            <p class="text-body-sm text-slate-300 mt-1 leading-relaxed">Evaluasi kedisiplinan pelaporan rekon, bobot ketepatan waktu lapor bulanan, serta deteksi dini tunggakan & selisih SKPD di sistem SiReKa.</p>
        </div>
        <a href="{{ route('dashboard.cetak-rapor-kepatuhan') }}" target="_blank" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-95 text-body-sm shrink-0 z-10">
            <span class="material-symbols-outlined text-lg" data-weight="fill">print</span>
            <span>Cetak Rapor Eksekutif (PDF)</span>
        </a>
    </div>
    @endif

    <!-- Advanced Analytics Charts (Hanya Admin) -->
    @if(isset($advChartData))
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- 1. Pie Chart: Status Bulan Ini -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm">
            <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Komposisi Kepatuhan (Bulan {{ $advChartData['target_month_status'] }})</h3>
            <div class="relative w-full h-48 mt-4">
                <canvas id="advPieStatus"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 text-[11px] text-on-surface-variant text-center">
                Status kelengkapan dokumen SKPD
            </div>
        </div>
        
        <!-- 2. Bar Chart: Kedisiplinan Waktu (Jan - Des) -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm">
            <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Tren Kedisiplinan Pelaporan ({{ $tahunAktif }})</h3>
            <div class="relative w-full h-48 mt-4">
                <canvas id="advBarDisiplin"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 text-[11px] text-on-surface-variant text-center">
                Tepat waktu (Tgl 1-10) vs Terlambat (> Tgl 10)
            </div>
        </div>

        <!-- 3. Line Chart: Tren Selisih Kas (Jan - Des) -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm">
            <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Tren Selisih Kas Daerah ({{ $tahunAktif }})</h3>
            <div class="relative w-full h-48 mt-4">
                <canvas id="advLineSelisih"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 text-[11px] text-on-surface-variant text-center">
                Frekuensi SKPD yang mengalami selisih
            </div>
        </div>
    </div>
    @endif

    </div> <!-- END TAB ANALYTICS -->

    <!-- TAB: ACTIVITY -->
    <div id="tab-activity" class="tab-content hidden animate-[fadeIn_0.3s_ease-in-out]">

    <div class="grid grid-cols-1 {{ isset($topSkpds) && count($topSkpds) > 0 ? 'lg:grid-cols-3' : 'lg:grid-cols-1' }} gap-6 mt-6">
        <!-- Top 5 Leaderboard -->
        @if(isset($topSkpds) && count($topSkpds) > 0)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-outline-variant/60 pb-3 mb-4">
                    <h3 class="font-bold text-base text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-500" data-weight="fill">workspace_premium</span>
                        <span>Top 5 Disiplin Rekon</span>
                    </h3>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Teratas</span>
                </div>
                <div class="space-y-3.5">
                    @foreach($topSkpds as $index => $topSkpd)
                    <div class="flex items-center justify-between gap-2 p-2 rounded-lg hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center font-extrabold text-xs shrink-0
                                {{ $index == 0 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 
                                   ($index == 1 ? 'bg-slate-200 text-slate-800 border border-slate-300' : 
                                   ($index == 2 ? 'bg-orange-100 text-orange-800 border border-orange-300' : 'bg-surface-container-high text-on-surface-variant')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-body-sm font-bold text-on-surface truncate" title="{{ $topSkpd->nama }}">{{ $topSkpd->nama }}</p>
                                <p class="text-[11px] text-emerald-600 font-semibold">{{ $topSkpd->verified_count ?? $topSkpd->transaksis_count }} bulan verified • Nihil selisih</p>
                            </div>
                        </div>
                        <div class="shrink-0 text-right">
                            <span class="text-xs font-black px-2 py-1 bg-emerald-100 text-emerald-800 rounded-md shadow-sm" title="Timeliness Score">
                                {{ $topSkpd->timeliness_score ?? 100 }} pt
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 text-[11px] text-on-surface-variant text-center italic">
                Skor tinggi berdasarkan kecepatan kirim & keakuratan rekon
            </div>
        </div>

        <!-- Bottom 5 Early Warning System (EWS) -->
        <div class="bg-surface-container-lowest border border-rose-200 rounded-xl p-5 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between border-b border-rose-100 pb-3 mb-4">
                    <h3 class="font-bold text-base text-rose-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-rose-500" data-weight="fill">warning_amber</span>
                        <span>Perlu Perhatian (EWS)</span>
                    </h3>
                    <span class="text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">Rawan / Tertinggal</span>
                </div>
                <div class="space-y-3.5">
                    @forelse($bottomSkpds ?? [] as $bIdx => $bottomSkpd)
                    <div class="flex items-center justify-between gap-2 p-2 rounded-lg bg-rose-50/40 hover:bg-rose-50 transition-colors">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-extrabold text-xs shrink-0 border border-rose-200">
                                !
                            </div>
                            <div class="min-w-0">
                                <p class="text-body-sm font-bold text-rose-950 truncate" title="{{ $bottomSkpd->nama }}">{{ $bottomSkpd->nama }}</p>
                                <p class="text-[11px] text-rose-600 font-medium">
                                    @if(($bottomSkpd->selisih_count ?? 0) > 0)
                                        ⚠️ Ada {{ $bottomSkpd->selisih_count }} selisih kas
                                    @else
                                        Baru {{ $bottomSkpd->verified_count ?? 0 }} dari 12 bulan verified
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="shrink-0 text-right">
                            <span class="text-xs font-bold px-2 py-1 bg-rose-200/80 text-rose-900 rounded-md shadow-sm" title="Timeliness Score Rendah">
                                {{ $bottomSkpd->timeliness_score ?? 30 }} pt
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-on-surface-variant text-sm py-4">Semua SKPD terpantau aman dan berperingkat baik!</div>
                    @endforelse
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-rose-100 text-[11px] text-rose-600 text-center font-medium">
                👉 Disarankan untuk melakukan supervisi & teguran WA
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-outline-variant/60 pb-3 mb-4">
                    <h3 class="font-bold text-base text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500" data-weight="fill">history</span>
                        <span>Aktivitas Terakhir</span>
                    </h3>
                    <span class="text-[11px] font-medium text-on-surface-variant">Real-time Log</span>
                </div>
                <div class="space-y-4 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px before:h-full before:w-0.5 before:bg-outline-variant/40 pl-2">
                    @forelse($recentActivities as $activity)
                    <div class="relative flex items-start gap-3.5">
                        <div class="bg-primary text-on-primary w-5 h-5 rounded-full flex items-center justify-center shrink-0 z-10 ring-4 ring-surface-container-lowest mt-0.5">
                            @if($activity->status_verifikasi == 'verified')
                                <span class="material-symbols-outlined text-[12px]" data-weight="fill">check_circle</span>
                            @else
                                <span class="w-2 h-2 bg-on-primary rounded-full"></span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-body-sm font-bold text-on-surface leading-tight">Data BKU {{ $namaBulan[$activity->periode_bulan - 1] }} {{ $activity->status_verifikasi == 'verified' ? 'Diverifikasi' : 'Diperbarui' }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5 truncate">{{ $activity->skpd->nama ?? 'Instansi' }} • Oleh: {{ $activity->user->name ?? 'Sistem' }}</p>
                            <span class="text-[10px] font-mono text-primary font-medium">{{ $activity->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-on-surface-variant text-sm py-8">
                        Belum ada aktivitas baru.
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/40 text-[11px] text-on-surface-variant text-center">
                Memilih pergerakan transaksi SiReKa
            </div>
        </div>
    </div>
    
    </div> <!-- END TAB ACTIVITY -->

    <!-- Tabs Javascript -->
    <script>
        function switchTab(tabId) {
            // Simpan state tab aktif ke sessionStorage
            sessionStorage.setItem('activeDashboardTab', tabId);

            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            const targetTab = document.getElementById(tabId);
            if(targetTab) {
                targetTab.classList.remove('hidden');
                targetTab.classList.add('block');
                
                // Trigger resize event so Chart.js can re-render properly inside previously hidden containers
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 10);
            }
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active-tab', 'bg-white', 'text-blue-600', 'shadow-sm', 'border-slate-200', 'font-bold');
                btn.classList.add('text-slate-500', 'hover:text-slate-800', 'hover:bg-slate-200/60', 'border-transparent', 'font-medium');
            });
            const activeBtn = document.getElementById('btn-' + tabId);
            if(activeBtn) {
                activeBtn.classList.remove('text-slate-500', 'hover:text-slate-800', 'hover:bg-slate-200/60', 'border-transparent', 'font-medium');
                activeBtn.classList.add('active-tab', 'bg-white', 'text-blue-600', 'shadow-sm', 'border-slate-200', 'font-bold');
            }
        }

        // Restore tab aktif saat halaman di-reload (misal: saat klik pagination)
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = sessionStorage.getItem('activeDashboardTab');
            if (activeTab && document.getElementById(activeTab)) {
                switchTab(activeTab);
            }
        });
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data for Bar Chart
            const chartData = @json($chartData);
            const ctxRekon = document.getElementById('rekonChart');
            if (ctxRekon) {
                new Chart(ctxRekon.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Total Saldo BKU',
                                data: chartData.bku,
                                backgroundColor: '#006B5E', // Primary color
                                borderRadius: 4,
                            },
                            {
                                label: 'Total Saldo Bank',
                                data: chartData.bank,
                                backgroundColor: '#4A635F', // Secondary color
                                borderRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: { font: { family: "'Inter', sans-serif", size: 12 } }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) { label += ': '; }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if(value >= 1000000000) return 'Rp ' + (value/1000000000).toFixed(1) + 'M';
                                        if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(0) + 'Jt';
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Data for Doughnut Chart (Kepatuhan)
            @if(isset($kepatuhanData))
            const ctxKepatuhan = document.getElementById('kepatuhanChart');
            if (ctxKepatuhan) {
                const patuh = {{ $kepatuhanData['patuh'] }};
                const belum = {{ $kepatuhanData['total_skpd'] - $kepatuhanData['patuh'] }};
                
                new Chart(ctxKepatuhan.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Sudah Lapor', 'Belum Lapor'],
                        datasets: [{
                            data: [patuh, belum],
                            backgroundColor: ['#8B5CF6', '#EDE9FE'], // Violet-500 and Violet-100
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ': ' + context.parsed + ' SKPD';
                                    }
                                }
                            }
                        }
                    }
                });
            }
            @endif
            // Advanced Executive Analytics Charts
            @if(isset($advChartData))
            const advData = @json($advChartData);
            
            // 1. Pie Chart (Status Bulan Terakhir)
            const ctxAdvPie = document.getElementById('advPieStatus');
            if(ctxAdvPie) {
                new Chart(ctxAdvPie.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Verified', 'Draft/Proses', 'Belum Lapor'],
                        datasets: [{
                            data: [advData.status_bulan.verified, advData.status_bulan.draft, advData.status_bulan.belum],
                            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                            borderWidth: 0,
                            hoverOffset: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 12, font: {size: 11} }
                            }
                        }
                    }
                });
            }

            // 2. Bar Chart (Kedisiplinan)
            const ctxAdvBar = document.getElementById('advBarDisiplin');
            if(ctxAdvBar) {
                new Chart(ctxAdvBar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [
                            {
                                label: 'Tepat Waktu',
                                data: advData.kedisiplinan.tepat_waktu,
                                backgroundColor: '#0284C7',
                                borderRadius: 3
                            },
                            {
                                label: 'Terlambat',
                                data: advData.kedisiplinan.terlambat,
                                backgroundColor: '#94A3B8',
                                borderRadius: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: {boxWidth: 12, font:{size: 11}} }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }

            // 3. Line Chart (Tren Selisih Kas)
            const ctxAdvLine = document.getElementById('advLineSelisih');
            if(ctxAdvLine) {
                new Chart(ctxAdvLine.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Jumlah Transaksi Selisih',
                            data: advData.selisih,
                            borderColor: '#DC2626',
                            backgroundColor: '#FECACA',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#DC2626'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }
            // 4. Bar Chart (Tren Penyerapan Kas)
            const ctxAdvPenyerapan = document.getElementById('advPenyerapanKas');
            if(ctxAdvPenyerapan) {
                new Chart(ctxAdvPenyerapan.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [
                            {
                                label: 'Total Penerimaan',
                                data: advData.penyerapan.penerimaan,
                                backgroundColor: '#10B981', // Emerald 500
                                borderRadius: 4,
                            },
                            {
                                label: 'Total Pengeluaran (Belanja)',
                                data: advData.penyerapan.pengeluaran,
                                backgroundColor: '#F43F5E', // Rose 500
                                borderRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: { font: { family: "'Inter', sans-serif", size: 12 } }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) { label += ': '; }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if(value >= 1000000000000) return 'Rp ' + (value/1000000000000).toFixed(1) + 'T';
                                        if(value >= 1000000000) return 'Rp ' + (value/1000000000).toFixed(1) + 'M';
                                        if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(0) + 'Jt';
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            @endif
            
            // Pop Out Pengumuman (SweetAlert)
            @if(isset($pengumumans) && $pengumumans->count() > 0)
            if (!sessionStorage.getItem('pengumuman_dibaca')) {
                let htmlContent = `
                    <div class="text-left space-y-4 max-h-72 overflow-y-auto mt-4 px-2 custom-scrollbar">
                        @foreach($pengumumans as $pengumuman)
                            <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                                <h4 class="font-bold text-primary mb-1 text-base">{{ $pengumuman->judul }}</h4>
                                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $pengumuman->isi }}</p>
                                <div class="text-xs text-gray-500 mt-3 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">calendar_today</span> 
                                    {{ $pengumuman->created_at->format('d M Y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                `;

                Swal.fire({
                    title: '📢 Pengumuman Baru',
                    html: htmlContent,
                    confirmButtonText: 'Saya Telah Membaca',
                    confirmButtonColor: '#00346f', // primary color
                    width: '600px',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        sessionStorage.setItem('pengumuman_dibaca', 'true');
                    }
                });
            }
            @endif
        });
    </script>
</x-app-layout>
