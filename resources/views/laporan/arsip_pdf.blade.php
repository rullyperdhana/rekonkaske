<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kelengkapan Arsip Dokumen SKPD - Tahun {{ $tahunAktif }}</title>
    <style>
        @page { margin: 15mm 20mm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* KOP Surat */
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 15px; padding-bottom: 5px; }
        .kop-logo { width: 80px; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 70px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 80px; } 
        .kop-text h2 { margin: 0; font-size: 16px; font-weight: bold; letter-spacing: 1px; }
        .kop-text h1 { margin: 0; font-size: 18px; font-weight: 900; letter-spacing: 1px; }
        .kop-text p { margin: 2px 0 0 0; font-size: 11px; }
        
        /* Judul */
        .judul-dokumen h2 { margin: 0; font-size: 14px; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        .judul-dokumen h3 { margin: 5px 0 15px 0; font-size: 12px; font-weight: normal; }

        /* Tabel Rekap */
        table.keuangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
            border: 1px solid #000;
        }
        table.keuangan th, table.keuangan td {
            padding: 5px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        table.keuangan th {
            background-color: #f3f4f6;
        }
        table.keuangan th:last-child, table.keuangan td:last-child {
            border-right: none;
        }
        .status-danger { color: #ba1a1a; }
        .status-success { color: #1b6d24; }

        /* Tanda Tangan */
        .ttd-table { width: 100%; margin-top: 30px; font-size: 12px; page-break-inside: avoid; border: none; }
        .ttd-table td { border: none; padding: 1px; }
        .ttd-cell { width: 50%; text-align: center; vertical-align: top; }
        .ttd-space { height: 60px; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-bottom: 2px; }
        .ttd-nip { margin-top: 0; }
    </style>
</head>
<body>

    @php
        $lines = explode('|', $pengaturan->isi_kop ?? 'PEMERINTAH KABUPATEN TAPIN|BADAN KEUANGAN DAN ASET DAERAH|Jalan Datu Nuraya Kawasan Perkantoran Rantau Baru|RT. 01 Kelurahan Rangda Malingkung Kecamatan Tapin Utara Telp. 0517 2035173');
        $logoSrc = $pengaturan->logo ?? null;
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

    <div class="text-center judul-dokumen">
        <h2>Laporan Kelengkapan Arsip Dokumen SKPD</h2>
        <h3>Tahun Anggaran {{ $tahunAktif }}</h3>
    </div>

    <table class="keuangan">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th class="text-center" style="width: 12%">Kode SKPD</th>
                <th class="text-center" style="width: 38%">Nama SKPD</th>
                <th class="text-center" style="width: 9%">Rekening</th>
                <th class="text-center" style="width: 9%">Transaksi</th>
                <th class="text-center" style="width: 9%">Verified</th>
                <th class="text-center" style="width: 9%">Draft</th>
                <th class="text-center" style="width: 9%">Kurang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($skpdData as $data)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $data['skpd']->kode }}</td>
                <td>{{ $data['skpd']->nama }}</td>
                <td class="text-center">{{ $data['total_rekening'] }}</td>
                <td class="text-center">{{ $data['total_transaksi'] }}</td>
                <td class="text-center {{ $data['total_verified'] > 0 ? 'status-success font-bold' : '' }}">
                    {{ $data['total_verified'] }}
                </td>
                <td class="text-center {{ $data['total_draft'] > 0 ? 'status-danger font-bold' : '' }}">
                    {{ $data['total_draft'] }}
                </td>
                <td class="text-center {{ $data['total_dokumen_missing'] > 0 ? 'status-danger font-bold' : 'status-success' }}">
                    {{ $data['total_dokumen_missing'] == 0 ? 'Lengkap' : $data['total_dokumen_missing'] }}
                </td>
            </tr>
            @endforeach
            @if(empty($skpdData))
            <tr>
                <td colspan="8" class="text-center" style="padding: 10px; color: #666;">Belum ada data SKPD.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <table class="ttd-table">
        <tr>
            <td class="ttd-cell">
            </td>
            <td class="ttd-cell">
                Rantau, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                <strong>Admin SiReKa</strong><br>
                <div class="ttd-space"></div>
                <div class="ttd-name">{{ Auth::user()->name }}</div>
                <div class="ttd-nip">Role: {{ ucfirst(Auth::user()->role) }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
