<table>
    <thead>
        <tr>
            <th colspan="15" style="text-align: center; font-size: 14px; font-weight: bold;">
                Laporan Kelengkapan Arsip Dokumen SKPD
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center; font-weight: bold;">
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
            @foreach($namaBulan as $bulan)
                <th style="font-weight: bold; text-align: center; border: 1px solid #000;">{{ substr($bulan, 0, 3) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($skpdData as $index => $data)
            <tr>
                <td style="text-align: center; border: 1px solid #000;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $data['skpd']->kode }}</td>
                <td style="border: 1px solid #000;">{{ $data['skpd']->nama }}</td>
                @for($i = 1; $i <= 12; $i++)
                    @php
                        $statusParts = explode('|', $data['bulan_status'][$i]);
                        $rekonStatus = $statusParts[0] ?? '-';
                        $docStatus = $statusParts[1] ?? '-';
                        
                        $color = '#000';
                        if ($docStatus == 'Lengkap') $color = '#1b6d24';
                        if ($docStatus == 'Kurang') $color = '#ba1a1a';
                    @endphp
                    <td style="text-align: center; border: 1px solid #000; color: {{ $color }}; font-weight: {{ $rekonStatus == '-' ? 'normal' : 'bold' }};">
                        @if($rekonStatus != '-')
                            {{ $rekonStatus }}
                            ({{ $docStatus }})
                        @else
                            -
                        @endif
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
