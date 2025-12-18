<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PtHasilLokasiSurveiEsdm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportHasilLokasiSurveiEsdmCommand extends Command
{
    protected $signature = 'import:hasil-lokasi-survei-esdm';
    protected $description = 'Import Hasil Lokasi Survei ESDM GeoJSON data';

    public function handle()
    {
        $this->info('Starting Hasil Lokasi Survei ESDM data import...');

        // Clear existing records
        PtHasilLokasiSurveiEsdm::truncate();

        // Path to the GeoJSON file
        $geoJsonPath = public_path('assets/PT_Hasil_Lokasi_Survei_ESDM.json');

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

            $model = new PtHasilLokasiSurveiEsdm();
            $model->Lokasi = $properties['Lokasi'] ?? null;
            $model->NAMOBJ = $properties['NAMOBJ'] ?? null;
            $model->LUASWH = $properties['LUASWH'] ?? null;
            $model->TIPADM = $properties['TIPADM'] ?? null;
            $model->WADMKC = $properties['WADMKC'] ?? null;
            $model->WADMKD = $properties['WADMKD'] ?? null;
            $model->WADMKK = $properties['WADMKK'] ?? null;
            $model->WADMPR = $properties['WADMPR'] ?? null;
            $model->Status = $properties['Status'] ?? null;
            $model->Kode_Kota = $properties['Kode_Kota'] ?? null;
            $model->Kode_L = $properties['Kode_L'] ?? null;
            $model->Lokasi_Ke = $properties['Lokasi_Ke'] ?? null;
            $model->Kodifikasi = $properties['Kodifikasi'] ?? null;
            $model->DUSUN = $properties['DUSUN'] ?? null;
            $model->JUMLAH_RT = $properties['JUMLAH_RT'] ?? null;
            $model->KET_RT = $properties['KET_RT'] ?? null;
            $model->J_Pnddk = $properties['J_Pnddk'] ?? null;
            $model->J_KK = $properties['J_KK'] ?? null;
            $model->J_BRumah = $properties['J_BRumah'] ?? null;
            $model->J_BFasum = $properties['J_BFasum'] ?? null;
            $model->Ket_BFasum = $properties['Ket_BFasum'] ?? null;
            $model->S_L_Kom = $properties['S_L_Kom'] ?? null;
            $model->N_S_L = $properties['N_S_L'] ?? null;
            $model->K_S_L = $properties['K_S_L'] ?? null;
            $model->S_P_L = $properties['S_P_L'] ?? null;
            $model->W_NYALA = $properties['W_NYALA'] ?? null;
            $model->L_NYALA = $properties['L_NYALA'] ?? null;
            $model->T_SL = $properties['T_SL'] ?? null;
            $model->Knd_S_L = $properties['Knd_S_L'] ?? null;
            $model->Koor_X = $properties['Koor_X'] ?? null;
            $model->Koor_Y = $properties['Koor_Y'] ?? null;
            $model->PR_Prov = $properties['PR_Prov'] ?? null;
            $model->K_Hutan = $properties['K_Hutan'] ?? null;
            $model->Izin_Lain = $properties['Izin_Lain'] ?? null;
            $model->Potensi = $properties['Potensi'] ?? null;
            $model->R_JUTAMA = $properties['R_JUTAMA'] ?? null;
            $model->R_JLISTRIK = $properties['R_JLISTRIK'] ?? null;
            $model->K_Jalan = $properties['K_Jalan'] ?? null;
            $model->L_Jalan = $properties['L_Jalan'] ?? null;
            $model->P_Jalan = $properties['P_Jalan'] ?? null;
            $model->PENYULANG = $properties['PENYULANG'] ?? null;
            $model->R_S_L = $properties['R_S_L'] ?? null;
            $model->KENDALA = $properties['KENDALA'] ?? null;
            $model->I_IUPT = $properties['I_IUPT'] ?? null;
            $model->I_PPBH = $properties['I_PPBH'] ?? null;
            $model->I_IUPK = $properties['I_IUPK'] ?? null;
            $model->J_Gardu = $properties['J_Gardu'] ?? null;
            $model->B_Gardu = $properties['B_Gardu'] ?? null;
            $model->S_L_P = $properties['S_L_P'] ?? null;
            $model->K_RPLTS = $properties['K_RPLTS'] ?? null;
            $model->Panjang = $properties['Panjang'] ?? null;
            $model->Tiang = $properties['Tiang'] ?? null;
            $model->Biaya = $properties['Biaya'] ?? null;
            $model->Skor_A = $properties['Skor_A'] ?? null;
            $model->Skor_J = $properties['Skor_J'] ?? null;
            $model->K_PR = $properties['K_PR'] ?? null;
            $model->K_Izin = $properties['K_Izin'] ?? null;
            $model->K_Hutan_1 = $properties['K_Hutan_1'] ?? null;
            $model->S_Arah = $properties['S_Arah'] ?? null;
            $model->S_Potensi = $properties['S_Potensi'] ?? null;
            $model->S_J_P = $properties['S_J_P'] ?? null;
            $model->T_S = $properties['T_S'] ?? null;
            $model->Cek = $properties['Cek'] ?? null;
            $model->Priorita_1 = $properties['Priorita_1'] ?? null;
            $model->B_PLTS = $properties['B_PLTS'] ?? null;
            $model->geom = $geometry;

            $model->save();
            $importedCount++;
        }

        $this->info("Successfully imported {$importedCount} Hasil Lokasi Survei ESDM records.");
        return 0;
    }
}