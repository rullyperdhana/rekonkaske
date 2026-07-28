<x-app-layout>
@section('title', 'Laporan Ringkasan Selisih Transaksi')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h2 class="text-headline-sm font-headline-sm text-on-surface">Laporan Ringkasan Selisih Transaksi</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Daftar transaksi yang memiliki selisih antara Saldo BKU dan Saldo Bank pada Tahun Anggaran {{ $tahunAktif }}.</p>
    </div>
</div>

<div class="bg-surface rounded-xl shadow-sm border border-outline-variant p-6 mb-8">
    <form action="{{ route('laporan.ringkasan-selisih') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'konsolidator')
        <div class="w-full md:w-1/3">
            <label for="skpd_id" class="block text-label-md font-label-md text-on-surface mb-1">Pilih SKPD</label>
            <select name="skpd_id" id="skpd_id" class="w-full h-11 px-3 rounded-lg border border-outline bg-surface text-body-md focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                <option value="">-- Semua SKPD --</option>
                @foreach($skpds as $skpd)
                    <option value="{{ $skpd->id }}" {{ request('skpd_id') == $skpd->id ? 'selected' : '' }}>{{ $skpd->kode }} - {{ $skpd->nama }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="w-full md:w-1/3">
            <label class="block text-label-md font-label-md text-on-surface mb-1">SKPD Aktif</label>
            <input type="text" value="{{ auth()->user()->skpd->nama ?? '' }}" disabled class="w-full h-11 px-3 rounded-lg border border-outline bg-surface-container-lowest text-body-md text-on-surface-variant opacity-70">
            <input type="hidden" name="skpd_id" value="{{ auth()->user()->skpd_id }}">
        </div>
        @endif

        <div class="w-full md:w-1/4">
            <label for="bulan" class="block text-label-md font-label-md text-on-surface mb-1">Pilih Bulan</label>
            <select name="bulan" id="bulan" class="w-full h-11 px-3 rounded-lg border border-outline bg-surface text-body-md focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                <option value="">-- Semua Bulan --</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>Bulan {{ $i }}</option>
                @endfor
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="h-11 px-6 bg-primary text-on-primary hover:bg-primary/90 rounded-lg flex items-center gap-2 font-label-md transition-colors shadow-sm">
                <span class="material-symbols-outlined" data-weight="fill">search</span>
                Filter
            </button>
            <a href="{{ route('laporan.ringkasan-selisih.pdf', request()->query()) }}" target="_blank" class="h-11 px-6 bg-tertiary text-on-tertiary hover:bg-tertiary/90 rounded-lg flex items-center gap-2 font-label-md transition-colors shadow-sm">
                <span class="material-symbols-outlined" data-weight="fill">print</span>
                Cetak PDF
            </a>
        </div>
    </form>
</div>

<div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface w-12 text-center">No</th>
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface">Instansi (SKPD)</th>
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface text-center">Bulan</th>
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface text-right">Saldo BKU</th>
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface text-right">Saldo Bank</th>
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface text-right">Nilai Selisih</th>
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface">Keterangan</th>
                    <th class="px-4 py-4 text-label-md font-label-md font-semibold text-on-surface text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @forelse($transaksis as $index => $trx)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-4 py-3 text-body-md text-on-surface text-center">{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $index + 1 }}</td>
                    <td class="px-4 py-3 text-body-md text-on-surface font-medium">{{ $trx->skpd->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-body-md text-on-surface text-center">{{ $trx->periode_bulan }}</td>
                    <td class="px-4 py-3 text-body-md text-on-surface text-right font-data-tabular">Rp {{ number_format($trx->bku_saldo_akhir, 2, ',', '.') }}</td>
                    <td class="px-4 py-3 text-body-md text-on-surface text-right font-data-tabular">Rp {{ number_format($trx->bank_saldo_akhir, 2, ',', '.') }}</td>
                    <td class="px-4 py-3 text-body-md text-error font-bold text-right font-data-tabular">
                        Rp {{ number_format(abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir), 2, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-body-sm text-on-surface-variant">
                        {{ $trx->keterangan_selisih ?: '-' }}
                    </td>
                    <td class="px-4 py-3 text-body-md text-on-surface text-center">
                        @if($trx->status_verifikasi == 'draft')
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
                    <td colspan="8" class="px-4 py-8 text-center text-on-surface-variant font-body-md">Tidak ada data transaksi yang memiliki selisih.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($transaksis->hasPages())
    <div class="p-4 border-t border-outline-variant bg-surface">
        {{ $transaksis->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('skpd_id')) {
            new TomSelect("#skpd_id",{
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        }
    });
</script>
</x-app-layout>
