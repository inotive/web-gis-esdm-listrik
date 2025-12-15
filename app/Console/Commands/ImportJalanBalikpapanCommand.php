<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_Balikpapan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanBalikpapanCommand extends Command
{
    protected $signature = 'import:jalan-balikpapan';
    protected $description = 'Import Balikpapan Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting Balikpapan Jalan data import...');

        // Clear existing records
        LN_Jalan_Balikpapan::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/Balikpapan.json');

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

            $model = new LN_Jalan_Balikpapan();
            $model->Kecamatan = $properties['Kecamatan'] ?? null;
            $model->F18 = $properties['F18'] ?? null;
            $model->F19 = $properties['F19'] ?? null;
            $model->F20 = $properties['F20'] ?? null;
            $model->F21 = $properties['F21'] ?? null;
            $model->KODE_RUAS = $properties['KODE_RUAS'] ?? null;
            $model->NAMA_RUAS = $properties['NAMA_RUAS'] ?? null;
            $model->TAHUN_DATA = $properties['TAHUN_DATA'] ?? null;
            $model->FUNGSI = $properties['FUNGSI'] ?? null;
            $model->LEBAR = $properties['LEBAR'] ?? null;
            $model->PANJANG = $properties['PANJANG'] ?? null;
            $model->KOORD_X_AW = $properties['KOORD_X_AW'] ?? null;
            $model->KOORD_Y_AW = $properties['KOORD_Y_AW'] ?? null;
            $model->KOORD_X_AK = $properties['KOORD_X_AK'] ?? null;
            $model->KOORD_Y_AK = $properties['KOORD_Y_AK'] ?? null;
            $model->Shape_Le_1 = $properties['Shape_Le_1'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Balikpapan Jalan records.");
        return 0;
    }
}