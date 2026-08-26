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

        $filePath = public_path('assets/Prioritas Rencana Bantuan Pembangunan Ketenagalistrikan(AutoRecovered).xlsx');

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
            RencanaPengembanganBantuan::truncate();
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

                // Mapping kolom berdasarkan struktur Excel terbaru:
                // 0: Provinsi, 1: Kabupaten, 2: Kecamatan, 3: Lokasi, 4: Desa, 5: Kodifikasi
                // 8: Status Desa PLN Berlistrik, 16: Jumlah Penduduk (jiwa), 18: Jumlah Calon Pelanggan
                // 67: AKSESIBILITAS, 68: Skor Aksesibilitas
                // 69: RADIUS KE JARINGAN EKSISTING, 70: Skor RADIUS KE JARINGAN EKSISTING
                // 71: ARAH DAN KEBIJAKAN TATA RUANG, 72: Skor ARAH DAN KEBIJAKAN TATA RUANG
                // 73: POTENSI KEGIATAN, 74: Skor Potensi
                // 75: JUMLAH PENGGUNA, 76: S Jumlah Pengguna, 77: Total Skor, 79: Rencana Sumber Listrik

                $provinsi = $row[0] ?? null;
                $kabupatenKota = $row[1] ?? null;
                $kecamatan = $row[2] ?? null;
                $lokasi = $row[3] ?? null;
                $desa = $row[4] ?? null;
                $kodifikasi = $row[5] ?? null;
                $statusDesaBerlistrik = $row[8] ?? null;
                $jumlahPenduduk = $this->parseInteger($row[16] ?? null);

                // Cari village_id berdasarkan nama desa
                $villageId = $this->findVillageId($desa, $kecamatan, $kabupatenKota);
                $districtId = $this->findDistrictId($kecamatan, $kabupatenKota);
                $regencyId = $this->findRegencyId($kabupatenKota);

                // Parse data
                $jumlahCalonPelanggan = $this->parseInteger($row[18] ?? null);

                $aksesibilitas = $row[67] ?? null;
                $skorAksesibilitas = $this->parseInteger($row[68] ?? null);
                $radiusJaringan = $row[69] ?? null;
                $skorRadius = $this->parseInteger($row[70] ?? null);
                $arahKebijakan = $row[71] ?? null;
                $skorArahKebijakan = $this->parseInteger($row[72] ?? null);
                $potensiKegiatan = $row[73] ?? null;
                $skorPotensiKegiatan = $this->parseInteger($row[74] ?? null);
                $jumlahPelanggan = $row[75] ?? null;
                $skorJumlahPelanggan = $this->parseInteger($row[76] ?? null);
                $totalSkor = $this->parseInteger($row[77] ?? null);
                $rencanaSumberListrik = $row[79] ?? null;

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
                    'rencana_sumber_listrik' => in_array($rencanaSumberListrik, ['SUTM', 'PLTS']) ? $rencanaSumberListrik : null,
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
     * Returns null if value exceeds MySQL INT range (to handle concatenated Excel cell values)
     */
    private function parseInteger($value)
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            return null;
        }

        // Remove non-numeric characters except minus sign
        $cleaned = preg_replace('/[^0-9-]/', '', (string) $value);

        if (!is_numeric($cleaned) || $cleaned === '' || $cleaned === '-') {
            return null;
        }

        $intVal = (int) $cleaned;

        // MySQL signed INT range: -2147483648 to 2147483647
        // Return null for out-of-range values (likely corrupted/concatenated cells in Excel)
        if ($intVal > 2147483647 || $intVal < -2147483648) {
            return null;
        }

        return $intVal;
    }
}
