<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center; font-size: 14px; font-weight: bold;">
                Laporan Kelengkapan Arsip Dokumen SKPD
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">
                Tahun Anggaran {{ $tahunAktif }}
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">No</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Kode SKPD</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Nama SKPD</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Jml Rekening</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Jml Transaksi</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Verified</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Draft</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Dokumen Kurang (Belum Upload)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($skpdData as $index => $data)
            <tr>
                <td style="text-align: center; border: 1px solid #000;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $data['skpd']->kode }}</td>
                <td style="border: 1px solid #000;">{{ $data['skpd']->nama }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $data['total_rekening'] }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $data['total_transaksi'] }}</td>
                <td style="text-align: center; border: 1px solid #000; color: {{ $data['total_verified'] > 0 ? '#1b6d24' : '#000' }};">
                    {{ $data['total_verified'] }}
                </td>
                <td style="text-align: center; border: 1px solid #000; color: {{ $data['total_draft'] > 0 ? '#ba1a1a' : '#000' }};">
                    {{ $data['total_draft'] }}
                </td>
                <td style="text-align: center; border: 1px solid #000; color: {{ $data['total_dokumen_missing'] > 0 ? '#ba1a1a' : '#1b6d24' }}; font-weight: {{ $data['total_dokumen_missing'] > 0 ? 'bold' : 'normal' }};">
                    {{ $data['total_dokumen_missing'] == 0 ? 'Lengkap' : $data['total_dokumen_missing'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
