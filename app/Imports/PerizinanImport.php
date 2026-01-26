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
    public function model(array $row)
    {
        // Cari perusahaan berdasarkan nama (case insensitive)
        $perusahaan = null;
        if (isset($row['nama_perusahaan'])) {
            $perusahaan = Perusahaan::where('nama', 'like', '%' . $row['nama_perusahaan'] . '%')->first();
        }

        // Jika perusahaan tidak ditemukan, create baru (optional logic, tapi aman untuk sekarang skip atau create)
        // Di sini kita create baru jika contact ada, atau biarkan error/skip jika critical.
        // Untuk kemudahan, jika null, kita create dummy/temp atau biarkan validation fail. 
        // Tapi "ToModel" akan mencoba insert.
        // Kita paksa cari atau create.
        if (!$perusahaan && !empty($row['nama_perusahaan'])) {
            $perusahaan = Perusahaan::create([
                'nama' => $row['nama_perusahaan']
            ]);
        }
        
        if (!$perusahaan) {
            return null; // Skip row if no company
        }

        return new Perizinan([
            'nama'              => $row['nama_pemohon'] ?? $row['nama_perizinan'] ?? $row['nama'] ?? '-',
            'perusahaan_id'     => $perusahaan->id,
            'kontak'            => $row['kontak'] ?? null,
            'jenis'             => $row['jenis_permohonan'] ?? $row['jenis_perizinan'] ?? $row['jenis'] ?? 'Izin Usaha',
            'no_pengajuan'      => $row['no_pengajuan'] ?? null,
            'no_surat_keluar'   => $row['no_surat_keluar'] ?? null,
            'tanggal'           => $this->transformDate($row['tanggal'] ?? null),
            'lokasi'            => $row['lokasi'] ?? null,
            'status_kelistrikan'=> $this->mapStatus($row['status_kelistrikan'] ?? null),
            'titik_koordinat'   => $row['titik_koordinat'] ?? null,
            'jumlah_kapasitas'  => $row['jumlah_kapasitas'] ?? 0,
            'total_kapasitas_kva'=> $row['total_kapasitas_kva'] ?? 0,
            'jenis_penggunaan'  => $row['jenis_penggunaan'] ?? null,
            'sifat_penggunaan'  => $row['sifat_penggunaan'] ?? null,
            'catatan'           => $row['catatan'] ?? null,
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

    private function mapStatus($status)
    {
        $status = strtolower($status ?? '');
        if (str_contains($status, 'non') || str_contains($status, 'kuning')) return 'berlistrik_non_pln';
        if (str_contains($status, 'tidak') || str_contains($status, 'merah')) return 'tidak_berlistrik';
        return 'berlistrik_pln'; // Default green
    }
}
