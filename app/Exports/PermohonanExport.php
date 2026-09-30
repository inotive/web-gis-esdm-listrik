<?php

namespace App\Exports;

use App\Models\PermohonanQuestion;
use App\Models\PermohonanUser;
use Illuminate\Support\Collection;
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
 * Export seluruh data tabel permohonan_users (tanpa filter / paginasi).
 * Semua nilai ditulis sebagai teks agar nomor/kontak dengan angka 0 di depan tidak hilang.
 * Jawaban dinamis (kolom `jawaban`) dipecah menjadi kolom tersendiri per pertanyaan.
 */
class PermohonanExport extends StringValueBinder implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithTitle, WithCustomValueBinder
{
    protected Collection $records;

    /** @var array<int, string> Label kolom jawaban dinamis, berurutan */
    protected array $answerColumns = [];

    /** @var array<string, string> Map question_id => label kolom */
    protected array $questionLabels = [];

    public function __construct()
    {
        $this->records = PermohonanUser::with(['permohonan', 'user', 'perusahaan', 'approver'])
            ->withCount('documents')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->buildAnswerColumns();
    }

    public function title(): string
    {
        return 'Data Permohonan';
    }

    /**
     * Kumpulkan semua key jawaban dari seluruh record dan ubah menjadi label pertanyaan.
     * Pertanyaan dengan teks yang sama (lintas kategori) digabung dalam satu kolom.
     */
    protected function buildAnswerColumns(): void
    {
        $keys = $this->records
            ->flatMap(fn ($r) => is_array($r->jawaban) ? array_keys($r->jawaban) : [])
            ->map(fn ($k) => (string) $k)
            ->unique();

        $numericKeys = $keys->filter(fn ($k) => ctype_digit($k));
        $questions = PermohonanQuestion::whereIn('id', $numericKeys)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get(['id', 'pertanyaan']);

        foreach ($questions as $question) {
            $this->questionLabels[(string) $question->id] = trim($question->pertanyaan);
        }

        // Key yang bukan ID pertanyaan (hasil import tanpa kategori) atau pertanyaan yang sudah dihapus
        foreach ($keys as $key) {
            if (!isset($this->questionLabels[$key])) {
                $this->questionLabels[$key] = ctype_digit($key) ? "Pertanyaan #{$key}" : $key;
            }
        }

        $this->answerColumns = array_values(array_unique(array_merge(
            $questions->pluck('pertanyaan')->map(fn ($p) => trim($p))->all(),
            array_values($this->questionLabels)
        )));
    }

    public function headings(): array
    {
        return array_merge([
            'No',
            'Nama Pemohon',
            'Email Pemohon',
            'Nama Perusahaan',
            'Kabupaten/Kota Perusahaan',
            'Kategori / Jenis Permohonan',
            'Status',
            'Keterangan',
            'Disetujui Oleh',
            'Tanggal Disetujui',
            'Catatan Persetujuan',
            'Jumlah Dokumen',
            'Tanggal Dibuat',
            'Terakhir Diperbarui',
        ], $this->answerColumns);
    }

    public function collection()
    {
        return $this->records->values()->map(function (PermohonanUser $r, $i) {
            $answers = array_fill_keys($this->answerColumns, '');

            foreach ((array) $r->jawaban as $key => $value) {
                $label = $this->questionLabels[(string) $key] ?? (string) $key;
                $answers[$label] = $this->formatValue($value);
            }

            return array_merge([
                $i + 1,
                $r->applicant_name,
                $r->user?->email,
                $r->company_name,
                $r->perusahaan?->kabupaten_kota,
                $r->type_name,
                $r->status,
                $r->keterangan,
                $r->approver?->name,
                $r->approved_at?->format('d-m-Y H:i'),
                $r->approval_notes,
                $r->documents_count,
                $r->created_at?->format('d-m-Y H:i'),
                $r->updated_at?->format('d-m-Y H:i'),
            ], array_values($answers));
        });
    }

    protected function formatValue($value): string
    {
        if (is_array($value)) {
            return implode(', ', array_map(fn ($v) => is_scalar($v) ? (string) $v : json_encode($v), $value));
        }

        return (string) $value;
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
