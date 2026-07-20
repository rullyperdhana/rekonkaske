<x-app-layout>
@section('title', 'Laporan Tunggakan & Selisih')

<div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h2 class="text-headline-sm font-headline-sm text-on-surface">Daftar Tunggakan & Selisih SKPD</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Pemantauan SKPD yang belum melaporkan rekonsiliasi atau memiliki selisih saldo di Tahun Anggaran {{ $tahunAktif }}.</p>
    </div>
</div>

<!-- Tabel Tunggakan / Belum Lapor -->
<div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden mb-8">
    <div class="p-6 border-b border-outline-variant bg-error-container/10 flex items-center gap-3">
        <div class="p-2 bg-error/10 text-error rounded-lg">
            <span class="material-symbols-outlined">warning</span>
        </div>
        <div>
            <h3 class="text-title-md font-title-md text-on-surface">SKPD Belum Lapor (Tunggakan)</h3>
            <p class="text-body-sm text-on-surface-variant">Batas lapor seharusnya: Bulan {{ $targetMonth }} Tahun {{ $tahunAktif }}</p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface w-16 text-center">No</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface">Nama SKPD</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface text-center">Lapor Terakhir</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface text-center">Menunggak (Bulan)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @forelse($dataTunggakanPaginated as $index => $item)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 text-body-md text-on-surface text-center">{{ ($dataTunggakanPaginated->currentPage() - 1) * $dataTunggakanPaginated->perPage() + $index + 1 }}</td>
                    <td class="px-6 py-4 text-body-md font-medium text-on-surface">
                        <div class="font-bold">{{ $item['skpd']->kode }}</div>
                        <div class="text-sm text-on-surface-variant">{{ $item['skpd']->nama }}</div>
                    </td>
                    <td class="px-6 py-4 text-body-md text-on-surface text-center">
                        @if($item['bulan_terakhir'] > 0)
                            Bulan {{ $item['bulan_terakhir'] }}
                        @else
                            <span class="italic text-error">Belum Lapor Sama Sekali</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-body-md text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-label-sm font-label-sm bg-error/10 text-error font-bold">
                            {{ $item['tunggakan_bulan'] }} Bulan
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-5xl mb-2 text-outline">check_circle</span>
                        <p class="text-body-lg">Luar biasa! Tidak ada SKPD yang menunggak pelaporan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dataTunggakanPaginated->hasPages())
    <div class="p-6 border-t border-outline-variant bg-surface-container-low">
        {{ $dataTunggakanPaginated->links() }}
    </div>
    @endif
</div>

<!-- Tabel Selisih -->
<div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden mb-8">
    <div class="p-6 border-b border-outline-variant bg-tertiary-container/10 flex items-center gap-3">
        <div class="p-2 bg-tertiary/10 text-tertiary rounded-lg">
            <span class="material-symbols-outlined">rule</span>
        </div>
        <div>
            <h3 class="text-title-md font-title-md text-on-surface">Daftar SKPD dengan Selisih (Discrepancy)</h3>
            <p class="text-body-sm text-on-surface-variant">Data transaksi SKPD yang nilai BKU dan Rekening Koran Bank tidak cocok.</p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface w-16 text-center">No</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface">Nama SKPD</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface">Bulan Selisih</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @forelse($dataSelisihPaginated as $index => $item)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 text-body-md text-on-surface text-center align-top">{{ ($dataSelisihPaginated->currentPage() - 1) * $dataSelisihPaginated->perPage() + $index + 1 }}</td>
                    <td class="px-6 py-4 text-body-md font-medium text-on-surface align-top">
                        <div class="font-bold">{{ $item['skpd']->kode }}</div>
                        <div class="text-sm text-on-surface-variant">{{ $item['skpd']->nama }}</div>
                    </td>
                    <td class="px-6 py-4 text-body-md text-on-surface">
                        <div class="flex flex-col gap-2">
                            @foreach($item['transaksi'] as $trx)
                                <div class="bg-surface-container-lowest p-3 border border-outline-variant rounded-lg flex justify-between items-center">
                                    <div>
                                        <div class="font-bold text-label-md">Bulan {{ $trx->periode_bulan }}</div>
                                        <div class="text-body-sm text-on-surface-variant">
                                            Selisih: <span class="text-error font-bold">Rp {{ number_format(abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir), 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('transaksi.show', $trx->id) }}" class="text-primary hover:underline text-label-sm font-label-sm">Lihat Detail &rarr;</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-5xl mb-2 text-outline">verified</span>
                        <p class="text-body-lg">Sempurna! Semua laporan SKPD saat ini seimbang (matched).</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dataSelisihPaginated->hasPages())
    <div class="p-6 border-t border-outline-variant bg-surface-container-low">
        {{ $dataSelisihPaginated->links() }}
    </div>
    @endif
</div>

</x-app-layout>
