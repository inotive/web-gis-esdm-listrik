<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PermohonanTemplateExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            [
                'PT. Contoh Perusahaan',            // nama_perusahaan
                'Budi Santoso',                     // nama_pemohon
                'Permohonan IUPTLS',                // jenis_permohonan
                'Januari',                          // Bulan
                '2026',                             // Tahun
                'Kota Samarinda',                   // Kota/Kabupaten
                'Samarinda Ulu',                    // Kecamatan
                'Air Putih',                        // Kelurahan/Desa
                'Catatan jika ada',                 // Keterangan
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama Perusahaan',
            'Nama Pemohon',
            'Jenis Permohonan',
            'Bulan',
            'Tahun',
            'Kota/Kabupaten',
            'Kecamatan',
            'Kelurahan/Desa',
            'Keterangan',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = 'I'; // 9 Kolom (A - I)

                // 1. STYLING HEADER (Baris 1)
                $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);
                
                // Alignment Center Header
                $sheet->getStyle('A1:' . $lastCol . '1')
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                      ->setVertical(Alignment::VERTICAL_CENTER);

                // 2. BORDER (Garis Tabel)
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A1:' . $lastCol . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
