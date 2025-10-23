<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RekapitulasiAssetExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Ambil data dari database
     */
    public function collection()
    {
        $query = Asset::with([
            'province',
            'regency', 
            'district',
            'village',
            'unitKerja',
            'statusHukum'
        ]);

        // Apply filters jika ada
        if (!empty($this->filters['search'])) {
            $query->where('nama_asset', 'like', '%' . $this->filters['search'] . '%');
        }

        if (!empty($this->filters['unit_kerja_id'])) {
            $query->where('unit_kerja_id', $this->filters['unit_kerja_id']);
        }

        if (!empty($this->filters['reg_regencies_id'])) {
            $query->where('reg_regencies_id', $this->filters['reg_regencies_id']);
        }

        if (!empty($this->filters['reg_districts_id'])) {
            $query->where('reg_districts_id', $this->filters['reg_districts_id']);
        }

        return $query->orderBy('nama_asset', 'asc')->get();
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Asset',
            'Nama Asset',
            'No Register',
            'Unit Kerja',
            'Status Hukum',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa/Kelurahan',
            'Luas (m²)',
            'Panjang (m)',
            'Lebar (m)',
            'Alamat',
            'Latitude',
            'Longitude',
            'Jenis Hak',
            'Nomor Hak',
            'Asal Perolehan',
            'Penggunaan SPMA',
        ];
    }

    /**
     * Map data untuk setiap row
     */
    public function map($asset): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $asset->kode_asset ?? '-',
            $asset->nama_asset ?? '-',
            $asset->no_register ?? '-',
            $asset->unitKerja->nama_unit ?? '-',
            $asset->statusHukum->nama_status ?? '-',
            $asset->province->name ?? '-',
            $asset->regency->name ?? '-',
            $asset->district->name ?? '-',
            $asset->village->name ?? '-',
            $asset->luas_m2 ?? 0,
            $asset->panjang_m ?? 0,
            $asset->lebar_m ?? 0,
            $asset->alamat ?? '-',
            $asset->latitude ?? '-',
            $asset->longitude ?? '-',
            $asset->jenis_hak ?? '-',
            $asset->nomor_hak ?? '-',
            $asset->asal ?? '-',
            $asset->penggunaan_spma ?? '-',
        ];
    }

    /**
     * Style untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Auto-height untuk header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 15,  // Kode Asset
            'C' => 30,  // Nama Asset
            'D' => 15,  // No Register
            'E' => 25,  // Unit Kerja
            'F' => 20,  // Status Hukum
            'G' => 15,  // Provinsi
            'H' => 20,  // Kabupaten
            'I' => 20,  // Kecamatan
            'J' => 20,  // Desa
            'K' => 12,  // Luas
            'L' => 12,  // Panjang
            'M' => 12,  // Lebar
            'N' => 30,  // Alamat
            'O' => 15,  // Latitude
            'P' => 15,  // Longitude
            'Q' => 15,  // Jenis Hak
            'R' => 15,  // Nomor Hak
            'S' => 20,  // Asal
            'T' => 15,  // Penggunaan SPMA
        ];
    }
}