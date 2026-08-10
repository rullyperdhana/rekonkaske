<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArsipExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $skpdData;
    protected $tahunAktif;
    protected $namaBulan;

    public function __construct($skpdData, $tahunAktif)
    {
        $this->skpdData = $skpdData;
        $this->tahunAktif = $tahunAktif;
        $this->namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function view(): View
    {
        return view('laporan.excel.arsip', [
            'skpdData' => $this->skpdData,
            'tahunAktif' => $this->tahunAktif,
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
