<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapInfrastrukturExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $infrastrukturData;
    protected $totalInfra;

    public function __construct($infrastrukturData, $totalInfra)
    {
        $this->infrastrukturData = $infrastrukturData;
        $this->totalInfra = $totalInfra;
    }

    public function view(): View
    {
        return view('admin.rekap_data.exports.infrastruktur', [
            'infrastrukturData' => $this->infrastrukturData,
            'totalInfra' => $this->totalInfra
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
