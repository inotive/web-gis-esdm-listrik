<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DataJaringanSeeder extends Seeder
{
    /**
     * Base path untuk data jaringan di storage
     */
    private string $basePath = '2. Data Jaringan';

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Ambil user pertama sebagai creator (atau bisa disesuaikan)
        $user = User::first();

        if (!$user) {
            $this->command->error('User tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Path lengkap di storage/app/public
        $storagePath = storage_path('app/public/' . $this->basePath);

        // Cek apakah folder ada
        if (!File::exists($storagePath)) {
            $this->command->error("Folder tidak ditemukan: {$storagePath}");
            $this->command->info("Pastikan folder '2. Data Jaringan' ada di storage/app/public/");
            return;
        }

        // Buat folder utama "2. Data Jaringan"
        $dataJaringan = Dokumen::create([
            'nama' => '2. Data Jaringan',
            'tipe' => 'folder',
            'parent_id' => null,
            'user_id' => $user->id,
        ]);

        $this->command->info('✓ Folder utama "2. Data Jaringan" berhasil dibuat');
        $this->command->newLine();

        // Scan dan proses folder secara rekursif
        $this->processDirectory($storagePath, $dataJaringan, $user);

        $this->command->newLine();
        $this->command->info('✅ Seeder Data Jaringan selesai!');
    }

    /**
     * Proses directory secara rekursif
     */
    private function processDirectory(string $directoryPath, Dokumen $parentFolder, User $user, int $level = 1): void
    {
        // Ambil semua items di directory
        $items = File::glob($directoryPath . '/*');

        if (empty($items)) {
            $this->command->warn(str_repeat('  ', $level) . "⚠ Folder kosong: " . basename($directoryPath));
            return;
        }

        // Pisahkan folder dan file
        $folders = [];
        $files = [];

        foreach ($items as $item) {
            if (File::isDirectory($item)) {
                $folders[] = $item;
            } else {
                $files[] = $item;
            }
        }

        // Sort alphabetically
        sort($folders);
        sort($files);

        // Proses folders terlebih dahulu
        foreach ($folders as $folderPath) {
            $folderName = basename($folderPath);

            // Skip hidden folders dan backup folders
            if (str_starts_with($folderName, '.') || str_contains($folderName, 'backup')) {
                continue;
            }

            // Buat folder entry
            $folder = Dokumen::create([
                'nama' => $folderName,
                'tipe' => 'folder',
                'parent_id' => $parentFolder->id,
                'user_id' => $user->id,
            ]);

            $indent = str_repeat('  ', $level);
            $this->command->info("{$indent}📁 Folder: {$folderName}");

            // Proses subfolder secara rekursif
            $this->processDirectory($folderPath, $folder, $user, $level + 1);
        }

        // Proses files
        foreach ($files as $filePath) {
            $fileName = basename($filePath);

            // Skip hidden files, backup files, dan temporary files
            if (str_starts_with($fileName, '.') ||
                str_contains($fileName, '.backup.') ||
                str_ends_with($fileName, '~') ||
                str_ends_with($fileName, '.tmp')) {
                continue;
            }

            $this->createFileEntry($filePath, $parentFolder, $user, $level);
        }
    }

    /**
     * Buat entry file dokumen
     */
    private function createFileEntry(string $filePath, Dokumen $parentFolder, User $user, int $level = 1): void
    {
        $fileName = basename($filePath);

        // Cek apakah file ada dan readable
        if (!File::exists($filePath) || !File::isReadable($filePath)) {
            $this->command->warn(str_repeat('  ', $level) . "⚠ File tidak dapat dibaca: {$fileName}");
            return;
        }

        // Dapatkan informasi file
        $fileSize = File::size($filePath);
        $mimeType = File::mimeType($filePath) ?: 'application/octet-stream';

        // Hitung path relatif dari storage/app/public
        $storageBasePath = storage_path('app/public');
        $relativePath = str_replace($storageBasePath . '/', '', $filePath);

        // Buat entry dokumen
        Dokumen::create([
            'nama' => $this->formatFileName($fileName),
            'tipe' => 'file',
            'parent_id' => $parentFolder->id,
            'path' => $relativePath,
            'mime_type' => $mimeType,
            'size' => $fileSize,
            'user_id' => $user->id,
        ]);

        $indent = str_repeat('  ', $level);
        $sizeFormatted = $this->formatBytes($fileSize);
        $this->command->info("{$indent}📄 File: {$fileName} ({$sizeFormatted})");
    }

    /**
     * Format nama file untuk ditampilkan
     */
    private function formatFileName(string $fileName): string
    {
        // Hapus ekstensi
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        // Replace underscore dengan spasi
        $name = str_replace('_', ' ', $name);

        return $name;
    }

    /**
     * Format bytes ke format yang readable
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes === 0) {
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
