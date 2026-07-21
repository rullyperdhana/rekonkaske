<x-app-layout>
    <div class="max-w-[1200px] mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b-[3px] border-primary pb-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Data Transaksi Rekonsiliasi</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola data input rekonsiliasi bulanan SKPD.</p>
            </div>
            @if(Auth::user()->role !== 'konsolidator')
            <a href="{{ route('transaksi.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded flex items-center space-x-2 hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm self-start md:self-auto font-label-sm text-label-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Input Transaksi Baru</span>
            </a>
            @endif
        </div>
        
        <!-- Filters -->
        <form action="{{ route('transaksi.index') }}" method="GET" class="bg-surface p-4 rounded border border-outline-variant shadow-sm flex flex-col sm:flex-row gap-4">
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
                <a href="{{ route('transaksi.index') }}" class="h-10 px-4 border border-outline-variant rounded bg-surface hover:bg-surface-container-low transition-colors font-label-sm text-label-sm flex items-center space-x-2 text-on-surface-variant">
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
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-right">Saldo BKU</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-right">Saldo Bank</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface">Status</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Dokumen</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transaksis as $trx)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ str_pad($trx->periode_bulan, 2, '0', STR_PAD_LEFT) }} / {{ $trx->periode_tahun }}
                            </td>
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ $trx->skpd->nama ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-body-md text-on-surface">
                                {{ $trx->rekening->nama ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-data-tabular text-on-surface text-right">
                                Rp {{ number_format($trx->bku_saldo_akhir, 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 font-data-tabular text-on-surface text-right">
                                Rp {{ number_format($trx->bank_saldo_akhir, 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($trx->status_verifikasi == 'verified')
                                <span class="inline-flex items-center px-2 py-1 rounded bg-primary/10 text-primary font-label-sm text-label-sm">
                                    Terverifikasi
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-secondary/10 text-secondary font-label-sm text-label-sm">
                                    Draft
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $docCount = 0;
                                    if($trx->file_ba_manual) $docCount++;
                                    if($trx->file_buku_kas) $docCount++;
                                    if($trx->file_buku_pembantu_bank) $docCount++;
                                    if($trx->file_rekening_koran) $docCount++;
                                @endphp
                                @if($trx->status_verifikasi === 'verified')
                                    @if(Auth::user()->role !== 'konsolidator')
                                    <a href="{{ route('transaksi.upload', $trx->id) }}" class="inline-flex items-center gap-1 {{ $docCount > 0 ? 'text-secondary hover:text-secondary-container' : 'text-primary hover:text-primary-container' }} transition-colors text-label-sm font-label-sm" title="Kelola Dokumen">
                                        <span class="material-symbols-outlined text-[18px]">folder_open</span>
                                        {{ $docCount > 0 ? $docCount . '/4' : 'Upload' }}
                                    </a>
                                    @else
                                    <span class="inline-flex items-center gap-1 {{ $docCount > 0 ? 'text-secondary' : 'text-on-surface-variant/50' }} text-label-sm font-label-sm" title="Dokumen">
                                        <span class="material-symbols-outlined text-[18px]">{{ $docCount > 0 ? 'folder' : 'folder_off' }}</span>
                                        {{ $docCount }}/4
                                    </span>
                                    @endif
                                @else
                                    <span class="text-on-surface-variant/50 text-label-sm" title="Verifikasi dulu untuk upload">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if(Auth::user()->role !== 'konsolidator')
                                    @if($trx->status_verifikasi !== 'verified' || Auth::user()->role === 'admin')
                                    <a href="{{ route('transaksi.edit', $trx->id) }}" class="inline-block text-primary hover:text-primary-container p-1 mx-1 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Transaksi ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error hover:text-error-container p-1 mx-1 transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-on-surface-variant">Belum ada data Transaksi.</td>
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
