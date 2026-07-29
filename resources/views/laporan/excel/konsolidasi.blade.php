<table>
    <tr>
        <td colspan="7" style="font-weight: bold; font-size: 14px;">LAPORAN KONSOLIDASI KAS DAERAH</td>
    </tr>
    <tr>
        <td colspan="7">Periode: {{ $selectedBulan }} {{ $tahunAktif }}</td>
    </tr>
    <tr>
        <th>No</th>
        <th>SKPD</th>
        <th>Bulan</th>
        <th>Saldo BKU</th>
        <th>Saldo Bank</th>
        <th>Nilai Selisih</th>
        <th>Status</th>
    </tr>
    @foreach($rekapData as $data)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $data['skpd_nama'] }}</td>
        <td>{{ $data['bulan'] }}</td>
        <td>{{ $data['bku'] }}</td>
        <td>{{ $data['bank'] }}</td>
        <td>{{ $data['selisih'] }}</td>
        <td>{{ ucfirst($data['status']) }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3" style="font-weight: bold;">TOTAL</td>
        <td style="font-weight: bold;">{{ $totalBku }}</td>
        <td style="font-weight: bold;">{{ $totalBank }}</td>
        <td style="font-weight: bold;">{{ $totalSelisih }}</td>
        <td></td>
    </tr>
</table>
