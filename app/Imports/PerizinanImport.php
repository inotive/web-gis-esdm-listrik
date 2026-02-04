<?php

namespace App\Imports;

use App\Models\Perizinan;
use App\Models\Perusahaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use \Carbon\Carbon;

class PerizinanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function headingRow(): int
    {
        return 4; // The header row containing "Nama", "Kontak", etc.
    }

    public function model(array $row)
    {
        // Skip row 5 (which contains column numbers '1', '2', '3'...)
        // We check if 'nama' is exactly the string '3' (based on the template) or purely numeric
        if (isset($row['nama']) && $row['nama'] == '3') {
             return null;
        }

        // Cari perusahaan berdasarkan nama (case insensitive)
        $perusahaan = null;
        if (isset($row['nama'])) {
            $perusahaan = Perusahaan::where('nama', 'like', '%' . $row['nama'] . '%')->first();
        }

        // Jika perusahaan tidak ditemukan, create baru
        if (!$perusahaan && !empty($row['nama'])) {
            $perusahaan = Perusahaan::create([
                'nama' => $row['nama'],
                'kontak' => $row['kontak'] ?? null,
                'alamat' => $row['lokasi'] ?? null,
            ]);
        }
        
        if (!$perusahaan) {
            return null; // Skip row if no company
        }

        return new Perizinan([
            'nama'              => $row['nama'],
            'perusahaan_id'     => $perusahaan->id,
            'kontak'            => $row['kontak'] ?? null,
            'jenis'             => $row['jenis'] ?? 'IUPTLS',
            'no_pengajuan'      => $row['no_pengajuan'] ?? null,
            'no_surat_keluar'   => $row['surat_izin'] ?? $row['no_surat_izin'] ?? $row['no_surat_keluar'] ?? null,
            'tanggal'           => $this->transformDate($row['tanggal'] ?? null),
            'no_surat_izin_terbit'=> $row['no_surat_izin_terbit'] ?? null,
            'tanggal_terbit'    => $this->transformDate($row['tanggal_terbit'] ?? null),
            'tanggal_akhir'     => $this->transformDate($row['tanggal_akhir'] ?? null),
            'lokasi'            => $row['lokasi'] ?? null,
            'titik_koordinat'   => $row['titik_koordinat'] ?? null,
            'jumlah'            => $row['jumlah'] ?? 0,
            'kapasitas'         => $row['kapasitas'] ?? 0,
            'total_kapasitas_kva'=> $row['total_kapasitas'] ?? 0,
            'jenis_penggunaan'  => $row['jenis_penggunaan'] ?? null,
            'sifat_penggunaan'  => $row['sifat_penggunaan'] ?? null,
            'catatan'           => $row['catatan'] ?? null,
            'created_by'        => auth()->id(),
        ]);
    }

    private function transformDate($value, $format = 'Y-m-d')
    {
        if (empty($value)) return null;

        try {
            // Check if value is numeric (Excel serial date)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            // Otherwise parse as string
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }


}
