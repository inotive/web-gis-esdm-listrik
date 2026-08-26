<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RegVillage;
use App\Models\ImportedJsonFeature;
use Illuminate\Support\Facades\Cache;

class SyncDesaStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esdm:sync-desa-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi status kelistrikan di ImportedJsonFeature mengikuti data di tabel reg_villages (local database)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi status kelistrikan desa...');

        $villages = RegVillage::all();
        $totalSynced = 0;

        foreach ($villages as $village) {
            $localStatus = $village->status_berlistrik;
            if (!$localStatus) {
                continue;
            }

            // 1. Sinkronisasi kategori "Status Desa Berlistrik" (Key: StatusDesa)
            $featuresListrik = ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->where(function ($q) use ($village) {
                    $q->where('properties->Nama_Desa', $village->name)
                        ->orWhere('properties->Desa', $village->name);
                })
                ->get();

            foreach ($featuresListrik as $feature) {
                $props = $feature->properties;
                $currentStatus = $props['StatusDesa'] ?? null;

                if ($currentStatus !== $localStatus) {
                    $props['StatusDesa'] = $localStatus;
                    $feature->properties = $props;
                    $feature->save();
                    $totalSynced++;
                    $this->line("Synced [{$village->name}] (Status Desa Berlistrik): '{$currentStatus}' -> '{$localStatus}'");
                }
            }

            // 2. Sinkronisasi kategori "Status Desa Berlistrik dengan Bantuan" (Key: Status_Des)
            $featuresBantuan = ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik dengan Bantuan')
                ->where(function ($q) use ($village) {
                    $q->where('properties->Nama_Desa', $village->name)
                        ->orWhere('properties->Desa', $village->name);
                })
                ->get();

            foreach ($featuresBantuan as $feature) {
                $props = $feature->properties;
                $currentStatus = $props['Status_Des'] ?? null;

                if ($currentStatus !== $localStatus) {
                    $props['Status_Des'] = $localStatus;
                    $feature->properties = $props;
                    $feature->save();
                    $totalSynced++;
                    $this->line("Synced [{$village->name}] (Status Desa Berlistrik dengan Bantuan): '{$currentStatus}' -> '{$localStatus}'");
                }
            }
        }

        if ($totalSynced > 0) {
            Cache::flush();
            $this->info("Sinkronisasi selesai! Berhasil memperbarui {$totalSynced} data feature.");
        } else {
            $this->info('Sinkronisasi selesai! Tidak ada perbedaan data yang perlu disinkronkan.');
        }

        return Command::SUCCESS;
    }
}
