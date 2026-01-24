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
        DB::table('imported_json_features')->truncate();

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
            $fileName = $file->getFilename();
            $fullPath = $file->getPathname();
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);

            $this->command->info("Processing: {$relativePath}/{$fileName}");

            $pathParts = explode('/', $relativePath);
            $rootFolder = $pathParts[0] ?? null;

            // Determine categorization strategy based on root folder
            $kategori = $rootFolder;
            $subKategori = null;
            $regencyId = null;

            if ($rootFolder === 'Administrasi') {
                // Aturan: folder kategori, file subkategori, regency null
                $subKategori = $baseName;
            } elseif ($rootFolder === 'Desa Berlistrik') {
                // Aturan: folder kategori, file subkategori, regency null
                $subKategori = $baseName;
            } elseif ($rootFolder === 'Infrastruktur') {
                // Aturan: folder infrastructure -> kategori
                // folder kota/kabupaten -> regency_id
                // file -> subkategori
                // Path examples: Infrastruktur / Kab. Paser / Gardu.json
                $regencyFolderName = $pathParts[1] ?? null;
                if ($regencyFolderName) {
                    $regencyId = $this->findRegencyId($regencyFolderName, $regencies);
                }
                $subKategori = $baseName;
            } elseif ($rootFolder === 'Jalan') {
                // Aturan:
                // folder jalan -> kategori
                // folder didalamnya -> subkategori (e.g. Jalan Kabupaten, Jalan Nasional?)
                // if subkategori == 'Jalan Kabupaten' -> folder didalamnya = regency_id
                // Path examples: Jalan / Jalan Kabupaten / Kab. Paser / data.json
                // Path examples: Jalan / Jalan Nasional / data.json

                $folderL1 = $pathParts[1] ?? null;
                $subKategori = $folderL1;

                // Check for deeper structure for Regency ID
                if ($folderL1) {
                    // Start checking from index 2 for regency
                    if (isset($pathParts[2])) {
                        $potentialRegency = $pathParts[2];
                        $foundId = $this->findRegencyId($potentialRegency, $regencies);
                        if ($foundId) {
                            $regencyId = $foundId;
                        }
                    }
                }
                // Filename as extra detail or ignored? User didn't specify filename usage for Jalan broadly,
                // but usually filename implies content. Let's keep subKategori as the folder type (Jalan Kabupaten)
                // and maybe if there's no deeper folder, filename is the feature source.
            } elseif ($rootFolder === 'Kondisi Titik Pemukiman Non Listrik PLN') {
                // Aturan: kategori
                // folder kota/kabupaten -> regency_id
                // file -> subkategori
                $regencyFolderName = $pathParts[1] ?? null;
                if ($regencyFolderName) {
                    $regencyId = $this->findRegencyId($regencyFolderName, $regencies);
                }
                $subKategori = $baseName;
            } else {
                // Fallback / Jaringan Listrik case (User didn't explicitly mention Jaringan Listrik rules in the LAST message,
                // but logically it follows Infrastruktur pattern usually. Or generic.)
                // Let's assume generic pattern:
                // Kategori = Root
                // Regency = Check L1
                // Sub = Filename
                $possibleRegency = $pathParts[1] ?? null;
                if ($possibleRegency) {
                    $regencyId = $this->findRegencyId($possibleRegency, $regencies);
                }
                $subKategori = $baseName;
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
                $properties = isset($feature['properties']) ? json_encode($feature['properties']) : null;
                $geometry = isset($feature['geometry']) ? json_encode($feature['geometry']) : null;

                $featuresToInsert[] = [
                    'kategori' => Str::slug($kategori),
                    'sub_kategori' => $subKategori ? Str::slug($subKategori) : null,
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
