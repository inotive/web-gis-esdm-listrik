<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_KutaiKartanegara;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanKutaiKartanegaraCommand extends Command
{
    protected $signature = 'import:jalan-kutai-kartanegara';
    protected $description = 'Import Kutai Kartanegara Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting Kutai Kartanegara Jalan data import...');

        // Clear existing records
        LN_Jalan_KutaiKartanegara::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/Kukar.json');

        if (!File::exists($geoJsonPath)) {
            $this->error('GeoJSON file not found: ' . $geoJsonPath);
            return 1;
        }

        $geoJsonContent = File::get($geoJsonPath);
        $geoJson = json_decode($geoJsonContent, true);

        if (!$geoJson) {
            $this->error('Invalid GeoJSON file: ' . $geoJsonPath);
            return 1;
        }

        $features = $geoJson['features'] ?? [];
        $importedCount = 0;

        foreach ($features as $feature) {
            $geometry = json_encode($feature['geometry']);

            $properties = $feature['properties'] ?? [];

            $model = new LN_Jalan_KutaiKartanegara();
            $model->NO_LAMA = $properties['NO_LAMA'] ?? null;
            $model->NO_BARU = $properties['NO_BARU'] ?? null;
            $model->NAMA_LAMA = $properties['NAMA_LAMA'] ?? null;
            $model->NAMA_BARU = $properties['NAMA_BARU'] ?? null;
            $model->P_Km = $properties['P_Km'] ?? null;
            $model->KECAMATAN = $properties['KECAMATAN'] ?? null;
            $model->URUT = $properties['URUT'] ?? null;
            $model->PANGKAL = $properties['PANGKAL'] ?? null;
            $model->UJUNG = $properties['UJUNG'] ?? null;
            $model->KOOR_PANGK = $properties['KOOR_PANGK'] ?? null;
            $model->KOOR_UJUNG = $properties['KOOR_UJUNG'] ?? null;
            $model->LEBAR_M = $properties['LEBAR_M'] ?? null;
            $model->FUNGSI = $properties['FUNGSI'] ?? null;
            $model->HISTORY = $properties['HISTORY'] ?? null;
            $model->Panjang = $properties['Panjang'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Kutai Kartanegara Jalan records.");
        return 0;
    }
}
