<x-app-layout>
    <div class="max-w-[1200px] mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Laporan Berita Acara Rekonsiliasi</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Cetak dan Download Berita Acara Rekonsiliasi.</p>
            </div>
        </div>
        
        <!-- Table Data -->
        <div class="bg-surface rounded border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Periode</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">SKPD</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Rekening</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Selisih</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Status</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transaksis as $trx)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ date('F', mktime(0, 0, 0, $trx->periode_bulan, 10)) }} {{ $trx->periode_tahun }}
                            </td>
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ $trx->skpd->nama ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ $trx->rekening->nama ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-data-tabular text-on-surface">
                                Rp {{ number_format(abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir), 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($trx->status_verifikasi === 'verified')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('ba.show', $trx->id) }}" class="inline-block text-primary hover:text-primary-container px-3 py-1 border border-primary rounded text-label-sm font-label-sm transition-colors" title="Lihat BA">
                                    <span class="material-symbols-outlined text-[16px] align-text-bottom mr-1">visibility</span>
                                    Lihat BA
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-on-surface-variant">Belum ada Berita Acara.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="border-t border-outline-variant p-4 bg-surface-container-lowest">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
