<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_Bontang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanBontangCommand extends Command
{
    protected $signature = 'import:jalan-bontang';
    protected $description = 'Import Bontang Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting Bontang Jalan data import...');

        // Clear existing records
        LN_Jalan_Bontang::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/Bontang.json');

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

            $model = new LN_Jalan_Bontang();
            $model->Kl_Dat_Das = $properties['Kl_Dat_Das'] ?? null;
            $model->Nm_Ruas = $properties['Nm_Ruas'] ?? null;
            $model->Thn_Data = $properties['Thn_Data'] ?? null;
            $model->Status = $properties['Status'] ?? null;
            $model->Fungsi = $properties['Fungsi'] ?? null;
            $model->Mendukung = $properties['Mendukung'] ?? null;
            $model->Ura_Dukung = $properties['Ura_Dukung'] ?? null;
            $model->Kd_Bd_PU = $properties['Kd_Bd_PU'] ?? null;
            $model->Kd_Jns_Inf = $properties['Kd_Jns_Inf'] ?? null;
            $model->Kd_Inf = $properties['Kd_Inf'] ?? null;
            $model->Propinsi = $properties['Propinsi'] ?? null;
            $model->Kab_Kota = $properties['Kab_Kota'] ?? null;
            $model->Kecamatan = $properties['Kecamatan'] ?? null;
            $model->Desa_Kel = $properties['Desa_Kel'] ?? null;
            $model->Tk_Ruas_Aw = $properties['Tk_Ruas_Aw'] ?? null;
            $model->Tk_Ruas_Ak = $properties['Tk_Ruas_Ak'] ?? null;
            $model->Kd_Patok = $properties['Kd_Patok'] ?? null;
            $model->Km_Awal = $properties['Km_Awal'] ?? null;
            $model->Km_Akhir = $properties['Km_Akhir'] ?? null;
            $model->Nm_Lintas = $properties['Nm_Lintas'] ?? null;
            $model->Kon_Baik = $properties['Kon_Baik'] ?? null;
            $model->Kon_Sdg = $properties['Kon_Sdg'] ?? null;
            $model->Kon_Rgn = $properties['Kon_Rgn'] ?? null;
            $model->Kon_Rusak = $properties['Kon_Rusak'] ?? null;
            $model->Kon_Mntp = $properties['Kon_Mntp'] ?? null;
            $model->Kon_T_Mntp = $properties['Kon_T_Mntp'] ?? null;
            $model->Panjang = $properties['Panjang'] ?? null;
            $model->Lbr_Keras = $properties['Lbr_Keras'] ?? null;
            $model->LHRT = $properties['LHRT'] ?? null;
            $model->VCR = $properties['VCR'] ?? null;
            $model->Tipe_Jln = $properties['Tipe_Jln'] ?? null;
            $model->MST = $properties['MST'] ?? null;
            $model->Tipe_Keras = $properties['Tipe_Keras'] ?? null;
            $model->Tanah_Kri = $properties['Tanah_Kri'] ?? null;
            $model->Macadam = $properties['Macadam'] ?? null;
            $model->Aspal = $properties['Aspal'] ?? null;
            $model->Rigid = $properties['Rigid'] ?? null;
            $model->Thn_Pen_Ak = $properties['Thn_Pen_Ak'] ?? null;
            $model->Jns_Pen = $properties['Jns_Pen'] ?? null;
            $model->Koord_X_Aw = $properties['Koord_X_Aw'] ?? null;
            $model->Koord_Y_Aw = $properties['Koord_Y_Aw'] ?? null;
            $model->Koord_X_Ak = $properties['Koord_X_Ak'] ?? null;
            $model->Koord_Y_Ak = $properties['Koord_Y_Ak'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Bontang Jalan records.");
        return 0;
    }
}