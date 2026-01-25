<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportJsonVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '-1');
        DB::disableQueryLog();
        DB::table('json_videos')->truncate();

        $jsonPath = public_path('video.json');


        if (!file_exists($jsonPath)) {
            $this->command->error("File public/video.json tidak ditemukan.");
            return;
        }

        $videos = json_decode(file_get_contents($jsonPath), true);

        foreach ($videos as $video) {
            $kodifikasi = $video['Kodifikasi'] ?? null;
            $link = $video['link'] ?? null;

            if (!$kodifikasi || !$link) continue;

            $features = \App\Models\ImportedJsonFeature::where('kategori', 'kondisi-titik-pemukiman-non-listrik-pln')
                ->where('properties->Kodifikasi', $kodifikasi)
                ->get();

            if ($features->count() > 0) {
                foreach ($features as $feature) {
                    \App\Models\JsonVideo::create([
                        'imported_json_features_id' => $feature->id,
                        'kodifikasi' => $kodifikasi,
                        'link' => $link
                    ]);
                }
                $this->command->info("Linked video for {$kodifikasi} ({$features->count()} features)");
            } else {
                $this->command->warn("Feature not found for Kodifikasi: {$kodifikasi}");
            }
        }
    }
}
