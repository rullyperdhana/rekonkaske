<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Bukti Pemeriksaan Rekonsiliasi - {{ $transaksi->skpd->nama ?? '-' }}</title>
    <style>
        @page { margin: 15mm 20mm; size: a4 portrait; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 15px; padding-bottom: 6px; }
        .kop-logo { width: 75px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 65px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 75px; } 
        .kop-text h2 { margin: 0; font-size: 15px; font-weight: bold; letter-spacing: 0.5px; }
        .kop-text h1 { margin: 0; font-size: 17px; font-weight: 900; letter-spacing: 0.5px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 10px; color: #333; }
        
        /* Judul Dokumen */
        .header-doc {
            text-align: center;
            margin-bottom: 15px;
        }
        .header-doc h2 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }
        .register-badge {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 12px;
            font-size: 10.5px;
            font-weight: bold;
            font-family: monospace;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            color: #0f172a;
        }

        /* Tabel Data Info */
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3.5px 0;
            vertical-align: top;
            font-size: 11px;
        }
        .info-label { width: 150px; font-weight: bold; color: #475569; }
        .info-sep { width: 15px; text-align: center; }
        .info-value { font-weight: bold; color: #0f172a; }

        /* Tabel Data Saldo */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10.5px;
            border: 1px solid #cbd5e1;
        }
        table.keuangan th, table.keuangan td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
        }
        table.keuangan th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #334155;
        }
        .curr-table { width: 100%; border: none !important; border-collapse: collapse; margin: 0; padding: 0; }
        .curr-table td { border: none !important; padding: 0 !important; background: transparent; }
        .curr-symbol { text-align: left; width: 22px; }
        .curr-val { text-align: right; }

        /* Checklist Dokumen */
        table.checklist {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10.5px;
            border: 1px solid #cbd5e1;
        }
        table.checklist th, table.checklist td {
            padding: 4.5px 8px;
            border: 1px solid #cbd5e1;
        }
        table.checklist th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #334155;
        }

        /* Statement Box */
        .statement-box {
            background-color: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 16px;
            font-size: 10.5px;
            text-align: justify;
            color: #14532d;
            line-height: 1.4;
        }

        /* Tanda Tangan & Pengesahan */
        .auth-table { width: 100%; border: none; margin-top: 10px; page-break-inside: avoid; }
        .auth-table td { border: none; padding: 0; vertical-align: top; }
        
        /* Stempel Digital Kotak */
        .stamp-box {
            border: 2px dashed #059669;
            background-color: #f0fdf4;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            display: inline-block;
            width: 250px;
        }
        .stamp-title {
            font-size: 10px;
            font-weight: 900;
            color: #047857;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .stamp-meta {
            font-size: 9px;
            color: #065f46;
            margin-top: 4px;
            font-family: monospace;
        }

        /* Footer Watermark */
        .security-footer {
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 8.5px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KABUPATEN TAPIN|BADAN KEUANGAN DAN ASET DAERAH|Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru|RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173');
        $logoSrc = \App\Models\Pengaturan::whereNull('skpd_id')->first()->logo ?? null;
        $base64Logo = null;
        if ($logoSrc) {
            $path = public_path('storage/' . $logoSrc);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $regNo = 'REG-KONS/TAPIN/' . $transaksi->periode_tahun . '/' . str_pad($transaksi->periode_bulan, 2, '0', STR_PAD_LEFT) . '/' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT);
        $selisih = abs($transaksi->bku_saldo_akhir - $transaksi->bank_saldo_akhir);
        $witaTime = $transaksi->checked_at ? \Carbon\Carbon::parse($transaksi->checked_at)->timezone('Asia/Makassar')->format('d F Y, H:i:s') . ' WITA' : '-';
        
        $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.show', $transaksi->id);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($signedUrl);
        $qrData = @file_get_contents($qrUrl);
        $qrBase64 = $qrData ? base64_encode($qrData) : '';
    @endphp

    <!-- KOP Surat -->
    <table class="kop-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="kop-logo">
                @if($base64Logo)
                    <img src="{{ $base64Logo }}" alt="Logo">
                @else
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGQglX4a91lGBKJ3x84BjayBzB86CFjav3SqOK5oE63MWbYO2Qcazq0aldyUiq4O4QUHgyHX3dIYsy_YZxQrgNA3gnZu-9IDh5PBQyqlamviMO9EYFfXzj-ZmB1cLlx2nTyOGUzDWwaUmkCW2sxkgnhAFG2520U_AyWNIov7XjxkjfYKcEDsZudVlfdUva_l58gAIdKZlkfCSf_qyyKiJjlMlPtKy6VdEbjqUDxlo92seLSowz38NN" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                @if(count($lines) >= 2)
                    <h2>{{ $lines[0] }}</h2>
                    <h1>{{ $lines[1] }}</h1>
                    @for($i = 2; $i < count($lines); $i++)
                        <p>{{ $lines[$i] }}</p>
                    @endfor
                @else
                    <h2>PEMERINTAH KABUPATEN TAPIN</h2>
                    <h1>BADAN KEUANGAN DAN ASET DAERAH</h1>
                    <p>Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru</p>
                @endif
            </td>
        </tr>
    </table>

    <!-- Header Dokumen -->
    <div class="header-doc">
        <h2>SURAT TANDA BUKTI PEMERIKSAAN REKONSILIASI KAS DAERAH</h2>
        <div>
            <span class="register-badge">NO. REGISTRASI DIGITAL: {{ $regNo }}</span>
        </div>
    </div>

    <!-- Data Instansi & Rekening -->
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-label">Satuan Kerja (SKPD)</td>
            <td class="info-sep">:</td>
            <td class="info-value">{{ $transaksi->skpd->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor Rekening Kas</td>
            <td class="info-sep">:</td>
            <td class="info-value">{{ $transaksi->rekening->nomor ?? '-' }} ({{ $transaksi->rekening->nama ?? '-' }}) &bull; {{ $transaksi->rekening->bank ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Periode Rekonsiliasi</td>
            <td class="info-sep">:</td>
            <td class="info-value">{{ $namaBulan[$transaksi->periode_bulan - 1] }} {{ $transaksi->periode_tahun }} (Tahun Anggaran {{ $transaksi->periode_tahun }})</td>
        </tr>
        <tr>
            <td class="info-label">Status Verifikasi Awal</td>
            <td class="info-sep">:</td>
            <td class="info-value">Diverifikasi Mandiri oleh Operator SKPD pada {{ $transaksi->updated_at ? \Carbon\Carbon::parse($transaksi->updated_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') . ' WITA' : '-' }}</td>
        </tr>
    </table>

    <!-- Ringkasan Saldo Rekonsiliasi -->
    <table class="keuangan" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 35%;">Uraian Rekonsiliasi Saldo</th>
                <th style="width: 35%; text-align: right;">Nilai Rupiah</th>
                <th style="width: 30%; text-align: center;">Hasil Uji Kesesuaian</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1. Saldo Kas Buku Kas Umum (BKU SIPANDA)</td>
                <td>
                    <table class="curr-table">
                        <tr>
                            <td class="curr-symbol">Rp</td>
                            <td class="curr-val">{{ number_format($transaksi->bku_saldo_akhir, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
                <td rowspan="2" class="text-center" style="vertical-align: middle; font-weight: bold; background-color: #f0fdf4; color: #15803d;">
                    @if($selisih < 0.01)
                        SESUAI / KLOP (Rp 0)<br>
                        <span style="font-size: 9px; font-weight: normal; color: #166534;">(Saldo BKU = Bank)</span>
                    @else
                        TERDAPAT SELISIH<br>
                        <span style="font-size: 9px; color: #b91c1c;">Rp {{ number_format($selisih, 2, ',', '.') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>2. Saldo Rekening Koran Bank Kalsel</td>
                <td>
                    <table class="curr-table">
                        <tr>
                            <td class="curr-symbol">Rp</td>
                            <td class="curr-val">{{ number_format($transaksi->bank_saldo_akhir, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Checklist 4 Bukti Dukung Fisik -->
    <table class="checklist" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th>Jenis Bukti Dukung Fisik yang Telah Diperiksa</th>
                <th style="width: 120px; text-align: center;">Ketersediaan File</th>
                <th style="width: 140px; text-align: center;">Status Verifikasi Fisik</th>
            </tr>
        </thead>
        <tbody>
            @php
                $docsCheck = [
                    ['title' => 'Berita Acara (BA) Manual Instansi', 'field' => 'file_ba_manual'],
                    ['title' => 'Buku Kas Umum (BKU) Penutupan Kas', 'field' => 'file_buku_kas'],
                    ['title' => 'Buku Pembantu Bank Bendahara', 'field' => 'file_buku_pembantu_bank'],
                    ['title' => 'Rekening Koran Bank Kalsel', 'field' => 'file_rekening_koran'],
                ];
            @endphp
            @foreach($docsCheck as $idx => $dc)
                @php
                    $filePath = $transaksi->{$dc['field']};
                    $hasFile = !empty($filePath) && \App\Services\SiReKaStorage::exists($filePath);
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $dc['title'] }}</td>
                    <td class="text-center" style="font-weight: bold; color: {{ $hasFile ? '#15803d' : '#b91c1c' }};">
                        {{ $hasFile ? 'Tersedia Lengkap' : 'Tidak Tersedia' }}
                    </td>
                    <td class="text-center" style="font-weight: bold; color: #15803d;">
                        ✓ Telah Diverifikasi Sah
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pernyataan Pengesahan -->
    <div class="statement-box">
        <strong>PERNYATAAN PENGESAHAN KONSOLIDATOR:</strong><br>
        Berdasarkan hasil pengujian teknis data saldo, pencocokan mutasi kas, serta validasi kelengkapan 4 dokumen bukti dukung fisik di atas, Konsolidator Badan Keuangan dan Aset Daerah (BKAD) Pemerintah Kabupaten Tapin menyatakan bahwa pelaksanaan rekonsiliasi kas pada instansi bersangkutan telah memenuhi standar akuntansi keuangan daerah dan dinyatakan <strong>SAH, TUNTAS, DAN VALID</strong>.
    </div>

    <!-- Tanda Tangan & QR Code -->
    <table class="auth-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Kolom QR Code Otentikasi -->
            <td style="width: 35%; text-align: center; vertical-align: middle;">
                @if($qrBase64)
                    <img src="data:image/png;base64,{{ $qrBase64 }}" width="90" height="90" style="border: 1px solid #94a3b8; padding: 3px; border-radius: 4px;">
                @endif
                <div style="font-size: 8.5px; font-weight: bold; color: #475569; margin-top: 4px;">
                    Scan QR Code untuk Verifikasi Keaslian Dokumen
                </div>
            </td>

            <!-- Kolom Stempel & Identitas Konsolidator -->
            <td style="width: 65%; text-align: center;">
                <div style="font-size: 11px; margin-bottom: 6px;">
                    Rantau, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                    <strong>Konsolidator Pemeriksa Kas Daerah,</strong>
                </div>

                <!-- Stempel Digital Pengesahan -->
                <div class="stamp-box">
                    <div class="stamp-title">TELAH DIPERIKSA &amp; DISAHKAN VALID</div>
                    <div style="font-size: 9.5px; font-weight: bold; color: #047857; margin-top: 2px;">
                        KONSOLIDATOR BKAD KABUPATEN TAPIN
                    </div>
                    <div class="stamp-meta">
                        Diperiksa: {{ $witaTime }}<br>
                        Oleh: {{ $transaksi->checker->name ?? 'Konsolidator BKAD' }}
                    </div>
                </div>

                <div style="margin-top: 8px;">
                    <div style="font-weight: bold; font-size: 11px; text-decoration: underline;" class="uppercase">
                        {{ $transaksi->checker->name ?? 'KONSOLIDATOR BKAD' }}
                    </div>
                    <div style="font-size: 9.5px; color: #475569;">
                        NIP. {{ $transaksi->checker->nip ?? '-' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer Keamanan & Jejak Digital -->
    <table width="100%" style="margin-top: 25px; border-top: 1px solid #cbd5e1; padding-top: 4px;">
        <tr>
            <td style="font-size: 8px; color: #64748b; border: none;">
                Dokumen ini merupakan tanda bukti pengesahan digital resmi yang diterbitkan oleh Aplikasi SiReKa BKAD Kabupaten Tapin.
            </td>
            <td style="font-size: 8px; color: #64748b; text-align: right; border: none; font-family: monospace;">
                Audit Trace ID: {{ md5($transaksi->id . $transaksi->checked_at . 'sireka-tapin-seal') }}
            </td>
        </tr>
    </table>

</body>
</html>
