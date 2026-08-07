<x-app-layout>
    @if(isset($pengumumans) && $pengumumans->count() > 0)
        <div class="mb-8 space-y-4">
            @foreach($pengumumans as $pengumuman)
                <div class="p-5 bg-primary-container/20 text-on-surface rounded-xl border-l-4 border-primary shadow-sm flex items-start gap-4 relative overflow-hidden group transition-all hover:bg-primary-container/30">
                    <div class="p-2 bg-primary/10 text-primary rounded-lg shrink-0">
                        <span class="material-symbols-outlined text-[28px]">campaign</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-title-md mb-1">{{ $pengumuman->judul }}</h3>
                        <p class="text-body-md text-on-surface-variant whitespace-pre-line leading-relaxed">{{ $pengumuman->isi }}</p>
                        <span class="text-label-sm font-label-sm text-on-surface-variant/70 mt-3 inline-block">Diumumkan pada {{ $pengumuman->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-primary/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
            @endforeach
        </div>
    @endif
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
    
    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-{{ isset($kepatuhanData) ? '4' : '3' }} gap-6 mb-8">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex flex-col gap-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Total BKU (SIPANDA)</h3>
                    <p class="text-headline-md font-headline-md text-on-surface font-data-tabular">
                        Rp {{ $summary['has_data'] ? number_format($summary['bku'], 2, ',', '.') : '0,00' }}
                    </p>
                </div>
                <div class="p-2 bg-primary-fixed rounded-lg text-primary">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
            </div>
            <div class="text-label-sm font-label-sm text-on-surface-variant flex items-center gap-1">
                Saldo Buku Kas Umum Bendahara
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex flex-col gap-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Total Bank Kalsel</h3>
                    <p class="text-headline-md font-headline-md text-on-surface font-data-tabular">
                        Rp {{ $summary['has_data'] ? number_format($summary['bank'], 2, ',', '.') : '0,00' }}
                    </p>
                </div>
                <div class="p-2 bg-secondary-fixed rounded-lg text-secondary">
                    <span class="material-symbols-outlined">account_balance</span>
                </div>
            </div>
            <div class="text-label-sm font-label-sm text-on-surface-variant flex items-center gap-1">
                Saldo Rekening Koran
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex flex-col gap-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Status Rekonsiliasi</h3>
                    <div class="flex items-center gap-2">
                        <p class="text-headline-md font-headline-md text-on-surface">
                            {{ $summary['has_data'] ? ($summary['is_matched'] ? '100%' : 'Selisih') : '-' }}
                        </p>
                        @if($summary['has_data'])
                            @if($summary['is_matched'])
                                <span class="bg-secondary-container/30 text-on-secondary-container px-2 py-1 rounded text-label-sm font-label-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">check_circle</span> Matched
                                </span>
                            @else
                                <span class="bg-error-container/30 text-on-error-container px-2 py-1 rounded text-label-sm font-label-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">warning</span> Discrepancy
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="p-2 bg-tertiary-fixed-dim rounded-lg text-tertiary">
                    <span class="material-symbols-outlined">fact_check</span>
                </div>
            </div>
            <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                <div class="{{ $summary['is_matched'] ? 'bg-secondary' : 'bg-error' }} h-full w-full rounded-full"></div>
            </div>
        </div>
        
        @if(isset($kepatuhanData))
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex flex-col gap-4">
            <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Kepatuhan (Bulan {{ $kepatuhanData['target_bulan'] }})</h3>
            <div class="flex-grow flex flex-col items-center justify-center relative w-full h-32">
                <canvas id="kepatuhanChart"></canvas>
            </div>
            <div class="text-center mt-2">
                <p class="text-headline-md font-headline-md text-on-surface font-data-tabular">
                    {{ $kepatuhanData['persentase'] }}%
                </p>
                <p class="text-label-sm text-on-surface-variant">({{ $kepatuhanData['patuh'] }}/{{ $kepatuhanData['total_skpd'] }} Lapor)</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Removed Chart Analytics from here, moved to Main Content Area -->

    <!-- Status Rekonsiliasi Per SKPD -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-headline-sm font-headline-sm text-on-surface">Status Rekonsiliasi Per SKPD — {{ $tahunAktif }}</h3>
            <span class="text-label-sm font-label-sm text-on-surface-variant">Total: {{ count($skpdRekonStatus) }} SKPD</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-4 py-3 text-label-sm font-label-sm font-semibold text-on-surface">SKPD</th>
                        @for($i = 1; $i <= 12; $i++)
                            <th class="px-2 py-3 text-label-sm font-label-sm font-semibold text-on-surface text-center" title="Bulan {{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</th>
                        @endfor
                        <th class="px-4 py-3 text-label-sm font-label-sm font-semibold text-on-surface text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md divide-y divide-outline-variant/50">
                    @foreach($skpdRekonStatus as $skpdStatus)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-medium text-on-surface">{{ $skpdStatus['nama'] }}</span>
                        </td>
                        @for($i = 1; $i <= 12; $i++)
                            <td class="px-2 py-3 text-center">
                                @if(in_array($i, $skpdStatus['bulan_list']))
                                    <span class="material-symbols-outlined text-secondary text-[18px]" title="Bulan {{ $i }} Selesai">check_circle</span>
                                @else
                                    <span class="material-symbols-outlined text-error/30 text-[18px]" title="Bulan {{ $i }} Belum">cancel</span>
                                @endif
                            </td>
                        @endfor
                        <td class="px-4 py-3 text-center">
                            @if($skpdStatus['bulan_selesai'] >= 12)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-green-100 text-green-700 text-label-sm font-label-sm font-medium whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Lengkap
                                </span>
                            @elseif($skpdStatus['bulan_selesai'] > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-orange-100 text-orange-700 text-label-sm font-label-sm font-medium whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[14px]">pending</span> Sebagian
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-red-100 text-red-700 text-label-sm font-label-sm font-medium whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span> Belum
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $skpdsPaginated->links() }}
        </div>
    </div>

    <!-- Chart Analytics -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Tren Saldo Kas Daerah ({{ $tahunAktif }})</h3>
        <p class="text-body-md font-body-md text-on-surface-variant mb-6">Perbandingan Total Saldo Buku Kas Umum vs Rekening Koran Bank seluruh SKPD.</p>
        <div class="w-full relative h-72">
            <canvas id="rekonChart"></canvas>
        </div>
    </div>
    
    <!-- Discrepancy Summary Dipindahkan ke Menu Laporan -->

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
                            backgroundColor: ['#006B5E', '#E0E3E1'],
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
            @endif
        });
    </script>
</x-app-layout>
