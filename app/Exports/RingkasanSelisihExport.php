<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RingkasanSelisihExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $transaksis;
    protected $namaBulan;
    protected $selectedBulan;

    public function __construct($transaksis, $selectedBulan)
    {
        $this->transaksis = $transaksis;
        $this->selectedBulan = $selectedBulan;
        $this->namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function view(): View
    {
        return view('laporan.excel.ringkasan_selisih', [
            'transaksis' => $this->transaksis,
            'selectedBulan' => $this->selectedBulan,
            'namaBulan' => $this->namaBulan
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            3    => ['font' => ['bold' => true]],
        ];
    }
}
