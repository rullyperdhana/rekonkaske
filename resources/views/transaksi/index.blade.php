<x-app-layout>
<style>
    #appMain { max-width: 100% !important; }
</style>
    <div class="space-y-6">
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
                                <div class="space-y-1">
                                    @if($trx->status_verifikasi == 'verified')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-700 font-label-sm text-[11px] font-bold">
                                        <span class="material-symbols-outlined text-[14px]">verified</span> Diverifikasi SKPD
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container text-on-surface-variant font-label-sm text-[11px] font-semibold border border-outline-variant">
                                        <span class="material-symbols-outlined text-[14px]">edit_note</span> Draft
                                    </span>
                                    @endif

                                    <!-- Status Konsolidator -->
                                    <div>
                                        @if($trx->status_konsolidator === 'valid')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-600 text-white font-label-sm text-[10px] font-bold shadow-xs">
                                                <span class="material-symbols-outlined text-[13px]">check_circle</span> Valid Konsolidator
                                            </span>
                                        @elseif($trx->status_konsolidator === 'perlu_perbaikan')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-rose-600 text-white font-label-sm text-[10px] font-bold shadow-xs">
                                                <span class="material-symbols-outlined text-[13px]">error</span> Perlu Perbaikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-500/10 text-blue-700 font-label-sm text-[10px] font-medium border border-blue-500/20">
                                                <span class="material-symbols-outlined text-[13px]">pending</span> Menunggu Cek
                                            </span>
                                        @endif
                                    </div>

                                    @if($trx->catatan_konsolidator_terakhir)
                                    <div class="text-[11px] text-rose-700 font-medium italic flex items-start gap-1 mt-0.5 bg-rose-500/5 p-1 rounded border border-rose-500/20" title="{{ $trx->catatan_konsolidator_terakhir }}">
                                        <span class="material-symbols-outlined text-[13px] text-rose-600 shrink-0 mt-0.5">comment</span>
                                        <span>Catatan: {{ \Illuminate\Support\Str::limit($trx->catatan_konsolidator_terakhir, 40) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $docCount = 0;
                                    if($trx->file_ba_manual) $docCount++;
                                    if($trx->file_buku_kas) $docCount++;
                                    if($trx->file_buku_pembantu_bank) $docCount++;
                                    if($trx->file_rekening_koran) $docCount++;
                                @endphp
                                <a href="{{ route('transaksi.upload', $trx->id) }}" class="inline-flex items-center gap-1 {{ $docCount >= 4 ? 'text-emerald-600 font-bold' : ($docCount > 0 ? 'text-secondary hover:text-secondary-container' : 'text-primary hover:text-primary-container') }} transition-colors text-label-sm font-label-sm" title="Lihat / Kelola 4 Berkas Dokumen">
                                    <span class="material-symbols-outlined text-[18px]">folder_open</span>
                                    <span>{{ $docCount }}/4</span>
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Tombol Pemeriksaan Konsolidator & Admin -->
                                    @if(in_array(Auth::user()->role, ['admin', 'konsolidator']))
                                    <a href="{{ route('transaksi.pemeriksaan', $trx->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-on-primary transition-all" title="Pemeriksaan & Catatan Konsolidator">
                                        <span class="material-symbols-outlined text-[18px]">fact_check</span>
                                    </a>
                                    @endif

                                    <!-- Tombol Reset ke Draft Khusus Admin -->
                                    @if(Auth::user()->role === 'admin' && $trx->status_verifikasi === 'verified')
                                    <form action="{{ route('transaksi.reset-draft', $trx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Kembalikan transaksi {{ $trx->skpd->nama ?? '' }} ke status DRAFT agar SKPD dapat memperbaikinya?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 hover:bg-amber-500 hover:text-white transition-all" title="Kembalikan ke Draft">
                                            <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Tombol Edit & Hapus untuk Operator / Admin -->
                                    @if(Auth::user()->role !== 'konsolidator')
                                        @if($trx->status_verifikasi !== 'verified' || Auth::user()->role === 'admin')
                                        <a href="{{ route('transaksi.edit', $trx->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-primary hover:bg-primary/10 transition-colors" title="Edit Transaksi">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" class="inline-block form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-error hover:bg-error/10 transition-colors" title="Hapus Transaksi">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                        @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-on-surface-variant/40" title="Terkunci (Sudah Diverifikasi)">
                                            <span class="material-symbols-outlined text-[18px]">lock</span>
                                        </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-on-surface-variant">Belum ada data Transaksi.</td>
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
