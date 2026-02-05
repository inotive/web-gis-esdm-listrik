<?php

namespace App\Imports;

use App\Models\Perizinan;
use App\Models\Perusahaan;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PerizinanImport implements OnEachRow, WithHeadingRow
{
    /**
    * @return int
    */
    public function headingRow(): int
    {
        return 4; // The header row containing "Nama", "Kontak", etc.
    }

    /**
     * @param Row $row
     */
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $rowArray = $row->toArray();

        // Skip row 5 (which contains column numbers '1', '2', '3'...)
        if (isset($rowArray['nama']) && $rowArray['nama'] == '3') {
            return;
        }

        // Access raw values by index to handle duplicate "JENIS" headers
        // Indices are 0-based in array representation of the row cells
        // But getCellIterator might behave differently. 
        // Let's use getDelegate to be sure for precise column index access.
        // Excel Columns: A=1, B=2, C=3, D=4, E=5 (Jenis Izin), ..., Q=17 (Jenis Pembangkit)
        
        $worksheet = $row->getDelegate()->getWorksheet();
        // Get cell values by coordinate (Column Letter + Row Index)
        // Column 5 is 'E', Column 17 is 'Q'
        
        $jenisIzin = $worksheet->getCell('E' . $rowIndex)->getValue();       // Column 5: Jenis Perizinan (IUPTLS, etc)
        $jenisPembangkit = $worksheet->getCell('Q' . $rowIndex)->getValue();  // Column 17: Jenis Pembangkit (PLTA, PLTD, etc)

        // Clean up values
        $jenisIzin = trim($jenisIzin ?? '');
        $jenisPembangkit = trim($jenisPembangkit ?? '');

        // Fallback or use Named if needed, but Index is safer for duplicates.
        // We use $rowArray for other unique columns.

        // Cari perusahaan berdasarkan nama (case insensitive)
        $perusahaan = null;
        if (isset($rowArray['nama'])) {
            $perusahaan = Perusahaan::where('nama', 'like', '%' . $rowArray['nama'] . '%')->first();
        }

        // Jika perusahaan tidak ditemukan, create baru
        if (!$perusahaan && !empty($rowArray['nama'])) {
            $perusahaan = Perusahaan::create([
                'nama' => $rowArray['nama'],
                'kontak' => $rowArray['kontak'] ?? null,
                'alamat' => $rowArray['lokasi'] ?? null,
            ]);
        }
        
        if (!$perusahaan) {
            return; // Skip row if no company
        }

        // Sanitize Kapasitas
        // Try to find reasonable keys for kapasitas if 'kapasitas' is ambiguous or missing
        // Often keys are slugs: 'kapasitas_mw', 'kapasitas', etc.
        // We rely on $rowArray['kapasitas'] primarily, or check column P (16) or R (18) if needed.
        // Assuming 'kapasitas' header exists unique enough or we trust the array:
        // Helper to safely get calculated value
        $getSafeValue = function($col, $row) use ($worksheet) {
            $cell = $worksheet->getCell($col . $row);
            try {
                return $cell->getCalculatedValue();
            } catch (\Exception $e) {
                // Fallback to old calculated value if formula fails (e.g. missing table reference)
                return $cell->getOldCalculatedValue();
            }
        };

        $jumlahRaw = $getSafeValue('N', $rowIndex);
        $kapasitasRaw = $getSafeValue('O', $rowIndex);
        $totalKapasitasRaw = $getSafeValue('P', $rowIndex);

        $jumlah = (int) $jumlahRaw;
        $kapasitas = $this->sanitizeDecimal($kapasitasRaw);
        $totalKapasitas = $this->sanitizeDecimal($totalKapasitasRaw);



        // Update or Create Perizinan
        // Consider what makes a Perizinan unique? No pengajuan or combination?
        // For import, we might just append or update if ID exists (but we don't have ID).
        // Let's create new for now as per previous logic (it was returning new Perizinan).
        
        // Explicitly fetch columns based on user feedback and template
        $tanggalTerbitRaw = $worksheet->getCell('J' . $rowIndex)->getValue(); // Column 10: Tanggal Terbit
        $tanggalAkhirRaw = $getSafeValue('K', $rowIndex);  // Column 11: Tanggal Akhir
        $catatanRaw = $worksheet->getCell('S' . $rowIndex)->getValue();       // Column 19: Catatan
        $noSuratIzinTerbitRaw = $worksheet->getCell('I' . $rowIndex)->getValue(); // Column 9: No Surat Izin Terbit
        $noSuratKeluarRaw = $worksheet->getCell('G' . $rowIndex)->getValue();     // Column 7: No Surat Keluar (Rekomtek/Pertek)

        // DEBUG DATE LOGGING
        // Check what we are actually getting
        $debugMsg = "Row {$rowIndex} | J (Terbit): " . json_encode($tanggalTerbitRaw) . " | K (Akhir): " . json_encode($tanggalAkhirRaw) . "\n";
        file_put_contents(storage_path('logs/debug_dates.log'), $debugMsg, FILE_APPEND);

        Perizinan::create([
            'nama'              => $rowArray['nama'],
            'perusahaan_id'     => $perusahaan->id,
            'kontak'            => $rowArray['kontak'] ?? null,
            'jenis'             => $jenisIzin ?: 'IUPTLS',
            'no_pengajuan'      => $rowArray['no_pengajuan'] ?? null,
            'no_surat_keluar'   => $noSuratKeluarRaw,
            'tanggal'           => $this->transformDate($rowArray['tanggal'] ?? null),
            'no_surat_izin_terbit'=> $noSuratIzinTerbitRaw,
            'tanggal_terbit'    => $this->transformDate($tanggalTerbitRaw), 
            'tanggal_akhir'     => $this->transformDate($tanggalAkhirRaw),
            'lokasi'            => $rowArray['lokasi'] ?? null,
            'titik_koordinat'   => $rowArray['titik_koordinat'] ?? null,
            'jumlah'            => $rowArray['jumlah'] ?? 0,
            'kapasitas'         => $kapasitas,
            'total_kapasitas_kva'=> $totalKapasitas,
            'jenis_penggunaan'  => $jenisPembangkit,
            'sifat_penggunaan'  => $rowArray['sifat_penggunaan'] ?? null,
            'catatan'           => $catatanRaw, 
            'created_by'        => auth()->id(),
        ]);
    }

    private function transformDate($value, $format = 'Y-m-d')
    {
        if (empty($value)) return null;

        try {
            // Check if value is numeric (Excel serial date)
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            }
            
            // Handle Indonesian Month Names (e.g. 28 Oktober 2022)
            $value = str_ireplace(
                ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                $value
            );

            // Parse as string
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function sanitizeDecimal($value)
    {
        if (empty($value)) return 0;
        
        // Remove non-numeric characters except dot and comma
        // Convert comma to dot if used as decimal separator
        $cleaned = preg_replace('/[^0-9.,]/', '', $value);
        
        // If multiple dots, keep last one? Standardize to 1234.56
        // Simple case: replace comma with dot
        $cleaned = str_replace(',', '.', $cleaned);
        
        return (float) $cleaned;
    }
}
