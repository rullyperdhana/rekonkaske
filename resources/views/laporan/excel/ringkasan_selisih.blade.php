<table>
    <tr>
        <td colspan="8" style="font-weight: bold; font-size: 14px;">LAPORAN RINGKASAN SELISIH TRANSAKSI</td>
    </tr>
    <tr>
        <td colspan="8">Bulan: {{ $selectedBulan ? $namaBulan[$selectedBulan - 1] : 'Semua Bulan' }}</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Instansi (SKPD)</th>
        <th>Bulan</th>
        <th>Saldo BKU</th>
        <th>Saldo Bank</th>
        <th>Nilai Selisih</th>
        <th>Keterangan</th>
        <th>Status</th>
    </tr>
    @foreach($transaksis as $trx)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $trx->skpd->nama ?? '-' }}</td>
        <td>{{ $namaBulan[$trx->periode_bulan - 1] }}</td>
        <td>{{ $trx->bku_saldo_akhir }}</td>
        <td>{{ $trx->bank_saldo_akhir }}</td>
        <td>{{ abs($trx->bku_saldo_akhir - $trx->bank_saldo_akhir) }}</td>
        <td>{{ $trx->keterangan_selisih ?: '-' }}</td>
        <td>{{ ucfirst($trx->status_verifikasi) }}</td>
    </tr>
    @endforeach
</table>
