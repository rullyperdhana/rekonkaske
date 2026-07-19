<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Rekonsiliasi - {{ $transaksi->skpd->nama }}</title>
    <style>
        @page { margin: 15mm 20mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .italic { font-style: italic; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        .mt-2 { margin-top: 10px; }
        .mt-4 { margin-top: 20px; }
        .indent { text-indent: 40px; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 15px; padding-bottom: 10px; }
        .kop-logo { width: 90px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 80px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 90px; } 
        .kop-text h2 { margin: 0; font-size: 18px; font-weight: bold; letter-spacing: 1px; }
        .kop-text h1 { margin: 0; font-size: 22px; font-weight: 900; letter-spacing: 1px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 12px; }
        
        /* Judul */
        .judul-dokumen h2 { margin: 0; font-size: 18px; font-weight: bold; text-decoration: underline; }
        .judul-dokumen h3 { margin: 5px 0 0 0; font-size: 16px; font-weight: bold; }

        /* Tabel Keuangan */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 13px;
            border: 1px solid #000;
        }
        table.keuangan th, table.keuangan td {
            padding: 4px 6px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        table.keuangan th:last-child, table.keuangan td:last-child {
            border-right: none;
        }
        .pl-4 { padding-left: 20px; }

        /* Tanda Tangan */
        .ttd-table { width: 100%; margin-top: 20px; font-size: 13px; page-break-inside: avoid; border: none; }
        .ttd-table td { border: none; padding: 2px; }
        .ttd-cell { width: 50%; text-align: center; vertical-align: top; }
        .ttd-space { height: 60px; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-bottom: 2px; }
        .ttd-nip { margin-top: 0; }
        
        /* Helper Table for Currency */
        .curr-table { width: 100%; border: none !important; border-collapse: collapse; margin: 0; padding: 0; }
        .curr-table td { border: none !important; padding: 0 !important; background: transparent; }
        .curr-symbol { text-align: left; width: 25px; }
        .curr-val { text-align: right; }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 35%;
            left: 10%;
            font-size: 140px;
            color: rgba(255, 0, 0, 0.15);
            font-weight: bold;
            transform: rotate(-45deg);
            z-index: -1000;
            user-select: none;
            pointer-events: none;
        }
    </style>
</head>
<body>

    @if($transaksi->status_verifikasi === 'draft')
        <div class="watermark">DRAFT</div>
    @endif

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KABUPATEN TAPIN|BADAN KEUANGAN DAN ASET DAERAH|Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru|RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173');
        
        $logoSrc = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
        $base64Logo = null;
        
        if($logoSrc && str_starts_with($logoSrc, 'logos/')) {
            $path = storage_path('app/public/' . $logoSrc);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        } elseif ($logoSrc && filter_var($logoSrc, FILTER_VALIDATE_URL)) {
            // If it's a URL (like from old config)
            try {
                $data = @file_get_contents($logoSrc);
                if ($data) {
                    $type = 'png';
                    $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {}
        }
    @endphp

    <!-- KOP Surat -->
    <table class="kop-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="kop-logo">
                @if($base64Logo)
                    <img src="{{ $base64Logo }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                @foreach($lines as $index => $line)
                    @if($index === 0)
                        <h2 class="uppercase">{{ $line }}</h2>
                    @elseif($index === 1)
                        <h1 class="uppercase">{{ $line }}</h1>
                    @elseif($index === 2)
                        <p style="margin-top:5px;">{{ $line }}</p>
                    @else
                        <p>{{ $line }}</p>
                    @endif
                @endforeach
            </td>
        </tr>
    </table>

    <!-- Judul -->
    <div class="text-center judul-dokumen mb-4">
        <h2 class="uppercase">BERITA ACARA REKONSILIASI</h2>
        <h3 class="uppercase">Bulan : {{ date('F', mktime(0, 0, 0, $transaksi->periode_bulan, 10)) }} {{ $transaksi->periode_tahun }}</h3>
    </div>

    <!-- Intro Text -->
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

    <p class="text-justify indent mb-2">
        Pada hari ini {{ $tanggal }} Tanggal {{ $tglNum }} Bulan {{ $bulanLengkap }} Tahun {{ $tahunLengkap }}, telah dilakukan rekonsiliasi Saldo Kas Bendahara Pengeluaran per {{ $akhirBulan }} pada {{ ucwords(strtolower($namaInstansi)) }} {{ ucwords(strtolower($namaPemda)) }}.
    </p>
    <p class="text-justify indent mb-4">
        Dengan mencocokkan BKU Bendahara Pengeluaran per {{ $akhirBulan }} pada Aplikasi SIPANDA dengan Rekening Koran Bank Kalsel per {{ $akhirBulan }} dengan hasil sebagai berikut :
    </p>

    <!-- Tabel Keuangan -->
    <table class="keuangan" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th colspan="2" class="text-center font-bold">BKU Bendahara Pengeluaran</th>
                <th colspan="2" class="text-center font-bold">Rekening Koran Bank Kalsel</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">Saldo Kas Awal</td>
                <td class="font-bold">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_saldo_awal, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="font-bold">Saldo Kas Awal</td>
                <td class="font-bold">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_saldo_awal, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <tr>
                <td>Ditambah:</td>
                <td></td>
                <td>Ditambah:</td>
                <td></td>
            </tr>
            <tr>
                <td class="pl-4">Penerimaan</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_penerimaan, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="pl-4">Penerimaan</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_penerimaan, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <tr>
                <td>Dikurang:</td>
                <td></td>
                <td>Dikurang:</td>
                <td></td>
            </tr>
            <tr>
                <td class="pl-4 pb-2">Pengeluaran</td>
                <td class="pb-2">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_pengeluaran, 2, ',', '.') }}</td></tr></table>
                </td>
                <td class="pl-4 pb-2">Pengeluaran</td>
                <td class="pb-2">
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_pengeluaran, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <tr class="font-bold">
                <td>Saldo Akhir Kas</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</td></tr></table>
                </td>
                <td>Saldo Akhir Kas</td>
                <td>
                    <table class="curr-table"><tr><td class="curr-symbol">Rp</td><td class="curr-val">{{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</td></tr></table>
                </td>
            </tr>
            <!-- Selisih -->
            @php $selisih = $transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir; @endphp
            <tr class="font-bold" style="{{ abs($selisih) > 0 ? 'color: #d32f2f; background-color: #fee2e2;' : '' }}">
                <td colspan="2" class="text-center italic">Selisih</td>
                <td colspan="2">
                    <table class="curr-table" style="{{ abs($selisih) > 0 ? 'color: #d32f2f;' : '' }}">
                        <tr>
                            <td class="curr-symbol">Rp</td>
                            <td class="curr-val">{{ number_format($selisih, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Penjelasan -->
    @if(abs($selisih) > 0)
    <div class="mb-4 text-justify">
        <span class="font-bold">Penjelasan :</span><br>
        {{ $transaksi->keterangan_selisih ?: '-' }}
    </div>
    @endif

    <div class="mb-4 text-sm font-bold">
        ** Rincian terlampir
    </div>

    <!-- Tanda Tangan -->
    @php
        $kotaFallback = 'Rantau';
        $lastLine = end($lines);
        if(stripos($lastLine, 'Rantau') !== false) {
            $kotaFallback = 'Rantau';
        }
    @endphp

    <table class="ttd-table text-center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="ttd-cell">
                Pembuatan Laporan,<br>
                {{ $pengaturan->jabatan_bendahara ?? 'Bendahara Pengeluaran' }}
                <div class="ttd-space"></div>
                <div class="ttd-name uppercase">{{ $pengaturan->nama_bendahara ?? '.........................' }}</div>
                <div class="ttd-nip">{{ $pengaturan->pangkat_bendahara ?? '.........................' }}</div>
                <div class="ttd-nip">NIP. {{ $pengaturan->nip_bendahara ?? '.........................' }}</div>
            </td>
            <td class="ttd-cell">
                Menyetujui,<br>
                {{ $pengaturan->jabatan_kasubag ?? 'Kasubag Keuangan' }}
                <div class="ttd-space"></div>
                <div class="ttd-name uppercase">{{ $pengaturan->nama_kasubag ?? '.........................' }}</div>
                <div class="ttd-nip">{{ $pengaturan->pangkat_kasubag ?? '.........................' }}</div>
                <div class="ttd-nip">NIP. {{ $pengaturan->nip_kasubag ?? '.........................' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="ttd-cell" style="padding-top: 20px; text-align: center;">
                {{ $kotaFallback }}, {{ $tglSumber->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                <span class="font-bold">Mengetahui,</span><br>
                <span class="font-bold">{{ $pengaturan->jabatan_kepala ?? 'Pengguna Anggaran / Kuasa Pengguna Anggaran' }}</span>
                <div class="ttd-space"></div>
                <div class="ttd-name uppercase">{{ $pengaturan->nama_kepala ?? '.........................' }}</div>
                <div class="ttd-nip">{{ $pengaturan->pangkat_kepala ?? '.........................' }}</div>
                <div class="ttd-nip">NIP. {{ $pengaturan->nip_kepala ?? '.........................' }}</div>
            </td>
        </tr>
    </table>
    
    @if($transaksi->status_verifikasi === 'verified')
    <div style="margin-top: 20px; text-align: left; display: table;">
        <div style="display: table-cell; vertical-align: middle; padding-right: 15px;">
            <img src="data:image/png;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(80)->margin(0)->generate(route('ba.pdf', $transaksi->id))) !!}" alt="QR Code">
        </div>
        <div style="display: table-cell; vertical-align: middle; font-size: 10px; line-height: 1.2;">
            <b>Dokumen Sah</b><br>
            Dicetak secara elektronik dari sistem<br>
            <i>sireke.cloud</i>
        </div>
    </div>
    @endif

    <!-- Footer Lampiran -->
    <div style="margin-top: 20px;">
        <span class="font-bold italic">Lampiran :</span>
        <ol class="italic" style="margin-top: 5px; padding-left: 20px; font-size: 13px;">
            <li>Buku Kas Pengeluaran</li>
            <li>Buku Pembantu Bank</li>
            <li>Rekening Koran Bank</li>
        </ol>
    </div>

</body>
</html>
