<x-app-layout>
@section('title', 'Laporan Rekapitulasi Tahunan')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h2 class="text-headline-sm font-headline-sm text-on-surface">Rekapitulasi Tahunan SKPD</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Laporan total rekon BKU dan Bank per bulan dalam satu tahun anggaran ({{ $tahunAktif }}).</p>
    </div>
</div>

<div class="bg-surface rounded-xl shadow-sm border border-outline-variant p-6 mb-8">
    <form action="{{ route('laporan.rekap') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        @if(auth()->user()->role === 'admin')
        <div class="w-full md:w-1/2">
            <label for="skpd_id" class="block text-label-md font-label-md text-on-surface mb-1">Pilih SKPD</label>
            <select name="skpd_id" id="skpd_id" class="w-full h-11 px-3 rounded-lg border border-outline bg-surface text-body-md focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                <option value="">-- Pilih SKPD --</option>
                @foreach($skpds as $skpd)
                    <option value="{{ $skpd->id }}" {{ $selectedSkpdId == $skpd->id ? 'selected' : '' }}>{{ $skpd->kode }} - {{ $skpd->nama }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="w-full md:w-1/2">
            <label class="block text-label-md font-label-md text-on-surface mb-1">SKPD Aktif</label>
            <input type="text" value="{{ $selectedSkpd->nama ?? '' }}" disabled class="w-full h-11 px-3 rounded-lg border border-outline bg-surface-container-lowest text-body-md text-on-surface-variant opacity-70">
            <input type="hidden" name="skpd_id" value="{{ auth()->user()->skpd_id }}">
        </div>
        @endif
        
        <div class="flex gap-2">
            <button type="submit" class="h-11 px-6 bg-primary text-on-primary hover:bg-primary/90 rounded-lg flex items-center gap-2 font-label-md transition-colors shadow-sm">
                <span class="material-symbols-outlined" data-weight="fill">search</span>
                Tampilkan
            </button>
            @if($selectedSkpdId)
            <a href="{{ route('laporan.rekap.pdf', ['skpd_id' => $selectedSkpdId]) }}" target="_blank" class="h-11 px-6 bg-tertiary text-on-tertiary hover:bg-tertiary/90 rounded-lg flex items-center gap-2 font-label-md transition-colors shadow-sm">
                <span class="material-symbols-outlined" data-weight="fill">print</span>
                Cetak PDF
            </a>
            @endif
        </div>
    </form>
</div>

@if($selectedSkpdId && isset($rekapData))
<div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden">
    <div class="p-6 border-b border-outline-variant bg-surface-container-lowest flex justify-between items-center">
        <div>
            <h3 class="text-title-md font-title-md text-on-surface">Tabel Rekapitulasi Tahun {{ $tahunAktif }}</h3>
            <p class="text-body-sm text-on-surface-variant">{{ $selectedSkpd->nama }}</p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface">Bulan</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface text-right">Saldo BKU</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface text-right">Saldo Bank</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface text-right">Selisih</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @foreach($rekapData as $data)
                <tr class="hover:bg-surface-container-lowest transition-colors {{ !$data['is_exist'] ? 'opacity-60 bg-surface-container-lowest' : '' }}">
                    <td class="px-6 py-4 text-body-md font-medium text-on-surface whitespace-nowrap">
                        {{ date('F', mktime(0, 0, 0, $data['bulan'], 10)) }}
                    </td>
                    
                    @if($data['is_exist'])
                        <td class="px-6 py-4 text-body-md font-data-tabular text-on-surface text-right whitespace-nowrap">
                            Rp {{ number_format($data['bku'], 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-body-md font-data-tabular text-on-surface text-right whitespace-nowrap">
                            Rp {{ number_format($data['bank'], 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-body-md font-data-tabular text-right whitespace-nowrap {{ $data['selisih'] > 0 ? 'text-error font-bold' : 'text-secondary' }}">
                            Rp {{ number_format($data['selisih'], 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($data['status'] === 'verified')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-label-sm font-label-sm bg-secondary/10 text-secondary border border-secondary/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-label-sm font-label-sm bg-outline-variant/30 text-on-surface-variant border border-outline-variant">
                                    <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> Draft
                                </span>
                            @endif
                        </td>
                    @else
                        <td colspan="4" class="px-6 py-4 text-body-md text-on-surface-variant text-center italic">
                            Belum ada laporan
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

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
