<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_Kubar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanKubarCommand extends Command
{
    protected $signature = 'import:jalan-kubar';
    protected $description = 'Import Kubar Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting Kubar Jalan data import...');

        // Clear existing records
        LN_Jalan_Kubar::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/Kubar.json');

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

            $model = new LN_Jalan_Kubar();
            $model->Nm_Ruas = $properties['Nm_Ruas'] ?? null;
            $model->Fungsi = $properties['Fungsi'] ?? null;
            $model->Panjang = $properties['Panjang'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Kubar Jalan records.");
        return 0;
    }
}