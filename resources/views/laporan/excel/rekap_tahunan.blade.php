<table>
    <tr>
        <td colspan="5" style="font-weight: bold; font-size: 14px;">LAPORAN REKAPITULASI TAHUNAN KAS DAERAH</td>
    </tr>
    <tr>
        <td colspan="5">Instansi: {{ $skpd->nama }}</td>
    </tr>
    <tr>
        <td colspan="5">Tahun: {{ $tahunAktif }}</td>
    </tr>
    <tr>
        <th>Bulan</th>
        <th>Saldo BKU</th>
        <th>Saldo Bank</th>
        <th>Nilai Selisih</th>
        <th>Status</th>
    </tr>
    @foreach($rekapData as $data)
    <tr>
        <td>{{ $namaBulan[$data['bulan'] - 1] }}</td>
        <td>{{ $data['bku'] }}</td>
        <td>{{ $data['bank'] }}</td>
        <td>{{ $data['selisih'] }}</td>
        <td>{{ isset($data['status']) ? ucfirst($data['status']) : '-' }}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight: bold;">SALDO AKHIR TAHUN</td>
        <td style="font-weight: bold;">{{ $totalBku }}</td>
        <td style="font-weight: bold;">{{ $totalBank }}</td>
        <td style="font-weight: bold;">{{ abs($totalBku - $totalBank) }}</td>
        <td></td>
    </tr>
</table>
