<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PerizinanTemplateExport implements FromCollection, WithHeadings, WithEvents, WithStrictNullComparison, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            [
                'nama_perusahaan' => 'PT. Contoh Perusahaan',
                'nama_pemohon' => 'Budi Santoso',
                'jenis_permohonan' => 'Baru',
                'kontak' => '081234567890',
                'no_pengajuan' => 'REG/2026/001',
                'no_surat_keluar' => 'SK/2026/001',
                'tanggal' => '2026-01-26',
                'tanggal_akhir' => '2031-01-26',
                'lokasi' => 'Jl. Merdeka No. 1, Jakarta',
                'status_kelistrikan' => 'Berlistrik PLN',
                'titik_koordinat' => '-6.2088,106.8456',
                'jumlah_kapasitas' => 10,
                'total_kapasitas_kva' => 1000,
                'jenis_penggunaan' => 'Industri',
                'sifat_penggunaan' => 'Tetap',
                'catatan' => 'Catatan tambahan jika ada',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_perizinan',
            'nama_perusahaan',
            'jenis_perizinan',
            'kontak',
            'no_pengajuan',
            'no_surat_keluar',
            'tanggal',
            'tanggal_akhir',
            'lokasi',
            'status_kelistrikan',
            'titik_koordinat',
            'jumlah_kapasitas',
            'total_kapasitas_kva',
            'jenis_penggunaan',
            'sifat_penggunaan',
            'catatan',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Dropdown validation for status_kelistrikan (Column I)
                $validation = $event->sheet->getCell('I2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input Error');
                $validation->setError('Value is not declared in the list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');
                $validation->setFormula1('"Berlistrik PLN,Berlistrik NON-PLN,Tidak Berlistrik"');

                // Apply to a range of rows (e.g., 2 to 1000)
                for ($i = 3; $i <= 1000; $i++) {
                   $event->sheet->getCell("I$i")->setDataValidation(clone $validation);
                }
            },
        ];
    }
}
