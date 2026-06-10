<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapElektrifikasiExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $rekapData;
    protected $total;
    protected $tahun;

    public function __construct($rekapData, $total, $tahun)
    {
        $this->rekapData = $rekapData;
        $this->total = $total;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('admin.rekap_data.exports.elektrifikasi', [
            'rekapData' => $this->rekapData,
            'total' => $this->total,
            'tahun' => $this->tahun
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
