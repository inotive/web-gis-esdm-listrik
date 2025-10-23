<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Models\AssetDokumen;
use App\Models\UnitKerja;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class ImportGeoJSONCommand extends Command
{
    protected $signature = 'asset:import-geojson 
                            {--update : Update existing assets instead of skip}
                            {--force : Force import with auto-suffix for duplicates}
                            {--debug : Show detailed debug information}';
    protected $description = 'Import data aset dari file GeoJSON ke tabel asset';

    private $debugMode = false;
    private $districtCache = [];
    private $villageCache = [];

    public function handle()
    {
        $path = public_path('assets/all_aset.json');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan di: {$path}");
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!isset($data['features'])) {
            $this->error("File GeoJSON tidak valid.");
            return;
        }

        $updateMode = $this->option('update');
        $forceMode = $this->option('force');
        $this->debugMode = $this->option('debug');

        if ($updateMode) {
            $this->info("🔄 Mode: UPDATE (data yang sudah ada akan di-update)");
        } elseif ($forceMode) {
            $this->info("⚡ Mode: FORCE (duplicate akan diberi suffix otomatis)");
        } else {
            $this->info("⏭️  Mode: SKIP (duplicate akan dilewati)");
        }

        if ($this->debugMode) {
            $this->warn("🐛 Debug mode: ON");
        }

        $this->info("Memulai import " . count($data['features']) . " data aset...");
        $this->info("📍 Kecamatan & Kelurahan akan diambil dari database (default per kabupaten)");
        $this->newLine();

        $this->buildDefaultCache();

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $duplicate = 0;
        $newUnitKerja = [];
        $notFoundRegencies = [];
        $foundRegencies = [];
        $autoSetDistricts = 0;
        $autoSetVillages = 0;
        $dokumenCreated = 0;
        $dokumenUpdated = 0;

        foreach ($data['features'] as $index => $feature) {
            $props = $feature['properties'] ?? [];

            if (!isset($feature['geometry'])) {
                $this->warn("  ⚠️  [{$index}] Data tanpa geometry: " . ($props['N_Aset'] ?? 'Unknown'));
                $skipped++;
                continue;
            }

            // Convert luas
            $luas = $props['Luas'] ?? null;
            if ($luas !== null) {
                $luas = str_replace(',', '.', $luas);
                $luas = floatval($luas);
            }

            $luas_1 = $props['Luas_1'] ?? null;
            if ($luas_1 !== null) {
                $luas_1 = str_replace(',', '.', $luas_1);
                $luas_1 = floatval($luas_1);
            }

            // === KOORDINAT LAT/LONG ===
            $latitude = $props['Lat'] ?? null;
            $longitude = $props['Long'] ?? null;
            
            if ($latitude !== null && $latitude != 0) {
                $latitude = floatval(str_replace(',', '.', $latitude));
            } else {
                $latitude = null;
            }
            
            if ($longitude !== null && $longitude != 0) {
                $longitude = floatval(str_replace(',', '.', $longitude));
            } else {
                $longitude = null;
            }

            $kodeAsset = $props['K_Aset'] ?? uniqid('AST-');

            try {
                // === CARI/BUAT UNIT KERJA ===
                $unitKerjaId = null;
                if (!empty($props['U_Kerja'])) {
                    $unitKerja = UnitKerja::where('nama_unit', $props['U_Kerja'])->first();
                    
                    if (!$unitKerja) {
                        $unitKerja = UnitKerja::create([
                            'kode_unit' => 'UK-' . strtoupper(substr(md5($props['U_Kerja']), 0, 6)),
                            'nama_unit' => $props['U_Kerja'],
                            'deskripsi' => 'Auto-generated dari import GeoJSON'
                        ]);
                        
                        if (!in_array($props['U_Kerja'], $newUnitKerja)) {
                            $newUnitKerja[] = $props['U_Kerja'];
                            $this->line("  ➕ Unit Kerja baru: {$props['U_Kerja']}");
                        }
                    }
                    
                    $unitKerjaId = $unitKerja->id;
                }

                // === CARI KABUPATEN/KOTA ===
                $regencyId = null;
                $districtId = null;
                $villageId = null;
                $provinceId = '64';

                $kabkotaField = $props['KabKota'] ?? $props['Kabkota'] ?? null;

                if (!empty($kabkotaField) && trim($kabkotaField) !== '') {
                    $kabkotaSearch = trim($kabkotaField);
                    
                    $kabupatenMapping = [
                        'Kota Samarinda' => '6472',
                        'Samarinda' => '6472',
                        'Kota Balikpapan' => '6471',
                        'Balikpapan' => '6471',
                        'Kota Bontang' => '6474',
                        'Bontang' => '6474',
                        'Kutai Kartanegara' => '6409',
                        'Kab. Kutai Kartanegara' => '6409',
                        'Kukar' => '6409',
                        'Kutai Timur' => '6408',
                        'Kab. Kutai Timur' => '6408',
                        'Kutim' => '6408',
                        'Kutai Barat' => '6407',
                        'Kab. Kutai Barat' => '6407',
                        'Kubar' => '6407',
                        'Berau' => '6402',
                        'Kab. Berau' => '6402',
                        'Paser' => '6403',
                        'Kab. Paser' => '6403',
                        'Penajam Paser Utara' => '6410',
                        'Kab. Penajam Paser Utara' => '6410',
                        'PPU' => '6410',
                        'Mahakam Ulu' => '6411',
                        'Kab. Mahakam Ulu' => '6411',
                    ];
                    
                    $regency = null;
                    
                    if (isset($kabupatenMapping[$kabkotaSearch])) {
                        $regencyId = $kabupatenMapping[$kabkotaSearch];
                        $regency = RegRegency::find($regencyId);
                        
                        if ($this->debugMode && $regency) {
                            $this->line("  🎯 Mapping: {$kabkotaSearch} → {$regency->name}");
                        }
                    }
                    
                    if (!$regency) {
                        $regency = RegRegency::where('name', $kabkotaSearch)->first();
                    }
                    
                    if (!$regency) {
                        $regency = RegRegency::where('name', strtoupper($kabkotaSearch))
                            ->where('province_id', '64')
                            ->first();
                    }
                    
                    if (!$regency) {
                        $cleanName = str_replace(['Kab. ', 'Kota ', 'Kabupaten ', 'Kab ', 'Kota '], '', $kabkotaSearch);
                        $regency = RegRegency::where('name', 'LIKE', "%{$cleanName}%")
                            ->where('province_id', '64')
                            ->first();
                    }
                    
                    if (!$regency) {
                        $regency = RegRegency::where('name', 'LIKE', "%{$kabkotaSearch}%")
                            ->where('province_id', '64')
                            ->first();
                    }
                    
                    if ($regency) {
                        $regencyId = $regency->id;
                        $provinceId = $regency->province_id;
                        
                        if (!in_array($kabkotaSearch, $foundRegencies)) {
                            $foundRegencies[] = $kabkotaSearch;
                            if ($this->debugMode) {
                                $this->line("  ✓ Kabupaten: {$kabkotaSearch} → ID: {$regencyId} ({$regency->name})");
                            }
                        }

                        if (isset($this->districtCache[$regencyId])) {
                            $districtId = $this->districtCache[$regencyId];
                            $autoSetDistricts++;
                            
                            if ($this->debugMode) {
                                $district = RegDistrict::find($districtId);
                                $this->line("  📍 Set default kecamatan: {$district->name}");
                            }
                        }

                        if ($districtId && isset($this->villageCache[$districtId])) {
                            $villageId = $this->villageCache[$districtId];
                            $autoSetVillages++;
                            
                            if ($this->debugMode) {
                                $village = RegVillage::find($villageId);
                                $this->line("  📍 Set default kelurahan: {$village->name}");
                            }
                        }
                        
                    } else {
                        if (!in_array($kabkotaSearch, $notFoundRegencies)) {
                            $notFoundRegencies[] = $kabkotaSearch;
                            $this->warn("  ⚠️  Kabupaten tidak ditemukan: {$kabkotaSearch}");
                        }
                    }
                }

                // Prepare data asset
                $assetData = [
                    'nama_asset'       => $props['N_Aset'] ?? 'Tanpa Nama',
                    'no_register'      => $props['No_Regis'] ?? null,
                    'penggunaan_spma'  => $props['Guna'] ?? null,
                    'luas_m2'          => $luas ?? $luas_1,
                    'unit_kerja_id'    => $unitKerjaId,
                    'reg_provinces_id' => $provinceId,
                    'reg_regencies_id' => $regencyId,
                    'reg_districts_id' => $districtId,
                    'reg_villages_id'  => $villageId,
                    'nomor_hak'        => $props['No_Sertif'] ?? null,
                    'jenis_hak'        => $props['H_Tanah'] ?? null,
                    'asal'             => $props['Asal'] ?? $props['Asal_Perolehan'] ?? null,
                    'kat_tanah'        => $props['Kat_Tanah'] ?? $props['Kategori_Tanah'] ?? null,
                    'kode'             => $props['K_Lokasi'] ?? null,
                    'alamat'           => $props['Alamat'] ?? null,
                    'latitude'         => $latitude,
                    'longitude'        => $longitude,
                    'geojson'          => json_encode($feature['geometry']),
                ];

                // Prepare data dokumen
                $dokumenData = [
                    'no_sertif'        => $props['No_Sertif'] ?? null,
                    'tgl_sertif'       => $this->parseDate($props['Tgl_Sertif'] ?? null),
                    'nama_sertifikat'  => $props['Nama_Sert'] ?? null,
                    'sts_digit'        => $props['Sts_Digit'] ?? null,
                    'sts_sertif'       => $props['Sts_Sertif'] ?? null,
                    'ket_sertif'       => $props['Ket_Sertif'] ?? null,
                    'konf_tanah'       => $props['Konf_Tanah'] ?? null,

                    'no_dokumen'       => $props['No_Dokumen'] ?? null,
                    'tanggal_dokumen'  => $this->parseDate($props['Tgl_Dokumen'] ?? null),
                    'tanggal_oleh'     => $this->parseDate($props['Tgl_Oleh'] ?? null),
                    'tanggal_buku'     => $this->parseDate($props['Tgl_Buku'] ?? null),
                    'has_konfir'       => isset($props['Has_Konfir']) ? (bool)$props['Has_Konfir'] : false,

                    'file_sertif'      => null,
                    'link_sertif'      => null,
                ];

                $existingAsset = Asset::where('kode_asset', $kodeAsset)->first();

                if ($existingAsset) {
                    if ($updateMode) {
                        $existingAsset->update($assetData);
                        $updated++;
                        
                        // Update atau create dokumen
                        if ($existingAsset->dokumenUtama) {
                            $existingAsset->dokumenUtama->update($dokumenData);
                            $dokumenUpdated++;
                        } else {
                            $existingAsset->dokumenUtama()->create($dokumenData);
                            $dokumenCreated++;
                        }
                        
                        if ($updated % 25 == 0) {
                            $this->line("  🔄 Updated: {$updated}");
                        }
                    } elseif ($forceMode) {
                        $counter = 1;
                        $newKodeAsset = $kodeAsset;
                        while (Asset::where('kode_asset', $newKodeAsset)->exists()) {
                            $newKodeAsset = $kodeAsset . '-' . $counter;
                            $counter++;
                        }
                        
                        $newAsset = Asset::create(array_merge($assetData, ['kode_asset' => $newKodeAsset]));
                        $newAsset->dokumenUtama()->create($dokumenData);
                        
                        $imported++;
                        $dokumenCreated++;
                        
                        if ($this->debugMode) {
                            $this->line("  ➕ [{$index}] Created with suffix: {$newKodeAsset}");
                        }
                    } else {
                        $duplicate++;
                        if ($duplicate % 50 == 0 && !$this->debugMode) {
                            $this->line("  ⏭️  Skipped duplicates: {$duplicate}");
                        }
                    }
                } else {
                    // INSERT BARU
                    $newAsset = Asset::create(array_merge($assetData, ['kode_asset' => $kodeAsset]));
                    $newAsset->dokumenUtama()->create($dokumenData);
                    
                    $imported++;
                    $dokumenCreated++;
                    
                    if ($imported % 50 == 0 && !$this->debugMode) {
                        $this->line("  📊 Progress: {$imported} imported");
                    }
                }

            } catch (QueryException $e) {
                if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $duplicate++;
                    if ($this->debugMode) {
                        $this->warn("  ⚠️  [{$index}] Duplicate: {$kodeAsset}");
                    }
                } else {
                    $this->error("  ❌ [{$index}] Error: " . ($props['N_Aset'] ?? 'Unknown'));
                    if ($this->debugMode) {
                        $this->error("     Message: " . $e->getMessage());
                    }
                    $skipped++;
                }
            } catch (\Exception $e) {
                $this->error("  ❌ [{$index}] Error: " . ($props['N_Aset'] ?? 'Unknown'));
                if ($this->debugMode) {
                    $this->error("     Message: " . $e->getMessage());
                }
                $skipped++;
            }
        }

        // === SUMMARY REPORT ===
        $this->newLine();
        $this->info("╔════════════════════════════════════════════╗");
        $this->info("║          IMPORT SELESAI                    ║");
        $this->info("╚════════════════════════════════════════════╝");
        $this->info("📊 Total data dalam GeoJSON: " . count($data['features']));
        $this->info("✅ Berhasil diimport: {$imported}");
        
        if ($updateMode) {
            $this->info("🔄 Berhasil diupdate: {$updated}");
        }
        
        if ($duplicate > 0) {
            $this->warn("⚠️  Duplicate (dilewati): {$duplicate}");
        }
        
        $this->info("❌ Error/Skipped: {$skipped}");
        
        // Dokumen summary
        if ($dokumenCreated > 0 || $dokumenUpdated > 0) {
            $this->newLine();
            $this->info("📄 Dokumen/Sertifikat:");
            $this->info("   • Dokumen baru dibuat: {$dokumenCreated}");
            if ($dokumenUpdated > 0) {
                $this->info("   • Dokumen diupdate: {$dokumenUpdated}");
            }
        }
        
        if ($autoSetDistricts > 0 || $autoSetVillages > 0) {
            $this->newLine();
            $this->info("📍 Auto-Set dari Database:");
            $this->info("   • Kecamatan di-set: {$autoSetDistricts}");
            $this->info("   • Kelurahan di-set: {$autoSetVillages}");
        }
        
        if (count($foundRegencies) > 0) {
            $this->newLine();
            $this->info("✓ Kabupaten/Kota Ditemukan: " . count($foundRegencies));
            if ($this->debugMode) {
                foreach ($foundRegencies as $reg) {
                    $this->line("   • {$reg}");
                }
            }
        }
        
        if (count($newUnitKerja) > 0) {
            $this->newLine();
            $this->info("➕ Unit Kerja Baru Dibuat: " . count($newUnitKerja));
            foreach (array_unique($newUnitKerja) as $uk) {
                $this->line("   • {$uk}");
            }
        }
        
        if (count($notFoundRegencies) > 0) {
            $this->newLine();
            $this->warn("⚠️  Kabupaten/Kota Tidak Ditemukan: " . count($notFoundRegencies));
            foreach (array_unique($notFoundRegencies) as $reg) {
                $this->line("   • {$reg}");
            }
        }
        
        $this->newLine();
        
        if ($duplicate > 0 && !$updateMode && !$forceMode) {
            $this->newLine();
            $this->comment("💡 Tips:");
            $this->line("   • Update data existing: php artisan asset:import-geojson --update");
            $this->line("   • Force dengan suffix: php artisan asset:import-geojson --force");
            $this->line("   • Debug detail: php artisan asset:import-geojson --debug");
        }
        
        return 0;
    }

    private function buildDefaultCache()
    {
        $this->info("📋 Membangun cache kecamatan & kelurahan default...");
        
        $regencies = RegRegency::where('province_id', '64')->get();
        
        foreach ($regencies as $regency) {
            $district = RegDistrict::where('regency_id', $regency->id)
                ->orderBy('name')
                ->first();
            
            if ($district) {
                $this->districtCache[$regency->id] = $district->id;
                
                $village = RegVillage::where('district_id', $district->id)
                    ->orderBy('name')
                    ->first();
                
                if ($village) {
                    $this->villageCache[$district->id] = $village->id;
                }
                
                if ($this->debugMode) {
                    $this->line("  • {$regency->name} → {$district->name}" . ($village ? " → {$village->name}" : ""));
                }
            }
        }
        
        $this->info("✓ Cache berhasil dibuat untuk " . count($this->districtCache) . " kabupaten");
        $this->newLine();
    }

    private function parseDate($dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            // Coba parse berbagai format tanggal
            $date = Carbon::parse($dateString);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}