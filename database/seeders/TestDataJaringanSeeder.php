<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use Illuminate\Database\Seeder;

class TestDataJaringanSeeder extends Seeder
{
    /**
     * Test dan verifikasi hasil seeding Data Jaringan
     */
    public function run(): void
    {
        $this->command->info('🔍 Memulai verifikasi Data Jaringan...');
        $this->command->newLine();

        // 1. Cek folder utama
        $dataJaringan = Dokumen::where('nama', '2. Data Jaringan')
            ->where('tipe', 'folder')
            ->whereNull('parent_id')
            ->first();

        if (!$dataJaringan) {
            $this->command->error('❌ Folder utama "2. Data Jaringan" tidak ditemukan!');
            return;
        }

        $this->command->info('✅ Folder utama "2. Data Jaringan" ditemukan');
        $this->command->info("   ID: {$dataJaringan->id}");
        $this->command->info("   Tipe: {$dataJaringan->tipe}");
        $this->command->info("   Parent ID: " . ($dataJaringan->parent_id ?? 'NULL'));
        $this->command->newLine();

        // 2. Cek subfolder
        $subfolders = $dataJaringan->children()->where('tipe', 'folder')->get();
        $this->command->info("📁 Jumlah subfolder: {$subfolders->count()}");
        $this->command->newLine();

        $totalFiles = 0;
        $totalSize = 0;

        foreach ($subfolders as $folder) {
            $files = $folder->children()->where('tipe', 'file')->get();
            $folderSize = $files->sum('size');
            $totalFiles += $files->count();
            $totalSize += $folderSize;

            $this->command->info("📂 {$folder->nama}");
            $this->command->info("   ID: {$folder->id}");
            $this->command->info("   Parent ID: {$folder->parent_id}");
            $this->command->info("   Jumlah file: {$files->count()}");
            $this->command->info("   Total ukuran: " . $this->formatBytes($folderSize));

            // List files
            foreach ($files as $file) {
                $this->command->line("   ├─ {$file->nama}");
                $this->command->line("      • Path: {$file->path}");
                $this->command->line("      • Size: " . $this->formatBytes($file->size));
                $this->command->line("      • MIME: {$file->mime_type}");
            }

            $this->command->newLine();
        }

        // 3. Summary
        $this->command->info('📊 RINGKASAN');
        $this->command->info("   Total folder: " . ($subfolders->count() + 1)); // +1 untuk folder utama
        $this->command->info("   Total file: {$totalFiles}");
        $this->command->info("   Total ukuran: " . $this->formatBytes($totalSize));
        $this->command->newLine();

        // 4. Cek integritas
        $this->command->info('🔧 CEK INTEGRITAS');

        $filesWithoutPath = Dokumen::where('tipe', 'file')
            ->whereNull('path')
            ->whereHas('parent', function($q) use ($dataJaringan) {
                $q->where('parent_id', $dataJaringan->id)
                  ->orWhere('id', $dataJaringan->id);
            })
            ->count();

        if ($filesWithoutPath > 0) {
            $this->command->warn("   ⚠ Ada {$filesWithoutPath} file tanpa path");
        } else {
            $this->command->info('   ✅ Semua file memiliki path');
        }

        $filesWithoutSize = Dokumen::where('tipe', 'file')
            ->whereNull('size')
            ->whereHas('parent', function($q) use ($dataJaringan) {
                $q->where('parent_id', $dataJaringan->id)
                  ->orWhere('id', $dataJaringan->id);
            })
            ->count();

        if ($filesWithoutSize > 0) {
            $this->command->warn("   ⚠ Ada {$filesWithoutSize} file tanpa ukuran");
        } else {
            $this->command->info('   ✅ Semua file memiliki ukuran');
        }

        $this->command->newLine();
        $this->command->info('✅ Verifikasi selesai!');
    }

    /**
     * Format bytes ke format yang readable
     */
    private function formatBytes(?int $bytes): string
    {
        if (!$bytes) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $bytes;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
