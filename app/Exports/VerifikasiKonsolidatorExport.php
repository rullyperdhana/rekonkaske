<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VerifikasiKonsolidatorExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $transaksis;
    protected $selectedBulan;
    protected $selectedStatus;
    protected $tahunAktif;
    protected $namaBulan;

    public function __construct($transaksis, $selectedBulan, $selectedStatus, $tahunAktif)
    {
        $this->transaksis = $transaksis;
        $this->selectedBulan = $selectedBulan;
        $this->selectedStatus = $selectedStatus;
        $this->tahunAktif = $tahunAktif;
        $this->namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function view(): View
    {
        return view('laporan.excel.verifikasi_konsolidator', [
            'transaksis' => $this->transaksis,
            'selectedBulan' => $this->selectedBulan,
            'selectedStatus' => $this->selectedStatus,
            'tahunAktif' => $this->tahunAktif,
            'namaBulan' => $this->namaBulan
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}
