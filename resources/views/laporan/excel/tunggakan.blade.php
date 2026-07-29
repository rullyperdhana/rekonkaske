<table>
    <tr>
        <td colspan="4" style="font-weight: bold; font-size: 14px;">LAPORAN TUNGGAKAN REKONSILIASI KAS DAERAH</td>
    </tr>
    <tr>
        <td colspan="4">Periode: {{ $namaBulan[$selectedBulan - 1] }} {{ $tahunAktif }}</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Instansi (SKPD)</th>
        <th>Bulan Tunggakan</th>
        <th>Keterangan</th>
    </tr>
    @foreach($tunggakanList as $skpd)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $skpd->nama }}</td>
        <td>{{ $namaBulan[$selectedBulan - 1] }}</td>
        <td>Belum Menginput Transaksi</td>
    </tr>
    @endforeach
</table>
