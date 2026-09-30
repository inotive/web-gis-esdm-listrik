<?php

namespace App\Exports;

use App\Models\Perizinan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Export seluruh data tabel perizinans (tanpa filter / paginasi).
 * Semua nilai ditulis sebagai teks agar nomor/kontak dengan angka 0 di depan tidak hilang.
 */
class PerizinanExport extends StringValueBinder implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithTitle, WithCustomValueBinder
{
    public function title(): string
    {
        return 'Data Perizinan';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Perusahaan',
            'Nama Pimpinan',
            'Alamat Perusahaan',
            'Kabupaten/Kota',
            'Kontak',
            'Jenis Izin',
            'No. Pengajuan',
            'No. Surat Keluar',
            'No. Surat Izin Terbit',
            'Tanggal',
            'Tanggal Terbit',
            'Tanggal Akhir',
            'Status Izin',
            'Status (Berdasarkan Tanggal Akhir)',
            'Lokasi',
            'Titik Koordinat',
            'Jumlah',
            'Kapasitas',
            'Jumlah Kapasitas',
            'Total Kapasitas (kVA)',
            'Jenis Penggunaan',
            'Sifat Penggunaan',
            'Catatan',
            'Jumlah Dokumen',
            'Dibuat Oleh',
            'Tanggal Dibuat',
            'Terakhir Diperbarui',
        ];
    }

    public function collection()
    {
        return Perizinan::with(['perusahaan', 'creator'])
            ->withCount('documents')
            ->orderBy('created_at', 'desc')
            ->get()
            ->values()
            ->map(fn (Perizinan $p, $i) => [
                $i + 1,
                $p->perusahaan?->nama ?? $p->nama_perusahaan,
                $p->perusahaan?->nama_pimpinan,
                $p->perusahaan?->alamat,
                $p->perusahaan?->kabupaten_kota,
                $p->kontak ?? $p->perusahaan?->kontak,
                $p->jenis,
                $p->no_pengajuan,
                $p->no_surat_keluar,
                $p->no_surat_izin_terbit,
                $p->tanggal?->format('d-m-Y'),
                $p->tanggal_terbit?->format('d-m-Y'),
                $p->tanggal_akhir?->format('d-m-Y'),
                $p->status_izin,
                $p->calculated_status,
                $p->lokasi,
                $p->titik_koordinat,
                $p->jumlah,
                $p->kapasitas,
                $p->jumlah_kapasitas,
                $p->total_kapasitas_kva,
                $p->jenis_penggunaan,
                $p->sifat_penggunaan,
                $p->catatan,
                $p->documents_count,
                $p->creator?->name,
                $p->created_at?->format('d-m-Y H:i'),
                $p->updated_at?->format('d-m-Y H:i'),
            ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = Coordinate::stringFromColumnIndex(count($this->headings()));
                $lastRow = $sheet->getHighestRow();

                $header = $sheet->getStyle('A1:' . $lastCol . '1');
                $header->getFont()->setBold(true);
                $header->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E5E7EB');
                $header->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A1:' . $lastCol . $lastRow)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $sheet->freezePane('A2');
            },
        ];
    }
}
