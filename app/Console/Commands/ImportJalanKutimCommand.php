<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_Kutim;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanKutimCommand extends Command
{
    protected $signature = 'import:jalan-kutim';
    protected $description = 'Import Kutim Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting Kutim Jalan data import...');

        // Clear existing records
        LN_Jalan_Kutim::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/Kutim.json');

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

            $model = new LN_Jalan_Kutim();
            $model->Kl_Dat_Das = $properties['Kl_Dat_Das'] ?? null;
            $model->No_Ruas = $properties['No_Ruas'] ?? null;
            $model->Nm_Ruas = $properties['Nm_Ruas'] ?? null;
            $model->Fungsi = $properties['Fungsi'] ?? null;
            $model->Kecamatan = $properties['Kecamatan'] ?? null;
            $model->Desa_Kel = $properties['Desa_Kel'] ?? null;
            $model->Tk_Ruas_Aw = $properties['Tk_Ruas_Aw'] ?? null;
            $model->Tk_Ruas_Ak = $properties['Tk_Ruas_Ak'] ?? null;
            $model->Panjang = $properties['Panjang'] ?? null;
            $model->Koord_X_Aw = $properties['Koord_X_Aw'] ?? null;
            $model->Koord_Y_Aw = $properties['Koord_Y_Aw'] ?? null;
            $model->Koord_X_Ak = $properties['Koord_X_Ak'] ?? null;
            $model->Koord_Y_Ak = $properties['Koord_Y_Ak'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Kutim Jalan records.");
        return 0;
    }
}