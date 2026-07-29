<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KonsolidasiExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $rekapData;
    protected $selectedBulan;
    protected $tahunAktif;
    protected $totalBku;
    protected $totalBank;
    protected $totalSelisih;

    public function __construct($rekapData, $selectedBulan, $tahunAktif, $totalBku, $totalBank, $totalSelisih)
    {
        $this->rekapData = $rekapData;
        $this->selectedBulan = $selectedBulan;
        $this->tahunAktif = $tahunAktif;
        $this->totalBku = $totalBku;
        $this->totalBank = $totalBank;
        $this->totalSelisih = $totalSelisih;
    }

    public function view(): View
    {
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('laporan.excel.konsolidasi', [
            'rekapData' => $this->rekapData,
            'selectedBulan' => $namaBulan[$this->selectedBulan - 1] ?? '',
            'tahunAktif' => $this->tahunAktif,
            'totalBku' => $this->totalBku,
            'totalBank' => $this->totalBank,
            'totalSelisih' => $this->totalSelisih
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            3    => ['font' => ['bold' => true]],
            // Styles applied via view can sometimes be enough, 
            // but this helps ensuring the title is bold.
        ];
    }
}
