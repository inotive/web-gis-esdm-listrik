<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_PPU;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanPPUCommand extends Command
{
    protected $signature = 'import:jalan-ppu';
    protected $description = 'Import PPU Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting PPU Jalan data import...');

        // Clear existing records
        LN_Jalan_PPU::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/PPU.json');

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

            $model = new LN_Jalan_PPU();
            $model->OID_ = $properties['OID_'] ?? null;
            $model->Name = $properties['Name'] ?? null;
            $model->FolderPath = $properties['FolderPath'] ?? null;
            $model->SymbolID = $properties['SymbolID'] ?? null;
            $model->Clamped = $properties['Clamped'] ?? null;
            $model->Shape_Leng = $properties['Shape_Leng'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} PPU Jalan records.");
        return 0;
    }
}