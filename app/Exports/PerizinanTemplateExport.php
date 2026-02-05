<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;

class PerizinanTemplateExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    /**
    * Mengembalikan Data Contoh (Dummy) di Baris ke-6
    */
    public function collection()
    {
        return new Collection([
            [
                '1',                                // 1. No
                '101',                              // 2. ID
                'PT. Contoh Perusahaan',            // 3. Nama (Pemohon)
                '081234567890',                     // 4. Kontak
                'IUPTLS',                           // 5. Jenis
                'REG/2026/001',                     // 6. No. Pengajuan
                'SK/2026/001',                      // 7. No. Surat Keluar
                '2026-01-26',                       // 8. Tanggal
                'IZIN/2026/001',                    // 9. No. Surat Izin Terbit
                '2026-02-01',                       // 10. Tanggal Terbit
                '2031-02-01',                       // 11. Tanggal Akhir
                'Jl. Merdeka No. 1, Samarinda',     // 12. Lokasi
                '-0.502, 117.153',                  // 13. Titik Koordinat
                '1',                                // 14. Jumlah
                '100',                              // 15. Kapasitas
                '100',                              // 16. Total Kapasitas (kVA)
                'PLTD',                             // 17. Jenis (Pembangkit)
                'Utama',                            // 18. Sifat Penggunaan
                'Catatan jika ada',                 // 19. Catatan
            ]
        ]);
    }

    /**
     * Membuat 5 Baris Header sesuai format Asli
     */
    public function headings(): array
    {
        return [
            // Baris 1: Judul Besar
            ['DAFTAR REKOMTEK/PERTEK PERIZINAN/NON PERIZINAN USAHA PENYEDIAAN TENAGA LISTRIK UNTUK KEPENTINGAN SENDIRI'],
            
            // Baris 2: Kosong
            [''],

            // Baris 3: Grouping Header
            [
                'No.',
                'ID',
                'Data Pemohon/Pelaku Usaha', // C
                '',                          // D (Merged)
                'Data Perizinan/Non Perizinan', // E
                '', '', '', '', '', '',      // F-K (Merged)
                'Data Pembangkit Listrik',   // L
                '', '', '', '', '', '',      // M-R (Merged)
                'Catatan'                    // S
            ],

            // Baris 4: Header Spesifik (Nama Kolom)
            [
                '', // A (Merged No)
                '', // B (Merged ID)
                'Nama',
                'Kontak',
                'Jenis',
                'No. Pengajuan',
                "No. Surat Keluar \n (Rekomtek/Pertek)",          // Mendukung wrap text
                'Tanggal',
                'No. Surat Izin Terbit',
                'Tanggal Terbit',
                'Tanggal Akhir',
                'Lokasi',
                'Titik Koordinat',
                'Jumlah',
                'Kapasitas',
                'Total Kapasitas (kVA)',
                'Jenis',
                'Sifat Penggunaan',
                ''  // S (Merged Catatan)
            ],

            // Baris 5: Nomor Kolom (1 - 19)
            [
                '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 
                '11', '12', '13', '14', '15', '16 (14*15)', '17', '18', '19'
            ]
        ];
    }

    /**
     * Register Events untuk Merge Cells dan Formatting
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. MERGE CELLS (Menyatukan Sel Header)
                
                // Merge Judul Utama (A1 sampai S1)
                $sheet->mergeCells('A1:S1');

                // Merge Vertikal (Baris 3 & 4) untuk No, ID, dan Catatan
                $sheet->mergeCells('A3:A4'); // No
                $sheet->mergeCells('B3:B4'); // ID
                $sheet->mergeCells('S3:S4'); // Catatan

                // Merge Horizontal (Baris 3) untuk Grouping
                $sheet->mergeCells('C3:D3'); // Data Pemohon
                $sheet->mergeCells('E3:K3'); // Data Perizinan
                $sheet->mergeCells('L3:R3'); // Data Pembangkit

                // 2. STYLING

                // Set Font Header Bold
                $sheet->getStyle('A1:S5')->getFont()->setBold(true);

                // Set Alignment Center (Tengah Vertikal & Horizontal)
                $sheet->getStyle('A1:S' . $sheet->getHighestRow())
                      ->getAlignment()
                      ->setVertical(Alignment::VERTICAL_CENTER);
                
                $sheet->getStyle('A1:S5')
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Wrap Text (Agar "No. Surat Keluar" turun ke bawah jika panjang)
                $sheet->getStyle('A4:S4')->getAlignment()->setWrapText(true);

                // 3. BORDER (Garis Tabel)
                // Memberikan garis kotak hitam tipis dari baris 3 sampai data terakhir
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A3:S' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // 4. AUTO SIZE (Lebar Kolom Otomatis)
                foreach(range('A','S') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Styling tambahan jika diperlukan di masa depan
        ];
    }
}