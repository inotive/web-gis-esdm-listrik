<?php

namespace Database\Seeders;

use App\Models\PerizinanListrik;
use App\Models\Perusahaan;
use App\Models\PembangkitListrik;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PerizinanListrikSeeder extends Seeder
{
    protected array $sheetMapping = [
        'SAMARINDA' => 'Kota Samarinda',
        'BALIKPAPAN' => 'Kota Balikpapan',
        'KUKAR' => 'Kab. Kutai Kartanegara',
        'KUTIM' => 'Kab. Kutai Timur',
        'KUBAR' => 'Kab. Kutai Barat',
        'PASER' => 'Kab. Paser',
        'PPU' => 'Kab. Penajam Paser Utara',
        'BONTANG' => 'Kota Bontang',
        'BERAU' => 'Kab. Berau',
        'MAHULU' => 'Kab. Mahakam Ulu',
    ];

    public function run(): void
    {
        $filePath = public_path('assets/Daftar Rekomtek & Pertek Perizinan Listrik 2022.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        // Clear existing data (disable foreign key check)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        PembangkitListrik::truncate();
        PerizinanListrik::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetNames = $spreadsheet->getSheetNames();

            $totalPerizinan = 0;
            $totalPerusahaan = 0;
            $totalPembangkit = 0;

            foreach ($sheetNames as $sheetName) {
                if (strtoupper($sheetName) === 'RINGKASAN') {
                    continue;
                }

                try {
                    $kabupatenKota = $this->sheetMapping[strtoupper($sheetName)] ?? $sheetName;
                    $sheet = $spreadsheet->getSheetByName($sheetName);
                    $data = $sheet->toArray(null, false, false, false);

                    $headerRowIndex = $this->findHeaderRow($data);
                    if ($headerRowIndex === null) {
                        $this->command->warn("Could not find header row in sheet: {$sheetName}");
                        continue;
                    }

                    $result = $this->importSheetData($data, $headerRowIndex, $kabupatenKota);

                    $totalPerizinan += $result['perizinan'];
                    $totalPerusahaan += $result['perusahaan'];
                    $totalPembangkit += $result['pembangkit'];

                    $this->command->info("Sheet {$sheetName}: {$result['perizinan']} perizinan, {$result['perusahaan']} perusahaan, {$result['pembangkit']} pembangkit");
                } catch (\Exception $e) {
                    $this->command->warn("Error processing sheet {$sheetName}: " . $e->getMessage());
                    continue;
                }
            }

            $this->command->info("=== TOTAL ===");
            $this->command->info("Perizinan: {$totalPerizinan}");
            $this->command->info("Perusahaan: {$totalPerusahaan}");
            $this->command->info("Pembangkit: {$totalPembangkit}");
        } catch (\Exception $e) {
            $this->command->error("Error reading Excel file: " . $e->getMessage());
        }
    }

    protected function findHeaderRow(array $data): ?int
    {
        foreach ($data as $index => $row) {
            $rowText = strtolower(implode(' ', array_filter($row)));
            if (str_contains($rowText, 'no. pengajuan') || str_contains($rowText, 'no pengajuan')) {
                return $index;
            }
        }
        return null;
    }

    protected function importSheetData(array $data, int $headerRowIndex, string $kabupatenKota): array
    {
        $result = ['perizinan' => 0, 'perusahaan' => 0, 'pembangkit' => 0];
        $headers = $data[$headerRowIndex];
        $columnMap = $this->mapColumns($headers);

        $currentCompany = null;
        $currentPerusahaan = null;
        $currentPerizinan = null;
        $createdPerusahaanNames = []; // Track created companies

        for ($i = $headerRowIndex + 1; $i < count($data); $i++) {
            $row = $data[$i];

            if ($this->isEmptyOrSummaryRow($row)) {
                continue;
            }

            try {
                $namaPemohon = $this->cleanString($this->getValue($row, $columnMap, 'nama_pemohon'));

                // New company/perizinan record
                if (!empty($namaPemohon)) {
                    $currentCompany = [
                        'nama_pemohon' => $namaPemohon,
                        'kontak' => $this->cleanString($this->getValue($row, $columnMap, 'kontak')),
                        'jenis_perizinan' => $this->cleanString($this->getValue($row, $columnMap, 'jenis_perizinan')), // SKTP, IUPTLS
                        'no_pengajuan' => $this->cleanString($this->getValue($row, $columnMap, 'no_pengajuan')),
                        'no_surat_keluar' => $this->cleanString($this->getValue($row, $columnMap, 'no_surat_keluar')),
                        'tanggal_perizinan' => $this->parseDate($this->getValue($row, $columnMap, 'tanggal_perizinan')),
                        'no_surat_izin' => $this->cleanString($this->getValue($row, $columnMap, 'no_surat_izin')),
                        'tanggal_terbit' => $this->parseDate($this->getValue($row, $columnMap, 'tanggal_terbit')),
                        'tanggal_akhir' => $this->parseDate($this->getValue($row, $columnMap, 'tanggal_akhir')),
                        'lokasi' => $this->cleanString($this->getValue($row, $columnMap, 'lokasi')),
                    ];

                    // Create/Update Perusahaan
                    $currentPerusahaan = Perusahaan::updateOrCreate(
                        ['nama' => $namaPemohon],
                        [
                            'kontak' => $currentCompany['kontak'],
                            'kabupaten_kota' => $kabupatenKota,
                        ]
                    );

                    // Count as new if not seen before
                    if (!in_array($namaPemohon, $createdPerusahaanNames)) {
                        $createdPerusahaanNames[] = $namaPemohon;
                        $result['perusahaan']++;
                    }
                }

                // Skip if no company tracked yet
                if (empty($currentCompany)) {
                    continue;
                }

                // Get pembangkit data
                $koordinat = $this->cleanString($this->getValue($row, $columnMap, 'koordinat'));
                $jumlahUnit = $this->parseNumber($this->getValue($row, $columnMap, 'jumlah_unit'));
                $kapasitas = $this->parseNumber($this->getValue($row, $columnMap, 'kapasitas'));
                $totalKapasitas = $this->parseNumber($this->getValue($row, $columnMap, 'total_kapasitas'));
                // Use row's jenis_perizinan if available, otherwise inherit from currentCompany
                $jenisPerizinan = $this->cleanString($this->getValue($row, $columnMap, 'jenis_perizinan'))
                    ?: ($currentCompany['jenis_perizinan'] ?? null);
                $jenisPembangkit = $this->cleanString($this->getValue($row, $columnMap, 'jenis_pembangkit'));
                $sifatPenggunaan = $this->cleanString($this->getValue($row, $columnMap, 'sifat_penggunaan'));
                $lokasi = $this->cleanString($this->getValue($row, $columnMap, 'lokasi'));

                $hasPembangkitData = !empty($koordinat) || !empty($kapasitas) || !empty($totalKapasitas) ||
                    !empty($jenisPembangkit) || !empty($jumlahUnit);

                // Update lokasi if present
                if (!empty($lokasi)) {
                    $currentCompany['lokasi'] = $lokasi;
                }

                // Only create PerizinanListrik for NEW company rows (when namaPemohon is not empty)
                // For sub-rows (empty namaPemohon), we only create PembangkitListrik
                if (!empty($namaPemohon)) {
                    // This is a new company row - create PerizinanListrik
                    $perizinanData = [
                        'tahun' => 2022,
                        'kabupaten_kota' => $kabupatenKota,
                        'nama_pemohon' => $currentCompany['nama_pemohon'],
                        'kontak' => $currentCompany['kontak'],
                        'jenis_usaha' => null,
                        'no_pengajuan' => $currentCompany['no_pengajuan'],
                        'no_surat_keluar' => $currentCompany['no_surat_keluar'],
                        'tanggal_perizinan' => $currentCompany['tanggal_perizinan'],
                        'no_surat_izin' => $currentCompany['no_surat_izin'],
                        'tanggal_terbit' => $currentCompany['tanggal_terbit'],
                        'tanggal_akhir' => $currentCompany['tanggal_akhir'],
                        'lokasi' => $currentCompany['lokasi'],
                        'koordinat' => $koordinat,
                        'jumlah_unit' => $jumlahUnit,
                        'kapasitas' => $kapasitas,
                        'total_kapasitas' => $totalKapasitas,
                        'jenis' => $jenisPerizinan, // SKTP, IUPTLS (inherited from company row if not present)
                        'sifat_penggunaan' => $sifatPenggunaan,
                        'catatan' => $this->cleanString($this->getValue($row, $columnMap, 'catatan')),
                    ];

                    $currentPerizinan = PerizinanListrik::create($perizinanData);
                    $result['perizinan']++;
                }

                // Create PembangkitListrik if we have pembangkit data and perusahaan
                // This applies to both new company rows AND sub-rows
                if ($hasPembangkitData && $currentPerusahaan && $currentPerizinan) {
                    PembangkitListrik::create([
                        'perusahaan_id' => $currentPerusahaan->id,
                        'perizinan_listrik_id' => $currentPerizinan->id,
                        'lokasi' => $currentCompany['lokasi'],
                        'koordinat' => $koordinat,
                        'jumlah_unit' => $jumlahUnit,
                        'kapasitas' => $kapasitas,
                        'total_kapasitas' => $totalKapasitas,
                        'jenis' => $jenisPembangkit, // PLTD, PLTS, PLTG, etc.
                        'sifat_penggunaan' => $sifatPenggunaan,
                        'catatan' => $this->cleanString($this->getValue($row, $columnMap, 'catatan')),
                    ]);
                    $result['pembangkit']++;
                }
            } catch (\Exception $e) {
                $this->command->error("Row {$i} error: " . $e->getMessage());
                continue;
            }
        }

        return $result;
    }

    protected function getValue(array $row, array $columnMap, string $field)
    {
        return isset($columnMap[$field]) ? ($row[$columnMap[$field]] ?? null) : null;
    }

    protected function mapColumns(array $headers): array
    {
        $map = [];
        $jenisCount = 0; // Track which "Jenis" column we're on

        foreach ($headers as $index => $header) {
            if ($header === null)
                continue;
            $h = strtolower(trim($header));

            if (str_contains($h, 'nama')) {
                $map['nama_pemohon'] = $index;
            } elseif (str_contains($h, 'kontak')) {
                $map['kontak'] = $index;
            }

            if (str_contains($h, 'no. pengajuan') || str_contains($h, 'no pengajuan')) {
                $map['no_pengajuan'] = $index;
            } elseif (str_contains($h, 'surat keluar') || str_contains($h, 'rekomtek/pertek') || str_contains($h, 'rekomtek')) {
                $map['no_surat_keluar'] = $index;
            } elseif (str_contains($h, 'surat izin terbit') || str_contains($h, 'no. surat izin')) {
                $map['no_surat_izin'] = $index;
            } elseif (str_contains($h, 'tanggal terbit')) {
                $map['tanggal_terbit'] = $index;
            } elseif (str_contains($h, 'tanggal akhir')) {
                $map['tanggal_akhir'] = $index;
            } elseif (str_contains($h, 'tanggal') && !isset($map['tanggal_perizinan'])) {
                $map['tanggal_perizinan'] = $index;
            } elseif (str_contains($h, 'lokasi')) {
                $map['lokasi'] = $index;
            } elseif (str_contains($h, 'koordinat') || str_contains($h, 'titik koordinat')) {
                $map['koordinat'] = $index;
            } elseif (str_contains($h, 'jumlah') && !str_contains($h, 'kapasitas')) {
                $map['jumlah_unit'] = $index;
            } elseif (str_contains($h, 'kapasitas') && !str_contains($h, 'total')) {
                $map['kapasitas'] = $index;
            } elseif (str_contains($h, 'total') && str_contains($h, 'kapasitas')) {
                $map['total_kapasitas'] = $index;
            } elseif ($h === 'jenis' && isset($map['nama_pemohon'])) {
                $jenisCount++;
                if ($jenisCount === 1) {
                    // First "Jenis" column = Jenis Perizinan (SKTP, IUPTLS)
                    $map['jenis_perizinan'] = $index;
                } else {
                    // Second "Jenis" column = Jenis Pembangkit (PLTD, PLTS, etc)
                    $map['jenis_pembangkit'] = $index;
                }
            } elseif (str_contains($h, 'sifat') || str_contains($h, 'penggunaan')) {
                $map['sifat_penggunaan'] = $index;
            } elseif (str_contains($h, 'catatan') || str_contains($h, 'keterangan')) {
                $map['catatan'] = $index;
            }
        }

        return $map;
    }

    protected function isEmptyOrSummaryRow(array $row): bool
    {
        $text = strtolower(implode(' ', array_filter($row)));

        // Check if it's an empty or summary row
        if (
            empty(trim($text)) ||
            str_contains($text, 'total kapasitas') ||
            str_contains($text, 'jumlah rekomtek')
        ) {
            return true;
        }

        // Check if this is a column number reference row (cells contain just sequential numbers like 1, 2, 3...)
        // This row appears after header and contains numbers like "1 2 3 4 5 6 7 8 9..." as column indices
        $nonEmptyCells = array_filter($row, fn($v) => $v !== null && $v !== '');
        if (count($nonEmptyCells) >= 5) {
            $allNumeric = true;
            $numericValues = [];
            foreach ($nonEmptyCells as $cell) {
                if (!is_numeric($cell)) {
                    $allNumeric = false;
                    break;
                }
                $numericValues[] = (int) $cell;
            }
            // If all cells are numeric and they seem to be sequential column indices (1,2,3,4,5...)
            if ($allNumeric && count($numericValues) >= 5) {
                sort($numericValues);
                $isSequential = true;
                for ($i = 0; $i < min(5, count($numericValues) - 1); $i++) {
                    if ($numericValues[$i + 1] - $numericValues[$i] !== 1) {
                        $isSequential = false;
                        break;
                    }
                }
                if ($isSequential && $numericValues[0] <= 5) {
                    return true; // This is a column number reference row
                }
            }
        }

        return false;
    }

    protected function cleanString($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        return trim((string) $value);
    }

    protected function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            $floatVal = (float) $value;
            if ($floatVal < 1 || $floatVal > 2958465) {
                return null;
            }
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $stringVal = (string) $value;
        if (stripos($stringVal, 'E+') !== false || stripos($stringVal, 'E-') !== false) {
            return null;
        }

        try {
            $date = \Carbon\Carbon::parse($stringVal);
            if ($date->year < 1900 || $date->year > 2100) {
                return null;
            }
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function parseNumber($value): ?float
    {
        if (empty($value)) {
            return null;
        }
        $cleaned = preg_replace('/[^0-9.,]/', '', (string) $value);
        $cleaned = str_replace(',', '.', $cleaned);
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }
}
