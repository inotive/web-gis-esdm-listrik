<?php

namespace Database\Seeders;

use App\Models\RegRegency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '-1');
        DB::disableQueryLog();

        // Disable Foreign Key Check
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        DB::table('json_videos')->truncate();
        DB::table('imported_json_features')->truncate();

        // Enable Foreign Key Check
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $jsonOutputPath = public_path('json_output');

        if (!File::exists($jsonOutputPath)) {
            $this->command->error("Directory not found: {$jsonOutputPath}");
            return;
        }

        // Cache Regencies for faster lookup: Name (upper) => ID
        // Note: RegRegency model uses IDs provided in the user snippet (e.g. 6401, 6402...)
        // We match folder name "Kab. Paser" -> "KAB. PASER" -> ID
        $regencies = RegRegency::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [Str::upper($name) => $id];
        })->toArray();

        $files = File::allFiles($jsonOutputPath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $relativePath = $file->getRelativePath();
            // Normalize path separators for Windows compatibility
            $relativePath = str_replace('\\', '/', $relativePath);

            $fileName = $file->getFilename();
            $fullPath = $file->getPathname();
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);

            $this->command->info("Processing: {$relativePath}/{$fileName}");

            $pathParts = explode('/', $relativePath);
            $rootFolder = $pathParts[0] ?? null;

            $kategori = $rootFolder;
            $subKategori = null;
            $subSubKategori = null;
            $regencyId = null;

            // Parts after root
            $innerParts = array_slice($pathParts, 1);

            // Logic for Sub and SubSub
            if (count($innerParts) > 0) {
                // First folder inside root is Sub Kategori
                $subKategori = $innerParts[0];

                // Remove it from parts
                $remainingParts = array_slice($innerParts, 1);

                if (!empty($remainingParts)) {
                    // There are deeper folders. Use them as sub_subkategori.
                    // File is irrelevant/grouped.
                    $subSubKategori = implode('/', $remainingParts);
                } else {
                    // No deeper folders. The file itself is the sub_subkategori.
                    $subSubKategori = $baseName;
                }
            } else {
                // No folder inside root, just file -> File is Sub Kategori
                $subKategori = $baseName;
                $subSubKategori = null;
            }

            $this->command->info("File: $relativePath -> Kategori: $kategori, Sub: $subKategori, SubSub: $subSubKategori");

            // Keep Regency ID detection logic (same as before)
            if ($rootFolder === 'Infrastruktur') {
                $regencyFolderName = $pathParts[1] ?? null;
                if ($regencyFolderName) {
                    $regencyId = $this->findRegencyId($regencyFolderName, $regencies);
                }
            } elseif ($rootFolder === 'Jalan') {
                $folderL1 = $pathParts[1] ?? null;
                if ($folderL1 && isset($pathParts[2])) {
                    $potentialRegency = $pathParts[2];
                    $foundId = $this->findRegencyId($potentialRegency, $regencies);
                    if ($foundId) {
                        $regencyId = $foundId;
                    }
                }
            } elseif ($rootFolder === 'Kondisi Titik Pemukiman Non Listrik PLN') {
                $regencyFolderName = $pathParts[1] ?? null;
                if ($regencyFolderName) {
                    $regencyId = $this->findRegencyId($regencyFolderName, $regencies);
                }
            } else {
                // Fallback for Jaringan Listrik etc
                $possibleRegency = $pathParts[1] ?? null;
                if ($possibleRegency) {
                    $regencyId = $this->findRegencyId($possibleRegency, $regencies);
                }
            }

            $jsonContent = File::get($fullPath);
            $data = json_decode($jsonContent, true);
            unset($jsonContent);

            if (!$data || !isset($data['features']) || !is_array($data['features'])) {
                $this->command->warn("Invalid GeoJSON or no features found in: {$fileName}");
                continue;
            }

            $featuresToInsert = [];
            foreach ($data['features'] as $feature) {
                // Transform properties if matches specific category
                $props = $feature['properties'] ?? [];


                if ($kategori == 'Kondisi Titik Pemukiman Non Listrik PLN' && $subSubKategori == 'Identifikasi Lokasi Pemukiman') {
                    $mapping = [
                        'WADMKD' => 'Desa',
                        'WADMKC' => 'Kecamatan',
                        'WADMKK' => 'Kabupaten',
                        'WADMPR' => 'Provinsi',
                        'Status' => 'Status Desa Listrik',
                        'Kodifikasi' => 'Kodifikasi',
                        'DUSUN' => 'Nama Dusun',
                        'J_RT' => 'Jumlah RT',
                        'J_Pnddk' => 'Nama RT', // Mengikuti lampiran user, meskipun tidak umum
                        'J_KK' => 'Jumlah Penduduk', // Mengikuti lampiran user
                        'J_BRumah' => 'Jumlah KK', // Mengikuti lampiran user
                        'J_BFasum' => 'Jumlah Bangunan Rumah', // Mengikuti lampiran user
                        'Ket_BFasum' => 'Jumlah Bangunan Fasilitas Umum', // Mengikuti lampiran user
                        'S_L_Kom' => 'Sumber Listrik Komunal',
                        'N_S_L' => 'Nama Sumber Listrik',
                        'K_S_L' => 'Kapasitas Sumber Listrik',
                        'S_P_L' => 'Sumber Pendanaan Listrik',
                        'W_NYALA' => 'Waktu Nyala',
                        'L_NYALA' => 'Lama Nyala',
                        'T_SL' => 'Tahun Sumber Listrik',
                        'Knd_S_L' => 'Kondisi Sumber Listrik',
                        'Koor_X' => 'Koordinat X',
                        'Koor_Y' => 'Koordinat Y',
                    ];

                    $newProps = [];
                    foreach ($props as $key => $val) {
                        $newKey = $mapping[$key] ?? $key;
                        $newProps[$newKey] = $val;
                    }
                    $props = $newProps;
                } elseif ($kategori == 'Kondisi Titik Pemukiman Non Listrik PLN' && $subSubKategori == 'Rencana Bantuan Lokasi Pemukiman') {
                    $mapping = [
                        'NAMOBJ' => 'Objek',
                        'LUASWH' => 'Luas Wilayah Administrasi',
                        'TIPADM' => 'Tipe Administrasi',
                        'WADMKC' => 'Kecamatan',
                        'WADMKD' => 'Desa',
                        'WADMKK' => 'Kabupaten',
                        'WADMPR' => 'Provinsi',
                        'Status' => 'Status Desa Listrik',
                        'Kode_Kota' => 'Kode Kota',
                        'Kode_L' => 'Kode Lokasi',
                        'Lokasi_Ke' => 'Lokasi Ke',
                        'Kodifikasi' => 'Kodifikasi',
                        'DUSUN' => 'Nama Dusun',
                        'J_RT' => 'Jumlah RT',
                        'KET_RT' => 'Nama RT',
                        'J_Pnddk' => 'Jumlah Penduduk',
                        'J_KK' => 'Jumlah KK',
                        'J_BRumah' => 'Jumlah Bangunan Rumah',
                        'J_BFasum' => 'Jumlah Bangunan Fasilitas Umum',
                        'Ket_BFasum' => 'Keterangan Bangunan Fasilitas Umum',
                        'S_L_Kom' => 'Sumber Listrik Komunal',
                        'N_S_L' => 'Nama Sumber Listrik',
                        'K_S_L' => 'Kapasitas Sumber Listrik',
                        'S_P_L' => 'Sumber Pendanaan Listrik',
                        'W_NYALA' => 'Waktu Nyala',
                        'L_NYALA' => 'Lama Nyala',
                        'T_SL' => 'Tahun Sumber Listrik',
                        'Knd_S_L' => 'Kondisi Sumber Listrik',
                        'Koor_X' => 'Koordinat X',
                        'Koor_Y' => 'Koordinat Y',
                        'PR_Prov' => 'Pola Ruang RTRW Kaltim 2023-2042',
                        'K_Hutan' => 'Kawasan Hutan (SK 397 2025)',
                        'Izin_Lain' => 'Izin Pemenfaatan Ruang',
                        'Potensi' => 'Potensi Lokasi',
                        'R_JUTAMA' => 'Jarak ke Jalan Utama',
                        'R_JLISTRIK' => 'Jarak Ke Jaringan Grid Listrik',
                        'K_Jalan' => 'Kondisi Jalan',
                        'L_Jalan' => 'Lebar Jalan',
                        'P_Jalan' => 'Panjang Jalan',
                        'PENYULANG' => 'Sumber Penyulang Terdekat',
                        'R_S_L' => 'Rencana Sumber Listrik',
                        'KENDALA' => 'Kendala Pengembangan Jaringan Listrik',
                        'I_IUPT' => 'Izin IUP Tambang',
                        'I_PPBH' => 'Izin PPBH Hutan',
                        'I_IUPK' => 'Izin IUP Kebun',
                        'S_L_P' => 'Sumber Listrik Perencanaan',
                        'Prioritas' => 'Prioritas',
                    ];

                    $newProps = [];
                    foreach ($props as $key => $val) {
                        $newKey = $mapping[$key] ?? $key;
                        $newProps[$newKey] = $val;
                    }
                    $props = $newProps;
                }

                $properties = isset($props) ? json_encode($props) : null;
                $geometry = isset($feature['geometry']) ? json_encode($feature['geometry']) : null;

                $featuresToInsert[] = [
                    'kategori' => Str::slug($kategori),
                    'sub_kategori' => $subKategori,
                    'sub_subkategori' => $subSubKategori,
                    'regency_id' => $regencyId,
                    'properties' => $properties,
                    'geometry' => $geometry,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($featuresToInsert) >= 50) {
                    DB::table('imported_json_features')->insert($featuresToInsert);
                    $featuresToInsert = [];
                }
            }

            if (!empty($featuresToInsert)) {
                DB::table('imported_json_features')->insert($featuresToInsert);
            }
        }

        $this->command->info('JSON import completed.');
    }

    private function findRegencyId($folderName, $regencies)
    {
        $normalized = Str::upper($folderName);

        // Exact match
        if (isset($regencies[$normalized])) {
            return $regencies[$normalized];
        }

        // Helper to match "Kab. Paser" with "KAB. PASER" - normalization helps
        // What about "Kab Paser" vs "KAB. PASER"?
        // Try strict normalize: remove dots?
        // Let's just try simple lookup first as user data seems consistent.
        // User paths: "Kab. Paser", "Kota Samarinda"
        // DB Names: "KAB. PASER", "KOTA SAMARINDA"
        // Str::upper("Kab. Paser") -> "KAB. PASER". Match!

        return null;
    }
}
