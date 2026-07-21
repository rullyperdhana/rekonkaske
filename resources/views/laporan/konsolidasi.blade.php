<x-app-layout>
@section('title', 'Laporan Konsolidasi Daerah')

<div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h2 class="text-headline-sm font-headline-sm text-on-surface">Laporan Konsolidasi Kas Daerah</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Rangkuman Saldo BKU dan Bank seluruh SKPD pada Bulan {{ $namaBulan[$selectedBulan - 1] }} Tahun {{ $tahunAktif }}.</p>
    </div>
</div>

<div class="bg-surface rounded-xl shadow-sm border border-outline-variant p-6 mb-8">
    <form action="{{ route('laporan.konsolidasi') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label for="bulan" class="block text-label-md font-label-md text-on-surface mb-1">Pilih Bulan</label>
            <select name="bulan" id="bulan" class="w-full h-11 px-3 rounded-lg border border-outline bg-surface text-body-md focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $selectedBulan == $i ? 'selected' : '' }}>{{ $namaBulan[$i - 1] }}</option>
                @endfor
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="h-11 px-6 bg-primary text-on-primary hover:bg-primary/90 rounded-lg flex items-center gap-2 font-label-md transition-colors shadow-sm">
                <span class="material-symbols-outlined" data-weight="fill">search</span>
                Tampilkan
            </button>
            <a href="{{ route('laporan.konsolidasi.pdf', ['bulan' => $selectedBulan]) }}" target="_blank" class="h-11 px-6 bg-tertiary text-on-tertiary hover:bg-tertiary/90 rounded-lg flex items-center gap-2 font-label-md transition-colors shadow-sm">
                <span class="material-symbols-outlined" data-weight="fill">print</span>
                Cetak PDF
            </a>
        </div>
    </form>
</div>

<div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden mb-8">
    <div class="p-6 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
        <h3 class="text-title-md font-title-md text-on-surface">Data Konsolidasi Seluruh SKPD</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface w-12 text-center">No</th>
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface w-32">Kode SKPD</th>
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface">Nama SKPD</th>
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface text-right">Saldo BKU (Rp)</th>
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface text-right">Saldo Bank (Rp)</th>
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface text-right">Selisih (Rp)</th>
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface text-center">Status</th>
                    <th class="px-4 py-3 text-label-md font-label-md font-semibold text-on-surface text-center w-16">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @foreach($konsolidasiData as $index => $data)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-4 py-3 text-body-md text-on-surface text-center">{{ ($konsolidasiData->currentPage() - 1) * $konsolidasiData->perPage() + $index + 1 }}</td>
                    <td class="px-4 py-3 text-body-md font-medium text-on-surface">{{ $data['skpd']->kode }}</td>
                    <td class="px-4 py-3 text-body-md text-on-surface">{{ $data['skpd']->nama }}</td>
                    
                    @if($data['is_exist'])
                        <td class="px-4 py-3 text-body-md text-on-surface text-right font-data-tabular">{{ number_format($data['bku'], 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-body-md text-on-surface text-right font-data-tabular">{{ number_format($data['bank'], 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-body-md text-right font-data-tabular">
                            @if($data['selisih'] > 0)
                                <span class="text-error font-bold">{{ number_format($data['selisih'], 2, ',', '.') }}</span>
                            @else
                                <span class="text-secondary font-bold">0,00</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-body-sm text-center">
                            @if($data['status'] === 'verified')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/50 text-on-secondary-container text-xs font-bold uppercase tracking-wider">Verified</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-variant text-on-surface-variant text-xs font-bold uppercase tracking-wider">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($data['skpd']->no_whatsapp)
                                @php
                                    $pesanWa = '';
                                    if ($data['status'] === 'verified') {
                                        $pesanWa = "Yth. Admin SKPD {$data['skpd']->nama}.\nLaporan rekonsiliasi Anda untuk Bulan {$selectedBulan} telah Selesai Diverifikasi. Terima kasih atas kerja samanya.";
                                    } else {
                                        $pesanWa = "Yth. Admin SKPD {$data['skpd']->nama}.\nLaporan Anda untuk Bulan {$selectedBulan} masih berstatus Draft. Mohon segera pastikan data sudah benar dan unggah dokumen pendukung agar dapat kami verifikasi.";
                                    }
                                @endphp
                                <a href="{{ $data['skpd']->getWhatsappUrl($pesanWa) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors" title="Kirim Pesan WA">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/></svg>
                                </a>
                            @else
                                <span class="text-xs text-outline italic" title="Nomor WA belum disetting">No WA</span>
                            @endif
                        </td>
                    @else
                        <td colspan="4" class="px-4 py-3 text-body-md text-error text-center italic opacity-70">Belum Lapor / Tidak Ada Transaksi</td>
                        <td class="px-4 py-3 text-center">
                            @if($data['skpd']->no_whatsapp)
                                @php
                                    $pesanWa = "Yth. Admin SKPD {$data['skpd']->nama}.\nKami menunggu pelaporan rekonsiliasi kas Anda untuk Bulan {$selectedBulan}. Mohon segera diinput ke aplikasi SiReKe. Terima kasih.";
                                @endphp
                                <a href="{{ $data['skpd']->getWhatsappUrl($pesanWa) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors" title="Kirim Peringatan WA">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/></svg>
                                </a>
                            @else
                                <span class="text-xs text-outline italic" title="Nomor WA belum disetting">No WA</span>
                            @endif
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-primary-container/20 border-t-2 border-outline-variant">
                    <td colspan="3" class="px-4 py-4 text-title-sm font-bold text-on-surface text-right uppercase tracking-wider">Grand Total Kas Daerah</td>
                    <td class="px-4 py-4 text-title-sm font-bold text-on-surface text-right font-data-tabular">Rp {{ number_format($totalBku, 2, ',', '.') }}</td>
                    <td class="px-4 py-4 text-title-sm font-bold text-on-surface text-right font-data-tabular">Rp {{ number_format($totalBank, 2, ',', '.') }}</td>
                    <td class="px-4 py-4 text-title-sm font-bold text-right font-data-tabular {{ abs($totalBku - $totalBank) > 0 ? 'text-error' : 'text-secondary' }}">
                        Rp {{ number_format(abs($totalBku - $totalBank), 2, ',', '.') }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    @if($konsolidasiData->hasPages())
    <div class="p-6 border-t border-outline-variant bg-surface-container-low">
        {{ $konsolidasiData->links() }}
    </div>
    @endif
</div>

</x-app-layout>
