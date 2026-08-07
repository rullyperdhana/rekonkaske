<x-app-layout>
<style>
    #appMain { max-width: 100% !important; }
</style>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Laporan Berita Acara Rekonsiliasi</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Cetak dan Download Berita Acara Rekonsiliasi.</p>
            </div>
        </div>
        
        <!-- Filters -->
        <form action="{{ route('ba.index') }}" method="GET" class="bg-surface p-4 rounded border border-outline-variant shadow-sm flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-body-md font-bold text-on-surface mb-1">Cari SKPD / Rekening</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama SKPD atau rekening..." class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
            </div>
            <div class="w-full sm:w-48">
                <label class="block font-body-md font-bold text-on-surface mb-1">Bulan</label>
                <select name="bulan" class="w-full h-10 border border-outline-variant rounded px-3 bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none font-body-md text-on-surface">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ $namaBulan[$i - 1] }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-10 px-4 border border-outline-variant rounded bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-sm text-label-sm flex items-center space-x-2">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request('search') || request('bulan'))
                <a href="{{ route('ba.index') }}" class="h-10 px-4 border border-outline-variant rounded bg-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm flex items-center space-x-2 text-on-surface-variant">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
        
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
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Dokumen</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transaksis as $trx)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ $namaBulan[$trx->periode_bulan - 1] }} {{ $trx->periode_tahun }}
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
                                <div class="flex flex-wrap items-center justify-center gap-1">
                                    @if($trx->file_ba_manual)
                                        <a href="{{ \App\Services\SiReKaStorage::url($trx->file_ba_manual) }}" target="_blank" class="px-2 py-0.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-[10px] font-bold transition-colors" title="Lihat Berita Acara (Manual)">BA</a>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded text-[10px] font-bold" title="Belum diupload">BA</span>
                                    @endif

                                    @if($trx->file_buku_kas)
                                        <a href="{{ \App\Services\SiReKaStorage::url($trx->file_buku_kas) }}" target="_blank" class="px-2 py-0.5 bg-green-100 text-green-700 hover:bg-green-200 rounded text-[10px] font-bold transition-colors" title="Lihat Buku Kas Pengeluaran">KAS</a>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded text-[10px] font-bold" title="Belum diupload">KAS</span>
                                    @endif

                                    @if($trx->file_buku_pembantu_bank)
                                        <a href="{{ \App\Services\SiReKaStorage::url($trx->file_buku_pembantu_bank) }}" target="_blank" class="px-2 py-0.5 bg-purple-100 text-purple-700 hover:bg-purple-200 rounded text-[10px] font-bold transition-colors" title="Lihat Buku Pembantu Bank">BANK</a>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded text-[10px] font-bold" title="Belum diupload">BANK</span>
                                    @endif

                                    @if($trx->file_rekening_koran)
                                        <a href="{{ \App\Services\SiReKaStorage::url($trx->file_rekening_koran) }}" target="_blank" class="px-2 py-0.5 bg-orange-100 text-orange-700 hover:bg-orange-200 rounded text-[10px] font-bold transition-colors" title="Lihat Rekening Koran Bank">KORAN</a>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded text-[10px] font-bold" title="Belum diupload">KORAN</span>
                                    @endif
                                </div>
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
                            <td colspan="7" class="py-4 text-center text-on-surface-variant">Belum ada Berita Acara.</td>
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
