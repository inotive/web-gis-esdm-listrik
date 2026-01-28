<?php

namespace App\Imports;

use App\Models\RekapElektrifikasi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RekapElektrifikasiImport implements ToCollection, WithHeadingRow
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip if kabupaten_kota is missing
            if (!isset($row['kabupaten_kota']) || empty($row['kabupaten_kota'])) {
                continue;
            }

            // Find existing record or create new one
            RekapElektrifikasi::updateOrCreate(
                [
                    'tahun' => $this->tahun,
                    'kabupaten_kota' => $row['kabupaten_kota'],
                ],
                [
                    'no_urut' => $row['no'] ?? null,
                    'jumlah_desa' => $row['jumlah_desa'] ?? 0,
                    'jumlah_kk' => $row['jumlah_kk'] ?? 0,
                    'jumlah_penduduk' => $row['jumlah_penduduk'] ?? 0,
                    'desa_berlistrik_pln' => $row['desa_berlistrik_pln'] ?? 0,
                    'desa_berlistrik_non_pln' => $row['desa_berlistrik_non_pln'] ?? 0,
                    'desa_berlistrik_jumlah' => $row['desa_berlistrik_jumlah'] ?? 0,
                    'desa_belum_berlistrik' => $row['desa_belum_berlistrik'] ?? 0,
                    'kk_berlistrik_pln' => $row['kk_berlistrik_pln'] ?? 0,
                    'kk_berlistrik_non_pln' => $row['kk_berlistrik_non_pln'] ?? 0,
                    'kk_berlistrik_jumlah' => $row['kk_berlistrik_jumlah'] ?? 0,
                    'rasio_desa_berlistrik' => $row['rasio_desa_berlistrik'] ?? 0,
                    'jumlah_kk_belum_berlistrik' => $row['jumlah_kk_belum_berlistrik'] ?? 0,
                    'rasio_elektrifikasi' => $row['rasio_elektrifikasi'] ?? 0,
                ]
            );
        }
    }
}
