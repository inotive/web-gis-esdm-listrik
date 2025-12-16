<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_Berau;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanBerauCommand extends Command
{
    protected $signature = 'import:jalan-berau';
    protected $description = 'Import Berau Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting Berau Jalan data import...');

        // Clear existing records
        LN_Jalan_Berau::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/Berau.json');

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

            $model = new LN_Jalan_Berau();
            $model->OBJECTID = $properties['OBJECTID'] ?? null;
            $model->NO_RUAS = $properties['NO_RUAS'] ?? null;
            $model->NAMA_RUAS = $properties['NAMA_RUAS'] ?? null;
            $model->KAB_KOTA = $properties['KAB_KOTA'] ?? null;
            $model->TTK_PNGKAL = $properties['TTK_PNGKAL'] ?? null;
            $model->TTK_AKHIR = $properties['TTK_AKHIR'] ?? null;
            $model->PANJANG = $properties['PANJANG'] ?? null;
            $model->JKP_2 = $properties['JKP_2'] ?? null;
            $model->JKP_3 = $properties['JKP_3'] ?? null;
            $model->JKP_4 = $properties['JKP_4'] ?? null;
            $model->JLP = $properties['JLP'] ?? null;
            $model->Jling_P = $properties['Jling_P'] ?? null;
            $model->JAS = $properties['JAS'] ?? null;
            $model->JKS = $properties['JKS'] ?? null;
            $model->JLS = $properties['JLS'] ?? null;
            $model->Jling_S = $properties['Jling_S'] ?? null;
            $model->FUNGSI = $properties['FUNGSI'] ?? null;
            $model->STATUS = $properties['STATUS'] ?? null;
            $model->Shape_Leng = $properties['Shape_Leng'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Berau Jalan records.");
        return 0;
    }
}