<x-app-layout>
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: 215mm 330mm; /* F4 / Folio Size */
                margin: 25mm 20mm;
            }
            .max-w-\[215mm\] {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    <!-- Print Action Bar -->
    <div class="no-print bg-surface-container-lowest border-b border-outline-variant sticky top-0 z-10 p-4 shadow-sm flex items-center justify-between">
        <a href="{{ route('ba.index') }}" class="text-primary hover:bg-primary/10 px-4 py-2 rounded-lg font-label-sm text-label-sm transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
            Kembali
        </a>
        <a href="{{ route('ba.pdf', $transaksi->id) }}" target="_blank" class="bg-primary text-on-primary hover:bg-primary/90 px-6 py-2 rounded-lg font-label-sm text-label-sm transition-colors shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">print</span>
            Cetak Berita Acara (PDF)
        </a>
    </div>

    <!-- Paper Container -->
    <div class="max-w-[215mm] mx-auto bg-white p-[20mm] md:shadow-md md:my-8 min-h-[330mm]">
        
        <!-- Document Content -->
        <div class="max-w-3xl mx-auto">
            
            <!-- KOP Surat (Formal Header) -->
            <div class="flex items-center gap-6 border-b-[3px] border-black pb-4 mb-8">
                <div class="w-24 h-24 shrink-0 flex items-center justify-center">
                    @php 
                        $globalLogo = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
                        if ($globalLogo && str_starts_with($globalLogo, 'logos/')) {
                            $logoUrl = asset('storage/' . $globalLogo);
                        } elseif ($globalLogo && filter_var($globalLogo, FILTER_VALIDATE_URL)) {
                            $logoUrl = $globalLogo;
                        } else {
                            $logoUrl = null;
                        }
                    @endphp
                    @if($logoUrl)
                        <img class="object-contain h-full w-full" data-alt="Logo" src="{{ $logoUrl }}"/>
                    @else
                        <!-- No logo placeholder -->
                        <div class="w-full h-full flex items-center justify-center border border-dashed border-gray-300 rounded text-gray-400 text-xs text-center p-2">
                            Belum ada Logo (Atur di Pengaturan Instansi)
                        </div>
                    @endif
                </div>
                <div class="flex-1 text-center text-black">
                    @php
                        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KABUPATEN TAPIN|BADAN KEUANGAN DAN ASET DAERAH|Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru|RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173');
                    @endphp
                    @foreach($lines as $index => $line)
                        @if($index === 0)
                            <h2 class="text-xl font-bold uppercase leading-tight tracking-wide">{{ $line }}</h2>
                        @elseif($index === 1)
                            <h1 class="text-2xl font-black uppercase leading-tight tracking-wide">{{ $line }}</h1>
                        @elseif($index === 2)
                            <p class="text-sm mt-1">{{ $line }}</p>
                        @else
                            <p class="text-sm">{{ $line }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Document Title -->
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold uppercase underline underline-offset-4 decoration-2 text-black">BERITA ACARA REKONSILIASI</h2>
                <h3 class="text-lg font-bold mt-2 text-black">Bulan : {{ strtoupper(date('F', mktime(0, 0, 0, $transaksi->periode_bulan, 10))) }} {{ $transaksi->periode_tahun }}</h3>
            </div>
            
            <!-- Introductory Text -->
            <div class="text-base text-justify space-y-4 mb-8 leading-relaxed text-black">
                @php
                    $tglSumber = $transaksi->tanggal_ba ? \Carbon\Carbon::parse($transaksi->tanggal_ba) : \Carbon\Carbon::parse($transaksi->updated_at);
                    
                    $tanggal = $tglSumber->locale('id')->isoFormat('dddd');
                    $tglNum = $tglSumber->format('d');
                    $bulanLengkap = $tglSumber->locale('id')->isoFormat('MMMM');
                    $tahunLengkap = $tglSumber->format('Y');
                    
                    $akhirBulan = \Carbon\Carbon::createFromDate($transaksi->periode_tahun, $transaksi->periode_bulan, 1)->endOfMonth()->locale('id')->isoFormat('D MMMM YYYY');
                    
                    $namaInstansi = $lines[1] ?? 'Badan Keuangan dan Aset Daerah';
                    $namaPemda = $lines[0] ?? 'Kabupaten Tapin';
                @endphp
                <p class="indent-10">
                    Pada hari ini {{ $tanggal }} Tanggal {{ $tglNum }} Bulan {{ $bulanLengkap }} Tahun {{ $tahunLengkap }}, telah dilakukan rekonsiliasi Saldo Kas Bendahara Pengeluaran per {{ $akhirBulan }} pada {{ ucwords(strtolower($namaInstansi)) }} {{ ucwords(strtolower($namaPemda)) }}.
                </p>
                <p class="indent-10">
                    Dengan mencocokkan BKU Bendahara Pengeluaran per {{ $akhirBulan }} pada Aplikasi SIPANDA dengan Rekening Koran Bank Kalsel per {{ $akhirBulan }} dengan hasil sebagai berikut :
                </p>
            </div>
            
            <!-- Financial Table -->
            <div class="border border-black mb-8 overflow-hidden rounded-sm text-black">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-black">
                            <th class="py-2 px-3 text-center border-r-[1px] border-black font-bold" colspan="2">BKU Bendahara Pengeluaran</th>
                            <th class="py-2 px-3 text-center font-bold" colspan="2">Rekening Koran Bank Kalsel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-black/30">
                            <td class="py-2 px-3 font-semibold">Saldo Kas Awal</td>
                            <td class="py-2 px-3 text-right font-data-tabular font-bold border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_saldo_awal, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-2 px-3 font-semibold">Saldo Kas Awal</td>
                            <td class="py-2 px-3 text-right font-data-tabular font-bold">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_saldo_awal, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Ditambah -->
                        <tr>
                            <td class="py-2 px-3">Ditambah:</td>
                            <td class="py-2 px-3 border-r-[1px] border-black"></td>
                            <td class="py-2 px-3">Ditambah:</td>
                            <td class="py-2 px-3"></td>
                        </tr>
                        <tr>
                            <td class="py-1 px-3 pl-8">Penerimaan</td>
                            <td class="py-1 px-3 text-right font-data-tabular border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_penerimaan, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-1 px-3 pl-8">Penerimaan</td>
                            <td class="py-1 px-3 text-right font-data-tabular">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_penerimaan, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Dikurang -->
                        <tr>
                            <td class="py-2 px-3 mt-2">Dikurang:</td>
                            <td class="py-2 px-3 border-r-[1px] border-black"></td>
                            <td class="py-2 px-3 mt-2">Dikurang:</td>
                            <td class="py-2 px-3"></td>
                        </tr>
                        <tr class="border-b border-black">
                            <td class="py-1 px-3 pl-8 pb-3">Pengeluaran</td>
                            <td class="py-1 px-3 pb-3 text-right font-data-tabular border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_pengeluaran, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-1 px-3 pl-8 pb-3">Pengeluaran</td>
                            <td class="py-1 px-3 pb-3 text-right font-data-tabular">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_pengeluaran, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Saldo Akhir -->
                        <tr class="font-bold border-b border-black">
                            <td class="py-2 px-3">Saldo Akhir Kas</td>
                            <td class="py-2 px-3 text-right font-data-tabular border-r-[1px] border-black">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</span></div>
                            </td>
                            <td class="py-2 px-3">Saldo Akhir Kas</td>
                            <td class="py-2 px-3 text-right font-data-tabular">
                                <div class="flex justify-between w-full"><span>Rp</span><span>{{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        <!-- Selisih -->
                        @php
                            $selisih = $transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir;
                        @endphp
                        <tr class="font-bold bg-gray-100 print:bg-transparent">
                            <td class="py-2 px-3 text-center border-r-[1px] border-black italic" colspan="2">Selisih</td>
                            <td class="py-2 px-3 text-right font-data-tabular" colspan="2">
                                <div class="flex justify-center w-full gap-4 {{ abs($selisih) > 0 ? 'text-red-600 print:text-black' : '' }}"><span>Rp</span><span>{{ number_format($selisih, 2, ',', '.') }}</span></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Penjelasan -->
            @if(abs($selisih) > 0)
            <div class="mb-8 text-black">
                <h4 class="text-base font-bold mb-1">Penjelasan :</h4>
                <p class="text-sm leading-relaxed text-justify">
                    {{ $transaksi->keterangan_selisih ?: '-' }}
                </p>
            </div>
            @endif
            
            <!-- Lampiran Note -->
            <div class="mb-10 text-sm font-medium text-black">
                ** Rincian terlampir
            </div>
            
            <!-- Signatures Section -->
            <div class="grid grid-cols-2 gap-8 mb-12 text-black text-sm">
                <div class="text-center">
                    <p class="mb-20">Pembuatan Laporan,<br>{{ $pengaturan->jabatan_bendahara ?? 'Bendahara Pengeluaran' }}</p>
                    <p class="font-bold underline underline-offset-2 uppercase">{{ $pengaturan->nama_bendahara ?? '.........................' }}</p>
                    <p>{{ $pengaturan->pangkat_bendahara ?? '.........................' }}</p>
                    <p>NIP. {{ $pengaturan->nip_bendahara ?? '.........................' }}</p>
                </div>
                <div class="text-center">
                    <p class="mb-20">Menyetujui,<br>{{ $pengaturan->jabatan_kasubag ?? 'Kasubag Keuangan' }}</p>
                    <p class="font-bold underline underline-offset-2 uppercase">{{ $pengaturan->nama_kasubag ?? '.........................' }}</p>
                    <p>{{ $pengaturan->pangkat_kasubag ?? '.........................' }}</p>
                    <p>NIP. {{ $pengaturan->nip_kasubag ?? '.........................' }}</p>
                </div>
                
                <!-- Bawah Tengah: Kepala SKPD -->
                <div class="col-span-2 text-center mt-12">
                    @php
                        // Coba cari kota dari isi_kop
                        $kotaFallback = 'Rantau';
                        $lastLine = end($lines);
                        if(stripos($lastLine, 'Rantau') !== false) {
                            $kotaFallback = 'Rantau';
                        }
                    @endphp
                    <p class="mb-1">{{ $kotaFallback }}, {{ $tglSumber->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    <p class="font-bold mb-16">Mengetahui,<br>{{ $pengaturan->jabatan_kepala ?? 'Pengguna Anggaran / Kuasa Pengguna Anggaran' }}</p>
                    <p class="font-bold underline underline-offset-2 uppercase">{{ $pengaturan->nama_kepala ?? '.........................' }}</p>
                    <p>{{ $pengaturan->pangkat_kepala ?? '.........................' }}</p>
                    <p>NIP. {{ $pengaturan->nip_kepala ?? '.........................' }}</p>
                </div>
            </div>
            
            <div class="flex justify-between items-end text-black text-sm mt-8">
                <!-- Verified QR Code -->
                <div>
                    @if($transaksi->status_verifikasi === 'verified')
                    <div class="flex items-center gap-4 border border-dashed border-gray-300 p-2 rounded-lg bg-gray-50/50 print:border-none print:p-0 print:bg-transparent">
                        <img src="data:image/png;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(80)->margin(0)->generate(route('ba.pdf', $transaksi->id))) !!}" alt="QR Code" class="w-16 h-16">
                        <div class="text-[10px] leading-snug text-left">
                            <span class="font-bold text-gray-800 print:text-black">Dokumen Sah</span><br>
                            <span class="text-gray-600 print:text-black">Dicetak secara elektronik dari sistem</span><br>
                            <i class="text-primary font-medium print:text-black">sireke.cloud</i>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Lampiran List -->
                <div class="text-right">
                    <p class="font-bold italic mb-1 text-left">Lampiran :</p>
                    <ol class="list-decimal list-inside italic">
                        <li>Buku Kas Pengeluaran</li>
                        <li>Buku Pembantu Bank</li>
                        <li>Rekening Koran Bank</li>
                    </ol>
                </div>
            </div>
        </article>
    </div>

    <style>
        @media print {
            body {
                background: white !important;
            }
            .max-w-\[1000px\] {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            header, nav, aside, .mb-6 {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
