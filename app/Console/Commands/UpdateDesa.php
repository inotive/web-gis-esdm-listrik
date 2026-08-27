<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RegVillage;
use App\Models\RegDistrict;
use App\Models\ImportedJsonFeature;
use Illuminate\Support\Facades\Cache;

class UpdateDesa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-desa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status berlistrik desa dari file excel Tabel_Data_Desa_Berlistrik_2026_Gabungan.xlsx';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = public_path('assets/Tabel_Data_Desa_Berlistrik_2026_Gabungan.xlsx');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return;
        }

        $this->info("Membaca file excel...");
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $countRegVillage = 0;
        $countFeature1 = 0;
        $countFeature2 = 0;

        // Skip baris 0, 1, 2 (header ada di baris 2)
        // Data mulai dari baris 3
        for ($i = 3; $i < count($rows); $i++) {
            $row = $rows[$i];

            $kecamatanName = trim($row[4] ?? '');
            $desaName = trim($row[5] ?? '');
            $status = trim($row[6] ?? '');

            // Normalisasi nama kecamatan
            if (strtolower($kecamatanName) === 'semboja barat' || strtolower($kecamatanName) === 'samboja barat') {
                $kecamatanName = 'Samboja Barat';
            }

            // Clean invisible characters like zero-width space
            $status = preg_replace('/[\x00-\x1F\x7F-\x9F\xE2\x80\x8B-\xE2\x80\x8F]/u', '', $status);
            $status = trim($status);

            if (!$desaName || !$status) {
                continue;
            }

            // 1. Update tabel RegVillage
            $desa = RegVillage::where('name', $desaName)
                ->whereHas('district', function ($q) use ($kecamatanName) {
                    $q->where('name', $kecamatanName);
                })
                ->first();

            // Fallback: cari dengan like jika exact match tidak ketemu
            if (!$desa) {
                $desa = RegVillage::where('name', 'like', "%{$desaName}%")
                    ->whereHas('district', function ($q) use ($kecamatanName) {
                        $q->where('name', 'like', "%{$kecamatanName}%");
                    })
                    ->first();
            }

            if ($desa) {
                $desa->status_berlistrik = $status;
                $desa->save();
                $countRegVillage++;
            } else {
                // Cari kecamatan (district)
                $district = RegDistrict::where('name', $kecamatanName)->first();
                if (!$district) {
                    $district = RegDistrict::where('name', 'like', "%{$kecamatanName}%")->first();
                }

                if ($district) {
                    // Cari ID desa tertinggi di kecamatan ini untuk increment
                    $maxVillage = RegVillage::where('district_id', $district->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($maxVillage) {
                        $suffix = substr($maxVillage->id, 6);
                        $nextSuffix = str_pad((int)$suffix + 1, 4, '0', STR_PAD_LEFT);
                        $newId = $district->id . $nextSuffix;
                    } else {
                        // Default jika belum ada desa sama sekali di kecamatan tersebut
                        $newId = $district->id . '2001';
                    }

                    // Tambah desa baru
                    $desa = RegVillage::create([
                        'id' => $newId,
                        'district_id' => $district->id,
                        'name' => $desaName,
                        'status_berlistrik' => $status,
                    ]);

                    $this->info("Menambahkan desa baru: {$desaName} (ID: {$newId}, Kec. {$kecamatanName})");
                    $countRegVillage++;
                } else {
                    $this->warn("Desa tidak ditemukan di db & gagal menambahkan (Kecamatan tidak ditemukan): {$desaName} (Kec. {$kecamatanName})");
                }
            }

            // 2. Update tabel ImportedJsonFeature (sub_kategori = Status Desa Berlistrik)
            $features1 = ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->where(function ($q) use ($desaName) {
                    $q->where('properties->Nama_Desa', $desaName)
                        ->orWhere('properties->Desa', $desaName);
                })
                ->get();

            foreach ($features1 as $feature1) {
                $props = $feature1->properties;
                $props['StatusDesa'] = $status;
                $feature1->properties = $props;
                $feature1->save();
                $countFeature1++;
            }

            // 3. Update tabel ImportedJsonFeature (sub_kategori = Status Desa Berlistrik dengan Bantuan)
            $features2 = ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik dengan Bantuan')
                ->where(function ($q) use ($desaName) {
                    $q->where('properties->Nama_Desa', $desaName)
                        ->orWhere('properties->Desa', $desaName);
                })
                ->get();

            foreach ($features2 as $feature2) {
                $props = $feature2->properties;
                $props['Status_Des'] = $status;
                $feature2->properties = $props;
                $feature2->save();
                $countFeature2++;
            }
        }

        Cache::flush();

        $this->info("Proses update selesai!");
        $this->info("- Total RegVillage terupdate: {$countRegVillage}");
        $this->info("- Total Feature 'Status Desa Berlistrik' terupdate: {$countFeature1}");
        $this->info("- Total Feature 'Status Desa Berlistrik dengan Bantuan' terupdate: {$countFeature2}");
    }
}
