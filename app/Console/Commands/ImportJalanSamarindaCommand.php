<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_Samarinda;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportJalanSamarindaCommand extends Command
{
    protected $signature = 'import:jalan-samarinda';
    protected $description = 'Import Samarinda Jalan GeoJSON data';

    public function handle()
    {
        $this->info('Starting Samarinda Jalan data import...');

        // Clear existing records
        LN_Jalan_Samarinda::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/Jalan/jalan-kabupaten/Samarinda.json');

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

            $model = new LN_Jalan_Samarinda();
            $model->NAME = $properties['NAME'] ?? null;
            $model->LAYER = $properties['LAYER'] ?? null;
            $model->OBJECTID_1 = $properties['OBJECTID_1'] ?? null;
            $model->OBJECTID = $properties['OBJECTID'] ?? null;
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
            $model->Kab_Kot = $properties['Kab_Kot'] ?? null;
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
            $model->Shape_Leng = $properties['Shape_Leng'] ?? null;
            $model->Shape_Le_1 = $properties['Shape_Le_1'] ?? null;
            $model->LENGTH = $properties['LENGTH'] ?? null;
            $model->LENGTH_3D = $properties['LENGTH_3D'] ?? null;
            $model->BEARING = $properties['BEARING'] ?? null;
            $model->LINE_STYLE = $properties['LINE_STYLE'] ?? null;
            $model->LINE_COLOR = $properties['LINE_COLOR'] ?? null;
            $model->LINE_WIDTH = $properties['LINE_WIDTH'] ?? null;
            $model->FONT_SIZE = $properties['FONT_SIZE'] ?? null;
            $model->FONT_COLOR = $properties['FONT_COLOR'] ?? null;
            $model->FONT_CHARS = $properties['FONT_CHARS'] ?? null;
            $model->FONT_WEIGH = $properties['FONT_WEIGH'] ?? null;
            $model->ELEVATION = $properties['ELEVATION'] ?? null;
            $model->MAP_NAME = $properties['MAP_NAME'] ?? null;
            $model->GM_LAYER = $properties['GM_LAYER'] ?? null;
            $model->GM_TYPE = $properties['GM_TYPE'] ?? null;
            $model->version = $properties['version'] ?? null;
            $model->highway = $properties['highway'] ?? null;
            $model->osm_id = $properties['id'] ?? null;
            $model->oneway = $properties['oneway'] ?? null;
            $model->boat = $properties['boat'] ?? null;
            $model->smoothness = $properties['smoothness'] ?? null;
            $model->START_TIME = $properties['START_TIME'] ?? null;
            $model->END_TIME = $properties['END_TIME'] ?? null;
            $model->Kord_X_Awa = $properties['Kord_X_Awa'] ?? null;
            $model->Kord_X_Akh = $properties['Kord_X_Akh'] ?? null;
            $model->Kord_Y_Awa = $properties['Kord_Y_Awa'] ?? null;
            $model->Kord_Y_Akh = $properties['Kord_Y_Akh'] ?? null;
            $model->Kord_Y_a_1 = $properties['Kord_Y_a_1'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Samarinda Jalan records.");
        return 0;
    }
}