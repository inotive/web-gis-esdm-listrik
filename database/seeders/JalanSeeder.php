<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\LN_Jalan_Balikpapan;
use App\Models\LN_Jalan_Berau;
use App\Models\LN_Jalan_Bontang;
use App\Models\LN_Jalan_Kubar;
use App\Models\LN_Jalan_KutaiKartanegara;
use App\Models\LN_Jalan_Kutim;
use App\Models\LN_Jalan_Paser;
use App\Models\LN_Jalan_PPU;
use App\Models\LN_Jalan_Samarinda;

class JalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Jalan data seeding...');

        // Define the mapping of JSON files to models
        $mappings = [
            'Balikpapan.json' => LN_Jalan_Balikpapan::class,
            'Berau.json' => LN_Jalan_Berau::class,
            'Bontang.json' => LN_Jalan_Bontang::class,
            'Kubar.json' => LN_Jalan_Kubar::class,
            'Kukar.json' => LN_Jalan_KutaiKartanegara::class,
            'kutim.json' => LN_Jalan_Kutim::class,
            'Paser.json' => LN_Jalan_Paser::class,
            'PPU.json' => LN_Jalan_PPU::class,
            'Samarinda.json' => LN_Jalan_Samarinda::class,
        ];

        $basePath = public_path('assets/Jalan/jalan-kabupaten');

        foreach ($mappings as $jsonFile => $modelClass) {
            $filePath = $basePath . '/' . $jsonFile;

            if (!File::exists($filePath)) {
                $this->command->warn("File not found: {$jsonFile}");
                continue;
            }

            $this->command->info("Processing {$jsonFile}...");

            // Read and decode JSON
            $jsonContent = File::get($filePath);
            $data = json_decode($jsonContent, true);

            if (!$data || !isset($data['features'])) {
                $this->command->error("Invalid GeoJSON format in {$jsonFile}");
                continue;
            }

            // Clear existing data
            $modelClass::truncate();

            // Get model fillable fields
            $model = new $modelClass();
            $fillableFields = $model->getFillable();

            $count = 0;
            foreach ($data['features'] as $feature) {
                $properties = $feature['properties'] ?? [];
                $geometry = $feature['geometry'] ?? null;

                if (!$geometry) {
                    continue;
                }

                // Build the data array dynamically based on fillable fields
                $dataToInsert = [];
                foreach ($fillableFields as $field) {
                    if ($field === 'geom') {
                        $dataToInsert[$field] = json_encode($geometry);
                    } else {
                        $dataToInsert[$field] = $properties[$field] ?? null;
                    }
                }

                // Create the record
                $modelClass::create($dataToInsert);

                $count++;
            }

            $this->command->info("Imported {$count} features from {$jsonFile}");
        }

        $this->command->info('Jalan data seeding completed!');
    }
}
