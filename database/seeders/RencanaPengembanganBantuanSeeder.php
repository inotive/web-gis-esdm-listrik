<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\RencanaPengembanganBantuan;

class RencanaPengembanganBantuanSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        $filePath = base_path('Rencana Pengembangan Bantuan Ketenagalistrikan.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: {$filePath}");
            return;
        }

        $this->command->info('Membaca file Excel...');

        try {
            // Load spreadsheet
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Hapus data lama
            DB::table('rencana_pengembangan_bantuan')->truncate();
            $this->command->info('Data lama dihapus.');

            // Skip header row (row 1)
            $data = [];
            $rowCount = 0;

            foreach ($rows as $index => $row) {
                // Skip header row
                if ($index === 0) {
                    continue;
                }

                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                // Mapping kolom berdasarkan struktur Excel
                // A: LOKASI, B: Desa, C: Kecamatan, D: Kabupaten/Kota, E: Provinsi
                // F: Status Desa Berlistrik, G: KODIFIKASI, H: Jumlah Penduduk
                // I: Jumlah Calon Pelanggan, J: AKSESIBILITAS, K: Skor
                // L: RADIUS KE JARINGAN TERDEKAT, M: Skor RADIUS KE JARINGAN TERDEKAT
                // N: ARAH DAN KEBIJAKAN TATA RUANG, O: Skor ARAH DAN KEBIJAKAN TATA RUANG
                // P: POTENSI KEGIATAN, Q: Skor Potensi, R: JUMLAH PELANGGAN
                // S: R Jumlah Pelanggan, T: Total Skor, U: Prioritas

                $lokasi = $row[0] ?? null;
                $desa = $row[1] ?? null;
                $kecamatan = $row[2] ?? null;
                $kabupatenKota = $row[3] ?? null;
                $provinsi = $row[4] ?? null;
                $statusDesaBerlistrik = $row[5] ?? null;
                $kodifikasi = $row[6] ?? null;
                $jumlahPenduduk = $this->parseInteger($row[7] ?? null);

                // Cari village_id berdasarkan nama desa
                $villageId = $this->findVillageId($desa, $kecamatan, $kabupatenKota);
                $districtId = $this->findDistrictId($kecamatan, $kabupatenKota);
                $regencyId = $this->findRegencyId($kabupatenKota);

                // Parse data
                $jumlahCalonPelanggan = $this->parseInteger($row[8] ?? null);
                $aksesibilitas = $row[9] ?? null;
                $skorAksesibilitas = $this->parseInteger($row[10] ?? null);
                $radiusJaringan = $row[11] ?? null;
                $skorRadius = $this->parseInteger($row[12] ?? null);
                $arahKebijakan = $row[13] ?? null;
                $skorArahKebijakan = $this->parseInteger($row[14] ?? null);
                $potensiKegiatan = $row[15] ?? null;
                $skorPotensiKegiatan = $this->parseInteger($row[16] ?? null);
                $jumlahPelanggan = $row[17] ?? null;
                $skorJumlahPelanggan = $this->parseInteger($row[18] ?? null);
                $totalSkor = $this->parseInteger($row[19] ?? null);
                $prioritas = $row[20] ?? null;

                $data[] = [
                    'regency_id' => $regencyId,
                    'district_id' => $districtId,
                    'village_id' => $villageId,
                    'lokasi' => $lokasi,
                    'status_desa_berlistrik' => $statusDesaBerlistrik,
                    'kodifikasi' => $kodifikasi,
                    'jumlah_penduduk' => $jumlahPenduduk,
                    'jumlah_calon_pelanggan' => $jumlahCalonPelanggan,
                    'aksesibilitas' => $aksesibilitas,
                    'skor_aksesibilitas' => $skorAksesibilitas,
                    'radius_jaringan' => $radiusJaringan,
                    'skor_radius' => $skorRadius,
                    'arah_kebijakan' => $arahKebijakan,
                    'skor_arah_kebijakan' => $skorArahKebijakan,
                    'potensi_kegiatan' => $potensiKegiatan,
                    'skor_potensi_kegiatan' => $skorPotensiKegiatan,
                    'jumlah_pelanggan' => $jumlahPelanggan,
                    'skor_jumlah_pelanggan' => $skorJumlahPelanggan,
                    'total_skor' => $totalSkor,
                    'prioritas' => $prioritas,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $rowCount++;

                // Insert per 100 rows
                if (count($data) >= 100) {
                    DB::table('rencana_pengembangan_bantuan')->insert($data);
                    $this->command->info("Inserted {$rowCount} rows...");
                    $data = [];
                }
            }

            // Insert remaining data
            if (!empty($data)) {
                DB::table('rencana_pengembangan_bantuan')->insert($data);
            }

            $this->command->info("✓ Selesai! Total {$rowCount} data berhasil di-seed.");

        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
            $this->command->error('Trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Find village ID by name
     */
    private function findVillageId($desaName, $kecamatanName, $kabupatenName)
    {
        if (empty($desaName)) {
            return null;
        }

        $village = DB::table('reg_villages as v')
            ->join('reg_districts as d', 'v.district_id', '=', 'd.id')
            ->join('reg_regencies as r', 'd.regency_id', '=', 'r.id')
            ->where('v.name', 'LIKE', '%' . $desaName . '%')
            ->where('d.name', 'LIKE', '%' . $kecamatanName . '%')
            ->where('r.name', 'LIKE', '%' . $kabupatenName . '%')
            ->select('v.id')
            ->first();

        return $village ? $village->id : null;
    }

    /**
     * Find district ID by name
     */
    private function findDistrictId($kecamatanName, $kabupatenName)
    {
        if (empty($kecamatanName)) {
            return null;
        }

        $district = DB::table('reg_districts as d')
            ->join('reg_regencies as r', 'd.regency_id', '=', 'r.id')
            ->where('d.name', 'LIKE', '%' . $kecamatanName . '%')
            ->where('r.name', 'LIKE', '%' . $kabupatenName . '%')
            ->select('d.id')
            ->first();

        return $district ? $district->id : null;
    }

    /**
     * Find regency ID by name
     */
    private function findRegencyId($kabupatenName)
    {
        if (empty($kabupatenName)) {
            return null;
        }

        $regency = DB::table('reg_regencies')
            ->where('name', 'LIKE', '%' . $kabupatenName . '%')
            ->select('id')
            ->first();

        return $regency ? $regency->id : null;
    }

    /**
     * Parse integer value
     */
    private function parseInteger($value)
    {
        if (empty($value)) {
            return null;
        }

        // Remove non-numeric characters except minus sign
        $cleaned = preg_replace('/[^0-9-]/', '', $value);

        return is_numeric($cleaned) ? (int) $cleaned : null;
    }
}
