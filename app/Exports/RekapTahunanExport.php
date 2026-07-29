<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapTahunanExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $skpd;
    protected $tahunAktif;
    protected $rekapData;
    protected $totalBku;
    protected $totalBank;

    public function __construct($skpd, $tahunAktif, $rekapData, $totalBku, $totalBank)
    {
        $this->skpd = $skpd;
        $this->tahunAktif = $tahunAktif;
        $this->rekapData = $rekapData;
        $this->totalBku = $totalBku;
        $this->totalBank = $totalBank;
    }

    public function view(): View
    {
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('laporan.excel.rekap_tahunan', [
            'skpd' => $this->skpd,
            'tahunAktif' => $this->tahunAktif,
            'rekapData' => $this->rekapData,
            'totalBku' => $this->totalBku,
            'totalBank' => $this->totalBank,
            'namaBulan' => $namaBulan
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            2    => ['font' => ['bold' => true]],
            4    => ['font' => ['bold' => true]],
        ];
    }
}
