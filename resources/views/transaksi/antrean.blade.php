<x-app-layout>
<style>
    #appMain { max-width: 100% !important; }
</style>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="font-headline-lg text-headline-lg text-on-surface">Antrean Verifikasi Rekonsiliasi</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        Tahun Anggaran {{ $tahunAktif }}
                    </span>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Pusat kendali pemeriksaan berkas berita acara dan bukti dukung SKPD untuk Konsolidator & Admin.
                </p>
            </div>
            <div class="flex items-center gap-2.5 self-start md:self-auto flex-wrap">
                @php
                    $firstPending = \App\Models\Transaksi::where('periode_tahun', $tahunAktif)
                        ->where('status_verifikasi', 'verified')
                        ->where('status_konsolidator', 'menunggu')
                        ->orderBy('periode_bulan', 'asc')
                        ->orderBy('id', 'asc')
                        ->first();
                @endphp
                @if($firstPending)
                <a href="{{ route('transaksi.pemeriksaan', $firstPending->id) }}" class="bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container px-4 py-2 rounded-xl flex items-center space-x-2 transition-all shadow-sm font-label-sm text-label-sm font-bold active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                    <span>Mulai Periksa Terdepan ({{ $firstPending->skpd->nama ?? 'SKPD' }})</span>
                </a>
                @endif
                <a href="{{ route('transaksi.index') }}" class="bg-surface border border-outline-variant hover:bg-surface-container text-on-surface px-3.5 py-2 rounded-xl flex items-center space-x-1.5 transition-colors shadow-sm font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[18px]">list_alt</span>
                    <span>Tabel Data Entri</span>
                </a>
            </div>
        </div>

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-[24px]">check_circle</span>
                <span class="font-body-md font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-[24px]">error</span>
                <span class="font-body-md font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- 4 Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Menunggu Pemeriksaan -->
            <a href="{{ route('transaksi.antrean', ['tab' => 'menunggu']) }}" class="group block p-4 rounded-2xl border transition-all duration-300 {{ $activeTab === 'menunggu' ? 'bg-amber-500/10 border-amber-500 shadow-md ring-2 ring-amber-500/30' : 'bg-surface border-outline-variant hover:border-amber-400 hover:shadow-sm' }}">
                <div class="flex items-center justify-between">
                    <span class="text-label-sm font-bold {{ $activeTab === 'menunggu' ? 'text-amber-800' : 'text-on-surface-variant' }}">Menunggu Pemeriksaan</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[22px]">pending</span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($counts['menunggu']) }}</span>
                    <span class="text-xs text-on-surface-variant ml-1 font-medium">Berkas</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-amber-500 inline-block animate-ping"></span>
                    Sudah verif SKPD, antre dicek
                </p>
            </a>

            <!-- 2. Perlu Perbaikan -->
            <a href="{{ route('transaksi.antrean', ['tab' => 'perlu_perbaikan']) }}" class="group block p-4 rounded-2xl border transition-all duration-300 {{ $activeTab === 'perlu_perbaikan' ? 'bg-rose-500/10 border-rose-500 shadow-md ring-2 ring-rose-500/30' : 'bg-surface border-outline-variant hover:border-rose-400 hover:shadow-sm' }}">
                <div class="flex items-center justify-between">
                    <span class="text-label-sm font-bold {{ $activeTab === 'perlu_perbaikan' ? 'text-rose-800' : 'text-on-surface-variant' }}">Perlu Perbaikan</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[22px]">error</span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($counts['perlu_perbaikan']) }}</span>
                    <span class="text-xs text-on-surface-variant ml-1 font-medium">Berkas</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5">Ada catatan koreksi kesalahan</p>
            </a>

            <!-- 3. Siap Reset ke Draft -->
            <a href="{{ route('transaksi.antrean', ['tab' => 'siap_reset']) }}" class="group block p-4 rounded-2xl border transition-all duration-300 {{ $activeTab === 'siap_reset' ? 'bg-purple-500/10 border-purple-500 shadow-md ring-2 ring-purple-500/30' : 'bg-surface border-outline-variant hover:border-purple-400 hover:shadow-sm' }}">
                <div class="flex items-center justify-between">
                    <span class="text-label-sm font-bold {{ $activeTab === 'siap_reset' ? 'text-purple-800' : 'text-on-surface-variant' }}">Butuh Reset Draft</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[22px]">restart_alt</span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($counts['siap_reset']) }}</span>
                    <span class="text-xs text-on-surface-variant ml-1 font-medium">Berkas</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5">Masih Verified, tunggu Admin reset</p>
            </a>

            <!-- 4. Telah Disetujui Valid -->
            <a href="{{ route('transaksi.antrean', ['tab' => 'valid']) }}" class="group block p-4 rounded-2xl border transition-all duration-300 {{ $activeTab === 'valid' ? 'bg-emerald-500/10 border-emerald-500 shadow-md ring-2 ring-emerald-500/30' : 'bg-surface border-outline-variant hover:border-emerald-400 hover:shadow-sm' }}">
                <div class="flex items-center justify-between">
                    <span class="text-label-sm font-bold {{ $activeTab === 'valid' ? 'text-emerald-800' : 'text-on-surface-variant' }}">Telah Disetujui (Valid)</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[22px]">check_circle</span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-3xl font-extrabold text-on-surface tracking-tight">{{ number_format($counts['valid']) }}</span>
                    <span class="text-xs text-on-surface-variant ml-1 font-medium">Berkas</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5">Sah &amp; tuntas diverifikasi</p>
            </a>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('transaksi.antrean') }}" method="GET" class="bg-surface p-4 rounded-2xl border border-outline-variant shadow-sm flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div class="flex-1">
                <label class="block font-label-sm text-xs font-bold text-on-surface mb-1">Pencarian SKPD / Rekening Bank</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama SKPD, nomor rekening, atau bank..." class="w-full h-10 pl-9 pr-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface text-sm">
                </div>
            </div>
            <div class="w-full sm:w-48">
                <label class="block font-label-sm text-xs font-bold text-on-surface mb-1">Filter Bulan</label>
                <select name="bulan" class="w-full h-10 border border-outline-variant rounded-xl px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface text-sm">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ $namaBulan[$i - 1] }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 px-4 rounded-xl bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-sm text-label-sm font-bold flex items-center space-x-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    <span>Terapkan</span>
                </button>
                @if(request('search') || request('bulan'))
                <a href="{{ route('transaksi.antrean', ['tab' => $activeTab]) }}" class="h-10 px-3.5 rounded-xl border border-outline-variant bg-surface hover:bg-surface-container transition-colors font-label-sm text-label-sm flex items-center text-on-surface-variant" title="Reset Filter">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </a>
                @endif
            </div>
        </form>

        <!-- Tabs Navigation -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-outline-variant">
            <a href="{{ route('transaksi.antrean', array_merge(request()->query(), ['tab' => 'menunggu'])) }}" class="px-4 py-2.5 rounded-xl font-label-sm text-sm font-bold flex items-center gap-2 transition-all shrink-0 {{ $activeTab === 'menunggu' ? 'bg-amber-500 text-white shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined text-[18px]">hourglass_top</span>
                <span>Menunggu Pemeriksaan</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'menunggu' ? 'bg-white/25 text-white' : 'bg-amber-500/10 text-amber-700' }}">{{ $counts['menunggu'] }}</span>
            </a>

            <a href="{{ route('transaksi.antrean', array_merge(request()->query(), ['tab' => 'perlu_perbaikan'])) }}" class="px-4 py-2.5 rounded-xl font-label-sm text-sm font-bold flex items-center gap-2 transition-all shrink-0 {{ $activeTab === 'perlu_perbaikan' ? 'bg-rose-600 text-white shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined text-[18px]">report_problem</span>
                <span>Perlu Perbaikan</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'perlu_perbaikan' ? 'bg-white/25 text-white' : 'bg-rose-500/10 text-rose-700' }}">{{ $counts['perlu_perbaikan'] }}</span>
            </a>

            <a href="{{ route('transaksi.antrean', array_merge(request()->query(), ['tab' => 'siap_reset'])) }}" class="px-4 py-2.5 rounded-xl font-label-sm text-sm font-bold flex items-center gap-2 transition-all shrink-0 {{ $activeTab === 'siap_reset' ? 'bg-purple-600 text-white shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                <span>Butuh Reset Draft</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'siap_reset' ? 'bg-white/25 text-white' : 'bg-purple-500/10 text-purple-700' }}">{{ $counts['siap_reset'] }}</span>
            </a>

            <a href="{{ route('transaksi.antrean', array_merge(request()->query(), ['tab' => 'valid'])) }}" class="px-4 py-2.5 rounded-xl font-label-sm text-sm font-bold flex items-center gap-2 transition-all shrink-0 {{ $activeTab === 'valid' ? 'bg-emerald-600 text-white shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span>Telah Disetujui (Valid)</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'valid' ? 'bg-white/25 text-white' : 'bg-emerald-500/10 text-emerald-700' }}">{{ $counts['valid'] }}</span>
            </a>
        </div>

        <!-- Table Antrean Data -->
        <div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1050px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider">Periode</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider">SKPD &amp; Rekening</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider text-right">Saldo Rekonsiliasi</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider text-center">Bukti Dukung</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider">Status &amp; Catatan</th>
                            <th class="py-3 px-4 font-label-sm text-xs font-bold text-on-surface uppercase tracking-wider text-center">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transaksis as $trx)
                            @php
                                $selisih = abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir);
                                $isBalance = $selisih < 0.01;
                                
                                // Hitung jumlah dokumen terupload
                                $docsCount = 0;
                                if (!empty($trx->file_ba_manual) && \App\Services\SiReKaStorage::exists($trx->file_ba_manual)) $docsCount++;
                                if (!empty($trx->file_buku_kas) && \App\Services\SiReKaStorage::exists($trx->file_buku_kas)) $docsCount++;
                                if (!empty($trx->file_buku_pembantu_bank) && \App\Services\SiReKaStorage::exists($trx->file_buku_pembantu_bank)) $docsCount++;
                                if (!empty($trx->file_rekening_koran) && \App\Services\SiReKaStorage::exists($trx->file_rekening_koran)) $docsCount++;
                            @endphp
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <!-- Periode -->
                                <td class="py-3.5 px-4 font-body-md text-on-surface whitespace-nowrap">
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

                                <!-- Saldo BKU & Bank + Klop/Selisih -->
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
                                                <span class="material-symbols-outlined text-[13px]">check_circle</span> KLOP (Rp 0)
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
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $docsCount === 4 ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20' : ($docsCount > 0 ? 'bg-amber-500/10 text-amber-700 border border-amber-500/20' : 'bg-rose-500/10 text-rose-700 border border-rose-500/20') }}">
                                        <span class="material-symbols-outlined text-[15px]">{{ $docsCount === 4 ? 'task' : 'attach_file' }}</span>
                                        <span>{{ $docsCount }}/4 Berkas</span>
                                    </div>
                                </td>

                                <!-- Status Konsolidator & Catatan -->
                                <td class="py-3.5 px-4 max-w-[280px]">
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
                                                <span class="material-symbols-outlined text-[13px]">pending</span> Menunggu Pemeriksaan
                                            </span>
                                        @endif

                                        @if($trx->status_verifikasi === 'draft')
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/15 text-amber-800">
                                                Sudah Jadi Draft
                                            </span>
                                        @endif

                                        @if(!empty($trx->catatan_konsolidator_terakhir))
                                            <div class="text-[11px] text-on-surface-variant bg-surface-container-low p-2 rounded-lg border border-outline-variant line-clamp-2 mt-1" title="{{ $trx->catatan_konsolidator_terakhir }}">
                                                <strong class="text-on-surface">Catatan:</strong> {{ $trx->catatan_konsolidator_terakhir }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Aksi Cepat -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <a href="{{ route('transaksi.pemeriksaan', $trx->id) }}" class="px-3 py-1.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container text-xs font-bold flex items-center gap-1 transition-all shadow-sm active:scale-95">
                                            <span class="material-symbols-outlined text-[16px]">fact_check</span>
                                            <span>Periksa</span>
                                        </a>

                                        @if(Auth::user()->role === 'admin' && $trx->status_verifikasi === 'verified')
                                        <form action="{{ route('transaksi.reset-draft', $trx->id) }}" method="POST" onsubmit="return confirm('Kembalikan transaksi {{ $trx->skpd->nama ?? '' }} ke status DRAFT agar SKPD dapat memperbaikinya?');">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-700 hover:text-white border border-amber-500/20 transition-all text-xs font-bold flex items-center justify-center" title="Admin: Reset status ke Draft">
                                                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                                            </button>
                                        </form>
                                        @endif

                                        <a href="{{ route('ba.show', $trx->id) }}" target="_blank" class="p-1.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface border border-outline-variant transition-colors text-xs flex items-center justify-center" title="Buka Berita Acara">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-on-surface-variant">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 rounded-2xl bg-surface-container-low border border-outline-variant flex items-center justify-center text-primary mb-3 shadow-inner">
                                            <span class="material-symbols-outlined text-[36px]">
                                                {{ $activeTab === 'menunggu' ? 'verified' : 'inbox' }}
                                            </span>
                                        </div>
                                        <h3 class="text-base font-bold text-on-surface">
                                            @if($activeTab === 'menunggu')
                                                Semua Antrean Bersih!
                                            @else
                                                Tidak Ada Data pada Tab Ini
                                            @endif
                                        </h3>
                                        <p class="text-xs text-on-surface-variant max-w-sm mt-1">
                                            @if($activeTab === 'menunggu')
                                                Luar biasa! Tidak ada laporan rekonsiliasi yang sedang menunggu pemeriksaan konsolidator untuk periode ini.
                                            @elseif($activeTab === 'perlu_perbaikan')
                                                Tidak ada rekonsiliasi berstatus perlu perbaikan saat ini.
                                            @elseif($activeTab === 'siap_reset')
                                                Tidak ada transaksi yang perlu direset ke status draft oleh Admin.
                                            @else
                                                Belum ada data pada kriteria pencarian ini.
                                            @endif
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
