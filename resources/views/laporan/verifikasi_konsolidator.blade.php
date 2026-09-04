<x-app-layout>
<style>
    #appMain { max-width: 100% !important; }
</style>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="font-headline-lg text-headline-lg text-on-surface">Laporan Verifikasi Konsolidator</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        Tahun Anggaran {{ $tahunAktif }}
                    </span>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Buku register dan rekapitulasi pengesahan hasil pemeriksaan rekonsiliasi kas daerah tingkat SKPD oleh Konsolidator BKAD.
                </p>
            </div>
            
            <div class="flex items-center gap-2.5 self-start md:self-auto flex-wrap">
                <a href="{{ route('laporan.verifikasi-konsolidator.pdf', request()->query()) }}" target="_blank" class="bg-rose-600 hover:bg-rose-700 text-white px-3.5 py-2 rounded-xl flex items-center space-x-1.5 transition-colors shadow-sm font-label-sm text-label-sm font-bold active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    <span>Cetak Register PDF</span>
                </a>
                <a href="{{ route('laporan.verifikasi-konsolidator.excel', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 rounded-xl flex items-center space-x-1.5 transition-colors shadow-sm font-label-sm text-label-sm font-bold active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">table_view</span>
                    <span>Ekspor Excel</span>
                </a>
            </div>
        </div>

        <!-- 3 KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- 1. Total Disetujui Valid -->
            <div class="p-4 rounded-2xl bg-surface border border-outline-variant shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Rekonsiliasi Valid &amp; Sah</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($kpi['valid']) }}</span>
                        <span class="text-xs text-on-surface-variant font-medium">Laporan</span>
                    </div>
                    <p class="text-[11px] text-emerald-700 mt-1 flex items-center gap-1 font-semibold">
                        <span class="material-symbols-outlined text-[15px]">verified</span>
                        Telah terbit tanda bukti digital
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">check_circle</span>
                </div>
            </div>

            <!-- 2. Total Nilai Kas Tervalidasi -->
            <div class="p-4 rounded-2xl bg-surface border border-outline-variant shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total Kas Tervalidasi</span>
                    <div class="mt-1">
                        <span class="text-2xl font-extrabold text-on-surface tracking-tight font-mono">Rp {{ number_format($kpi['total_saldo'], 0, ',', '.') }}</span>
                    </div>
                    <p class="text-[11px] text-on-surface-variant mt-1">
                        Akumulasi saldo kas tervalidasi sah
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">account_balance_wallet</span>
                </div>
            </div>

            <!-- 3. Berkas Perlu Perbaikan -->
            <div class="p-4 rounded-2xl bg-surface border border-outline-variant shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Perlu Perbaikan / Koreksi</span>
                    <div class="mt-1 flex items-baseline gap-1.5">
                        <span class="text-3xl font-extrabold text-rose-600 tracking-tight">{{ number_format($kpi['perlu_perbaikan']) }}</span>
                        <span class="text-xs text-on-surface-variant font-medium">Laporan</span>
                    </div>
                    <p class="text-[11px] text-rose-700 mt-1 flex items-center gap-1 font-semibold">
                        <span class="material-symbols-outlined text-[15px]">error</span>
                        Terdapat catatan kesalahan
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">report_problem</span>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('laporan.verifikasi-konsolidator') }}" method="GET" class="bg-surface p-4 rounded-2xl border border-outline-variant shadow-sm flex flex-col md:flex-row gap-3 items-stretch md:items-end">
            <!-- Filter SKPD -->
            <div class="flex-1">
                <label class="block font-label-sm text-xs font-bold text-on-surface mb-1">Pilih SKPD / Instansi</label>
                <select name="skpd_id" class="w-full h-10 border border-outline-variant rounded-xl px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface text-sm">
                    <option value="">Semua Instansi (SKPD)</option>
                    @foreach($skpds as $s)
                        <option value="{{ $s->id }}" {{ request('skpd_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Bulan -->
            <div class="w-full md:w-44">
                <label class="block font-label-sm text-xs font-bold text-on-surface mb-1">Periode Bulan</label>
                <select name="bulan" class="w-full h-10 border border-outline-variant rounded-xl px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface text-sm">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ $namaBulan[$i - 1] }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Filter Status Konsolidator -->
            <div class="w-full md:w-48">
                <label class="block font-label-sm text-xs font-bold text-on-surface mb-1">Status Pemeriksaan</label>
                <select name="status" class="w-full h-10 border border-outline-variant rounded-xl px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface text-sm">
                    <option value="">Semua Status</option>
                    <option value="valid" {{ request('status') === 'valid' ? 'selected' : '' }}>✅ Valid &amp; Sah</option>
                    <option value="perlu_perbaikan" {{ request('status') === 'perlu_perbaikan' ? 'selected' : '' }}>⚠️ Perlu Perbaikan</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>⏳ Menunggu Pemeriksaan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 px-4 rounded-xl bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-sm text-label-sm font-bold flex items-center space-x-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    <span>Terapkan</span>
                </button>
                @if(request('skpd_id') || request('bulan') || request('status'))
                <a href="{{ route('laporan.verifikasi-konsolidator') }}" class="h-10 px-3.5 rounded-xl border border-outline-variant bg-surface hover:bg-surface-container transition-colors font-label-sm text-label-sm flex items-center text-on-surface-variant" title="Reset Filter">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </a>
                @endif
            </div>
        </form>

        <!-- Table Register Verifikasi -->
        <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1100px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider text-center" style="width: 50px;">No</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider">Periode</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider">SKPD &amp; Rekening</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider text-right">Saldo BKU vs Bank</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider text-center">Bukti Dukung</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider">Status &amp; Konsolidator</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider text-center">Aksi / Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transaksis as $index => $trx)
                            @php
                                $selisih = abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir);
                                $isBalance = $selisih < 0.01;
                                
                                $docsCount = 0;
                                if (!empty($trx->file_ba_manual) && \App\Services\SiReKaStorage::exists($trx->file_ba_manual)) $docsCount++;
                                if (!empty($trx->file_buku_kas) && \App\Services\SiReKaStorage::exists($trx->file_buku_kas)) $docsCount++;
                                if (!empty($trx->file_buku_pembantu_bank) && \App\Services\SiReKaStorage::exists($trx->file_buku_pembantu_bank)) $docsCount++;
                                if (!empty($trx->file_rekening_koran) && \App\Services\SiReKaStorage::exists($trx->file_rekening_koran)) $docsCount++;
                            @endphp
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3.5 px-4 text-center font-mono text-xs text-on-surface-variant">
                                    {{ $transaksis->firstItem() + $index }}
                                </td>
                                
                                <!-- Periode -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-sm text-on-surface">{{ $namaBulan[$trx->periode_bulan - 1] }}</div>
                                    <div class="text-xs text-on-surface-variant font-mono">{{ $trx->periode_tahun }}</div>
                                </td>

                                <!-- SKPD & Rekening -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-sm text-on-surface leading-snug">{{ $trx->skpd->nama ?? '-' }}</div>
                                    <div class="text-xs text-on-surface-variant mt-0.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">account_balance</span>
                                        <span>{{ $trx->rekening->nomor ?? '-' }} &bull; {{ $trx->rekening->bank ?? '-' }}</span>
                                    </div>
                                </td>

                                <!-- Saldo BKU & Bank -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="text-xs text-on-surface-variant">
                                        BKU: <span class="font-bold text-on-surface font-mono">Rp {{ number_format($trx->bku_saldo_akhir, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="text-xs text-on-surface-variant">
                                        Bank: <span class="font-bold text-on-surface font-mono">Rp {{ number_format($trx->bank_saldo_akhir, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="mt-1">
                                        @if($isBalance)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                                <span class="material-symbols-outlined text-[13px]">check_circle</span> KLOP
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-700 border border-rose-500/20">
                                                <span class="material-symbols-outlined text-[13px]">warning</span> Selisih Rp {{ number_format($selisih, 2, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Bukti Dukung (X/4) -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $docsCount === 4 ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-700 border border-amber-500/20' }}">
                                        <span class="material-symbols-outlined text-[15px]">{{ $docsCount === 4 ? 'task' : 'attach_file' }}</span>
                                        <span>{{ $docsCount }}/4 Berkas</span>
                                    </div>
                                </td>

                                <!-- Status Konsolidator & Pemeriksa -->
                                <td class="py-3.5 px-4 max-w-[260px]">
                                    <div class="space-y-1">
                                        @if($trx->status_konsolidator === 'valid')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white shadow-xs">
                                                <span class="material-symbols-outlined text-[13px]">check_circle</span> Valid Konsolidator
                                            </span>
                                        @elseif($trx->status_konsolidator === 'perlu_perbaikan')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-600 text-white shadow-xs">
                                                <span class="material-symbols-outlined text-[13px]">error</span> Perlu Perbaikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-700 border border-blue-500/20">
                                                <span class="material-symbols-outlined text-[13px]">pending</span> Menunggu Cek
                                            </span>
                                        @endif

                                        @if($trx->checked_at)
                                            <div class="text-[11px] text-on-surface-variant flex items-center gap-1 mt-1">
                                                <span class="material-symbols-outlined text-[13px]">person_check</span>
                                                <span>{{ $trx->checker->name ?? 'Konsolidator' }} &bull; {{ \Carbon\Carbon::parse($trx->checked_at)->timezone('Asia/Makassar')->format('d/m/y H:i') }} WITA</span>
                                            </div>
                                        @endif

                                        @if(!empty($trx->catatan_konsolidator_terakhir))
                                            <div class="text-[10.5px] text-on-surface-variant bg-surface-container-low p-1.5 rounded-lg border border-outline-variant line-clamp-1 mt-1" title="{{ $trx->catatan_konsolidator_terakhir }}">
                                                {{ $trx->catatan_konsolidator_terakhir }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Aksi Cepat / Cetak Bukti Digital -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        @if($trx->status_konsolidator === 'valid')
                                            <a href="{{ route('transaksi.bukti-digital-pdf', $trx->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1 transition-all shadow-sm active:scale-95" title="Cetak Surat Tanda Bukti Digital (PDF)">
                                                <span class="material-symbols-outlined text-[16px]">verified</span>
                                                <span>Slip Digital</span>
                                            </a>
                                        @endif

                                        @if(in_array(Auth::user()->role, ['admin', 'konsolidator']))
                                            <a href="{{ route('transaksi.pemeriksaan', $trx->id) }}" class="p-1.5 rounded-xl bg-primary/10 hover:bg-primary text-primary hover:text-on-primary border border-primary/20 transition-all text-xs flex items-center justify-center" title="Buka Lembar Pemeriksaan">
                                                <span class="material-symbols-outlined text-[16px]">fact_check</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('ba.show', $trx->id) }}" target="_blank" class="p-1.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface border border-outline-variant transition-colors text-xs flex items-center justify-center" title="Buka Berita Acara (BA)">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant flex items-center justify-center text-primary mb-2 shadow-inner">
                                            <span class="material-symbols-outlined text-[32px]">folder_open</span>
                                        </div>
                                        <h3 class="text-sm font-bold text-on-surface">Tidak Ada Data Ditemukan</h3>
                                        <p class="text-xs text-on-surface-variant max-w-sm mt-1">
                                            Tidak ada data laporan rekonsiliasi yang cocok dengan kriteria filter di atas.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transaksis->hasPages())
                <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
