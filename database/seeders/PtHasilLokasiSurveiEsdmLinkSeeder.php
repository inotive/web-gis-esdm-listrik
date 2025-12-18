<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PtHasilLokasiSurveiEsdm;

class PtHasilLokasiSurveiEsdmLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $linkData = [
            [
                'kodifikasi' => 'BRU-TSG-1',
                'link_dokumen' => 'https://drive.google.com/file/d/1tofU5rLEpqp4jl_tsO2BLboJSjeSaZyT/view?usp=sharing'
            ],
            [
                'kodifikasi' => 'BRU-PGB-1',
                'link_dokumen' => 'https://drive.google.com/file/d/1v_3Rsib9R64FJ5QHAi3n2l68WzlX8A-u/view?usp=sharing'
            ],
            [
                'kodifikasi' => 'BRU-BIA-1',
                'link_dokumen' => 'https://drive.google.com/file/d/1pYAgUbfEzynFNh0c-QBgy3cd3nHbwyUg/view?usp=sharing'
            ],
            [
                'kodifikasi' => 'BRU-BIA-2',
                'link_dokumen' => 'https://drive.google.com/file/d/1pYAgUbfEzynFNh0c-QBgy3cd3nHbwyUg/view?usp=sharing'
            ],
            [
                'kodifikasi' => 'BRU-KRG-1',
                'link_dokumen' => 'https://drive.google.com/file/d/1U2gABysMhA7FNQvyU2RUnxAgpfmZKv28/view?usp=drive_link'
            ],
            [
                'kodifikasi' => 'BRU-MRP-1',
                'link_dokumen' => 'https://drive.google.com/file/d/129zxhPDpu5vuhvyKrkzbUZXJbYE6eALY/view?usp=sharing'
            ],
        ];

        foreach ($linkData as $data) {
            $record = PtHasilLokasiSurveiEsdm::where('Kodifikasi', $data['kodifikasi'])->first();

            if ($record) {
                $record->update(['link_dokumen' => $data['link_dokumen']]);
                $this->command->info("Updated link for: {$data['kodifikasi']}");
            } else {
                $this->command->warn("Kodifikasi not found: {$data['kodifikasi']}");
            }
        }

        $this->command->info('Link dokumen seeding completed!');
    }
}
