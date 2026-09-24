<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Eksekutif Rekonsiliasi Kas - {{ $namaBulan[$bulanAktif - 1] }} {{ $tahunAktif }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 12px;
            padding-bottom: 6px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 70px;
            text-align: center;
        }
        .kop-logo img {
            width: 60px;
            height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-text h2 {
            font-size: 11pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: normal;
        }
        .kop-text h1 {
            font-size: 13pt;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 8.5pt;
            margin: 1px 0;
            font-style: italic;
        }
        .judul-dokumen {
            text-align: center;
            margin-bottom: 14px;
        }
        .judul-dokumen h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 0 0 3px 0;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-dokumen h3 {
            font-size: 9.5pt;
            font-weight: normal;
            margin: 0;
        }
        .box-kpi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .box-kpi td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
            background-color: #f8fafc;
        }
        .box-kpi .label {
            font-size: 8pt;
            text-transform: uppercase;
            font-weight: bold;
            color: #475569;
        }
        .box-kpi .value {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 4px 0;
            border-bottom: 1px solid #94a3b8;
            padding-bottom: 2px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.5pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #64748b;
            padding: 4px 6px;
        }
        .data-table th {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-klop { background-color: #d1fae5; color: #065f46; }
        .badge-draft { background-color: #e0f2fe; color: #075985; }
        .badge-selisih { background-color: #fee2e2; color: #991b1b; }
        .badge-belum { background-color: #fef3c7; color: #92400e; }
        
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            vertical-align: top;
            width: 50%;
        }

        .no-print {
            margin-bottom: 15px;
            text-align: right;
        }
        .btn-print {
            padding: 6px 14px;
            background-color: #00346f;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 9pt;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
    </div>

    @php
        $lines = explode('|', $pengaturanGlobal->isi_kop ?? 'PEMERINTAH KABUPATEN TAPIN|BADAN KEUANGAN DAN ASET DAERAH|Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru|RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173');
        $logoSrc = $pengaturanGlobal->logo ?? null;
        $base64Logo = null;
        if ($logoSrc) {
            $path = storage_path('app/public/' . $logoSrc);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        if (!$base64Logo) {
            $path = public_path('images/logo_tapin.png');
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
    @endphp

    <!-- Kop Surat Resmi -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($base64Logo)
                    <img src="{{ $base64Logo }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <h2>{{ $lines[0] ?? '' }}</h2>
                <h1>{{ $lines[1] ?? '' }}</h1>
                <p>{{ $lines[2] ?? '' }}</p>
                <p>{{ $lines[3] ?? '' }}</p>
            </td>
        </tr>
    </table>

    <div class="judul-dokumen">
        <h2>RINGKASAN EKSEKUTIF KEPATUHAN & LIKUIDITAS REKONSILIASI KAS</h2>
        <h3>Laporan Pemantauan Pimpinan Daerah &bull; Periode {{ $namaBulan[$bulanAktif - 1] }} {{ $tahunAktif }}</h3>
    </div>

    <!-- 4 Kotak KPI Utama -->
    <table class="box-kpi">
        <tr>
            <td style="width: 25%;">
                <div class="label">Total Kas di SKPD (Bank)</div>
                <div class="value font-mono">Rp {{ number_format($totalBank, 2, ',', '.') }}</div>
            </td>
            <td style="width: 25%;">
                <div class="label">Indeks Kepatuhan Pemda</div>
                <div class="value font-mono" style="color: #059669;">{{ $kepatuhanRate }}% ({{ $countKlop }}/{{ $totalSkpdCount }})</div>
            </td>
            <td style="width: 25%;">
                <div class="label">Akumulasi Selisih Kas</div>
                <div class="value font-mono" style="color: {{ $totalSelisih == 0 ? '#059669' : '#dc2626' }};">
                    Rp {{ number_format($totalSelisih, 2, ',', '.') }}
                </div>
            </td>
            <td style="width: 25%;">
                <div class="label">Status Kesiapan SKPD</div>
                <div class="value" style="font-size: 9.5pt;">
                    Klop: <b>{{ $countKlop }}</b> &bull; Draft: <b>{{ $countDraft }}</b> &bull; Belum: <b>{{ $countBelumLapor }}</b>
                </div>
            </td>
        </tr>
    </table>

    <!-- Rekomendasi Kebijakan untuk Pimpinan -->
    <div class="section-title">Catatan & Rekomendasi Kebijakan Pimpinan Daerah</div>
    <div style="background-color: #f1f5f9; border-left: 3px solid #00346f; padding: 6px 10px; margin-bottom: 12px; font-size: 8.5pt;">
        <ul style="margin: 0; padding-left: 16px;">
            @if($countSelisih > 0)
                <li style="color: #991b1b; font-weight: bold; margin-bottom: 3px;">
                    Perhatian Khusus Selisih Kas: Terdeteksi {{ $countSelisih }} SKPD dengan selisih nominal Rp {{ number_format($totalSelisih, 2, ',', '.') }}. Diinstruksikan kepada Tim Konsolidator BKAD dan Inspektorat untuk asistensi penelusuran dokumen transaksi.
                </li>
            @else
                <li style="color: #065f46; font-weight: bold; margin-bottom: 3px;">
                    Integritas Saldo Terjaga: Seluruh SKPD yang telah menyelesaikan verifikasi berstatus KLOP 100% (Rp 0,00) antara catatan BKU dengan Rekening Koran Bank Kalsel.
                </li>
            @endif
            @if($countBelumLapor > 0)
                <li style="margin-bottom: 3px;">
                    Percepatan Pelaporan: Terdapat {{ $countBelumLapor }} SKPD yang belum menyelesaikan rekonsiliasi kas periode {{ $namaBulan[$bulanAktif - 1] }} {{ $tahunAktif }}. Disarankan penerbitan surat peringatan/teguran pimpinan guna menjaga tertib administrasi LPJ.
                </li>
            @endif
            <li>
                Likuiditas Kas Daerah: Saldo kas mengendap di rekening perbankan seluruh bendahara pengeluaran tercatat sebesar Rp {{ number_format($totalBank, 2, ',', '.') }}.
            </li>
        </ul>
    </div>

    <!-- Tabel Rapor Seluruh SKPD -->
    <div class="section-title">Tabel Rapor Status Rekonsiliasi Kas Per SKPD</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Kode</th>
                <th style="width: 32%;">Nama Instansi (SKPD)</th>
                <th style="width: 18%;">Saldo BKU (Rp)</th>
                <th style="width: 18%;">Saldo Bank (Rp)</th>
                <th style="width: 10%;">Selisih (Rp)</th>
                <th style="width: 7%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($skpdRows as $idx => $r)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-mono text-center">{{ $r['kode'] }}</td>
                    <td>{{ $r['nama'] }}</td>
                    <td class="text-right font-mono">{{ number_format($r['bku'], 2, ',', '.') }}</td>
                    <td class="text-right font-mono">{{ number_format($r['bank'], 2, ',', '.') }}</td>
                    <td class="text-right font-mono" style="{{ $r['selisih'] > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">
                        {{ number_format($r['selisih'], 2, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @php
                            $badgeClass = match($r['status']) {
                                'KLOP' => 'badge-klop',
                                'PROSES' => 'badge-draft',
                                'SELISIH' => 'badge-selisih',
                                default => 'badge-belum',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $r['status'] }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="3" class="text-center">TOTAL KAS PEMDA</td>
                <td class="text-right font-mono">Rp {{ number_format($totalBku, 2, ',', '.') }}</td>
                <td class="text-right font-mono">Rp {{ number_format($totalBank, 2, ',', '.') }}</td>
                <td class="text-right font-mono" style="{{ $totalSelisih > 0 ? 'color: #dc2626;' : '' }}">
                    Rp {{ number_format($totalSelisih, 2, ',', '.') }}
                </td>
                <td class="text-center">{{ $countKlop }}/{{ $totalSkpdCount }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan Formal -->
    <table class="ttd-table">
        <tr>
            <td style="text-align: center;">
                <p>Mengetahui,</p>
                <p class="font-bold">SEKRETARIS DAERAH KABUPATEN TAPIN</p>
                <div style="height: 60px;"></div>
                <p class="font-bold" style="text-decoration: underline;">Dr. H. SUFYAN HAKIM, M.Si</p>
                <p>Pembina Utama Muda (IV/c)</p>
                <p>NIP. 19700512 199603 1 002</p>
            </td>
            <td style="text-align: center;">
                <p>Rantau, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">{{ $pengaturanGlobal->jabatan_kepala ?? 'KEPALA BADAN KEUANGAN DAN ASET DAERAH' }}</p>
                <div style="height: 60px;"></div>
                <p class="font-bold" style="text-decoration: underline;">{{ $pengaturanGlobal->nama_kepala ?? 'NAMA KEPALA BADAN' }}</p>
                <p>{{ $pengaturanGlobal->pangkat_kepala ?? 'Pembina Tingkat I (IV/b)' }}</p>
                <p>NIP. {{ $pengaturanGlobal->nip_kepala ?? '19750101 200001 1 001' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
