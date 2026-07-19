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
        @if($latestTransaksi)
            <p class="text-body-md font-body-md text-on-surface-variant">Periode aktif: {{ date('F', mktime(0, 0, 0, $latestTransaksi->periode_bulan, 10)) }} {{ $latestTransaksi->periode_tahun }} | {{ $latestTransaksi->skpd->nama ?? 'Semua SKPD' }}</p>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex flex-col gap-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Total BKU (SIPANDA)</h3>
                    <p class="text-headline-md font-headline-md text-on-surface font-data-tabular">
                        Rp {{ $latestTransaksi ? number_format($latestTransaksi->bku_saldo_akhir, 2, ',', '.') : '0,00' }}
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
                        Rp {{ $latestTransaksi ? number_format($latestTransaksi->bank_saldo_akhir, 2, ',', '.') : '0,00' }}
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
                        @php
                            $isMatched = $latestTransaksi && abs($latestTransaksi->bku_saldo_akhir - $latestTransaksi->bank_saldo_akhir) < 0.01;
                        @endphp
                        <p class="text-headline-md font-headline-md text-on-surface">
                            {{ $latestTransaksi ? ($isMatched ? '100%' : 'Selisih') : '-' }}
                        </p>
                        @if($latestTransaksi)
                            @if($isMatched)
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
                <div class="{{ $isMatched ? 'bg-secondary' : 'bg-error' }} h-full w-full rounded-full"></div>
            </div>
        </div>
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Discrepancy Summary -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-headline-sm font-headline-sm text-on-surface">Ringkasan Selisih Transaksi</h3>
                    <a class="text-primary text-label-sm font-label-sm hover:underline" href="#">Lihat Detail</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase">No. Bukti</th>
                            <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase">Tanggal</th>
                            <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase">Keterangan</th>
                            <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase text-right">Nilai Selisih</th>
                            <th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody class="text-body-md font-body-md">
                        @forelse($selisihTransaksis as $selisih)
                        <tr class="border-b border-outline-variant/50 hover:bg-surface-container-lowest/50 transition-colors">
                            <td class="py-3 px-4">
                                {{ str_pad($selisih->periode_bulan, 2, '0', STR_PAD_LEFT) }}/{{ $selisih->periode_tahun }}
                            </td>
                            <td class="py-3 px-4">{{ $selisih->updated_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-on-surface-variant truncate max-w-xs">{{ $selisih->keterangan_selisih ?: 'Tidak ada keterangan' }}</td>
                            <td class="py-3 px-4 font-data-tabular text-right text-error">
                                Rp {{ number_format(abs($selisih->bku_saldo_akhir - $selisih->bank_saldo_akhir), 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($selisih->status_verifikasi == 'draft')
                                <span class="inline-flex items-center gap-1 bg-error-container/30 text-on-error-container px-2 py-1 rounded text-label-sm font-label-sm">
                                    <span class="material-symbols-outlined text-sm">warning</span> Pending
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 bg-secondary-container/30 text-on-secondary-container px-2 py-1 rounded text-label-sm font-label-sm">
                                    <span class="material-symbols-outlined text-sm">check</span> Resolved
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-on-surface-variant">Tidak ada transaksi dengan selisih yang mencolok.</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Quick Actions Bento Grid -->
            <div class="grid grid-cols-2 gap-4">
                <a class="group relative overflow-hidden bg-primary-fixed rounded-xl p-6 border border-primary-fixed-dim shadow-sm hover:shadow-md transition-shadow" href="{{ route('transaksi.create') }}">
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <span class="material-symbols-outlined text-primary text-3xl mb-4" style="font-variation-settings: 'FILL' 1;">upload_file</span>
                        <div>
                            <h4 class="text-headline-sm font-headline-sm text-on-primary-container mb-1">Input Data Kas</h4>
                            <p class="text-body-md font-body-md text-on-primary-container/80">Buat Transaksi Rekonsiliasi Baru</p>
                        </div>
                    </div>
                    <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-primary/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                </a>
                <a class="group relative overflow-hidden bg-surface-container-lowest rounded-xl p-6 border border-outline-variant shadow-sm hover:shadow-md transition-shadow" href="{{ route('ba.index') }}">
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <span class="material-symbols-outlined text-on-surface-variant text-3xl mb-4">description</span>
                        <div>
                            <h4 class="text-headline-sm font-headline-sm text-on-surface mb-1">Laporan BA</h4>
                            <p class="text-body-md font-body-md text-on-surface-variant">Cetak Berita Acara Rekonsiliasi</p>
                        </div>
                    </div>
                    <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-surface-container-high/50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                </a>
            </div>
        </div>
        <!-- Side Panel -->
        <div class="space-y-8">
            <!-- Chart Preview -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Tren Rekonsiliasi {{ $tahunAktif }}</h3>
                <p class="text-body-md font-body-md text-on-surface-variant mb-4">Total Saldo BKU vs Bank.</p>
                <div class="w-full relative">
                    <canvas id="rekonChart" height="200"></canvas>
                </div>
            </div>
            <!-- Recent Activity -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Aktivitas Terakhir</h3>
                <div class="space-y-4 relative before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-outline-variant/30">
                    @forelse($recentActivities as $activity)
                    <div class="relative flex items-start gap-4">
                        <div class="bg-primary text-on-primary w-5 h-5 rounded-full flex items-center justify-center shrink-0 z-10 ring-4 ring-surface-container-lowest mt-1">
                            @if($activity->status_verifikasi == 'verified')
                                <span class="material-symbols-outlined text-[12px]">check</span>
                            @else
                                <span class="w-2 h-2 bg-on-primary rounded-full"></span>
                            @endif
                        </div>
                        <div>
                            <p class="text-body-md font-body-md text-on-surface">Data BKU {{ date('F', mktime(0, 0, 0, $activity->periode_bulan, 10)) }} {{ $activity->status_verifikasi == 'verified' ? 'Diverifikasi' : 'Diperbarui' }}</p>
                            <p class="text-label-sm font-label-sm text-on-surface-variant">Oleh: {{ $activity->user->name ?? 'Sistem' }} • {{ $activity->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-on-surface-variant text-sm">
                        Belum ada aktivitas.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('rekonChart').getContext('2d');
            const chartData = @json($chartData);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Saldo Akhir BKU',
                            data: chartData.bku,
                            borderColor: '#006B5E', // Primary color
                            backgroundColor: 'rgba(0, 107, 94, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Saldo Akhir Bank',
                            data: chartData.bank,
                            borderColor: '#4A635F', // Secondary color
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: "'Inter', sans-serif", size: 12 }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(0) + 'Jt';
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
