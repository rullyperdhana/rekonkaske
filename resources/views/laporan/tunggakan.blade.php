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
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface text-center w-20">Aksi</th>
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
                    <td class="px-6 py-4 text-center">
                        @if($item['skpd']->no_whatsapp)
                            @php
                                $pesanWa = "Yth. Admin SKPD {$item['skpd']->nama}.\nKami menginformasikan bahwa SKPD Anda belum melakukan pelaporan rekonsiliasi kas sejak Bulan {$item['bulan_terakhir']}. Saat ini Anda menunggak pelaporan selama {$item['tunggakan_bulan']} Bulan. Mohon segera diselesaikan melalui aplikasi SiReKe. Terima kasih.";
                            @endphp
                            <a href="{{ $item['skpd']->getWhatsappUrl($pesanWa) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors" title="Kirim Peringatan WA">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/></svg>
                            </a>
                        @else
                            <span class="text-xs text-outline italic" title="Nomor WA belum disetting">No WA</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
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
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('transaksi.edit', $trx->id) }}" class="text-primary hover:underline text-label-sm font-label-sm">Lihat Detail &rarr;</a>
                                        @if($item['skpd']->no_whatsapp)
                                            @php
                                                $nilaiSelisih = number_format(abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir), 0, ',', '.');
                                                $pesanWa = "Yth. Admin SKPD {$item['skpd']->nama}.\nKami menemukan adanya SELISIH SALDO pada pelaporan Anda di Bulan {$trx->periode_bulan}. Terdapat selisih sebesar Rp {$nilaiSelisih} antara saldo BKU dan saldo Bank. Mohon segera diperiksa dan diperbaiki di aplikasi SiReKe. Terima kasih.";
                                            @endphp
                                            <a href="{{ $item['skpd']->getWhatsappUrl($pesanWa) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors" title="Kirim Peringatan WA">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/></svg>
                                            </a>
                                        @endif
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

<!-- Tabel Tanpa Dokumen -->
<div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden mb-8">
    <div class="p-6 border-b border-outline-variant bg-tertiary-container/20 flex items-center gap-3">
        <div class="p-2 bg-tertiary/10 text-tertiary rounded-lg">
            <span class="material-symbols-outlined">description</span>
        </div>
        <div>
            <h3 class="text-title-md font-title-md text-on-surface">SKPD Belum Upload Dokumen Pendukung Lengkap</h3>
            <p class="text-body-sm text-on-surface-variant">Daftar SKPD yang sudah menginput transaksi namun belum melampirkan ke-empat file dokumen wajib (Berita Acara, Buku Kas, Buku Pembantu Bank, dan Rekening Koran).</p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface w-16 text-center">No</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface">Nama SKPD</th>
                    <th class="px-6 py-4 text-label-md font-label-md font-semibold text-on-surface">Bulan Tanpa Dokumen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @forelse($dataTanpaDokumenPaginated as $index => $item)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 text-body-md text-on-surface text-center align-top">{{ ($dataTanpaDokumenPaginated->currentPage() - 1) * $dataTanpaDokumenPaginated->perPage() + $index + 1 }}</td>
                    <td class="px-6 py-4 text-body-md font-medium text-on-surface align-top">
                        <div class="font-bold">{{ $item['skpd']->kode }}</div>
                        <div class="text-sm text-on-surface-variant">{{ $item['skpd']->nama }}</div>
                    </td>
                    <td class="px-6 py-4 text-body-md text-on-surface">
                        <div class="flex flex-col gap-2">
                            @foreach($item['transaksi'] as $trx)
                                <div class="bg-surface-container-lowest p-3 border border-outline-variant rounded-lg flex justify-between items-center">
                                    <div>
                                        <div class="font-bold text-label-md flex items-center gap-2">
                                            Bulan {{ $trx->periode_bulan }}
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $trx->status_verifikasi == 'verified' ? 'bg-secondary/10 text-secondary' : 'bg-outline-variant/30 text-on-surface-variant' }}">
                                                {{ $trx->status_verifikasi }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-on-surface-variant mt-1 flex gap-1">
                                            @if(!$trx->file_ba_manual) <span class="bg-error/10 text-error px-1 rounded">BA</span> @endif
                                            @if(!$trx->file_buku_kas) <span class="bg-error/10 text-error px-1 rounded">Kas</span> @endif
                                            @if(!$trx->file_buku_pembantu_bank) <span class="bg-error/10 text-error px-1 rounded">Bank</span> @endif
                                            @if(!$trx->file_rekening_koran) <span class="bg-error/10 text-error px-1 rounded">Rek.Koran</span> @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('transaksi.edit', $trx->id) }}" class="text-primary hover:underline text-label-sm font-label-sm">Lihat Detail &rarr;</a>
                                        @if($item['skpd']->no_whatsapp)
                                            @php
                                                $kurang = [];
                                                if(!$trx->file_ba_manual) $kurang[] = "Berita Acara";
                                                if(!$trx->file_buku_kas) $kurang[] = "Buku Kas";
                                                if(!$trx->file_buku_pembantu_bank) $kurang[] = "Buku Pembantu Bank";
                                                if(!$trx->file_rekening_koran) $kurang[] = "Rekening Koran";
                                                $kurangText = implode(', ', $kurang);
                                                
                                                $pesanWa = "Yth. Admin SKPD {$item['skpd']->nama}.\nKami menginformasikan bahwa pelaporan Anda untuk Bulan {$trx->periode_bulan} belum melampirkan dokumen pendukung lengkap. Berdasarkan data kami, Anda belum mengunggah: {$kurangText}. Mohon dokumen tersebut segera diunggah di aplikasi SiReKe agar verifikasi dapat dilanjutkan. Terima kasih.";
                                            @endphp
                                            <a href="{{ $item['skpd']->getWhatsappUrl($pesanWa) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors" title="Kirim Peringatan WA">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/></svg>
                                            </a>
                                        @endif
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
                        <p class="text-body-lg">Luar biasa! Semua transaksi saat ini memiliki dokumen pendukung.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dataTanpaDokumenPaginated->hasPages())
    <div class="p-6 border-t border-outline-variant bg-surface-container-low">
        {{ $dataTanpaDokumenPaginated->links() }}
    </div>
    @endif
</div>

</x-app-layout>
