<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clear existing data
        Dokumen::query()->delete();

        // Get admin user or first user
        $user = User::where('email', 'admin@example.com')->first() ?? User::first();

        if (!$user) {
            $this->command->error('No user found. Please run UserSeeder first.');
            return;
        }

        $this->command->info('Starting dokumen import from storage/app/public/dokumen/...');

        // Base path in storage
        $basePath = 'dokumen';

        // Check if base directory exists
        if (!Storage::disk('public')->exists($basePath)) {
            $this->command->warn("Directory 'storage/app/public/{$basePath}' does not exist.");
            $this->command->info("Creating directory...");
            Storage::disk('public')->makeDirectory($basePath);
            $this->command->info("Directory created. Please add your documents to 'storage/app/public/{$basePath}/'");
            return;
        }

        // Start recursive import from base path
        $this->importDirectory($basePath, null, $user->id);

        $totalFolders = Dokumen::where('tipe', 'folder')->count();
        $totalFiles = Dokumen::where('tipe', 'file')->count();

        $this->command->info("✓ Import completed!");
        $this->command->info("  - Folders: {$totalFolders}");
        $this->command->info("  - Files: {$totalFiles}");
        $this->command->info("  - Total: " . ($totalFolders + $totalFiles));
    }

    /**
     * Recursively import directory and its contents
     *
     * @param string $path Path relative to storage/app/public
     * @param int|null $parentId Parent dokumen ID
     * @param int $userId User ID
     * @return void
     */
    private function importDirectory(string $path, ?int $parentId, int $userId): void
    {
        // Get all items in directory
        $items = Storage::disk('public')->listContents($path, false);

        foreach ($items as $item) {
            $itemPath = $item['path'];
            $itemName = basename($itemPath);

            // Skip hidden files and system files
            if ($this->shouldSkip($itemName)) {
                continue;
            }

            if ($item['type'] === 'dir') {
                // Create folder record
                $folder = $this->createFolder($itemPath, $itemName, $parentId, $userId);

                $this->command->info("📁 {$itemPath}");

                // Recursively import subdirectory
                $this->importDirectory($itemPath, $folder->id, $userId);
            } else {
                // Create file record
                $this->createFile($itemPath, $itemName, $parentId, $userId);

                $this->command->info("📄 {$itemPath}");
            }
        }
    }

    /**
     * Create folder record
     *
     * @param string $path Full path relative to storage/app/public
     * @param string $name Folder name
     * @param int|null $parentId Parent dokumen ID
     * @param int $userId User ID
     * @return Dokumen
     */
    private function createFolder(string $path, string $name, ?int $parentId, int $userId): Dokumen
    {
        return Dokumen::create([
            'nama' => $name,
            'path' => $path,
            'tipe' => 'folder',
            'parent_id' => $parentId,
            'user_id' => $userId,
            'mime_type' => null,
            'size' => 0,
        ]);
    }

    /**
     * Create file record
     *
     * @param string $path Full path relative to storage/app/public
     * @param string $name File name
     * @param int|null $parentId Parent dokumen ID
     * @param int $userId User ID
     * @return Dokumen
     */
    private function createFile(string $path, string $name, ?int $parentId, int $userId): Dokumen
    {
        // Get file metadata
        $mimeType = Storage::disk('public')->mimeType($path);
        $size = Storage::disk('public')->size($path);

        return Dokumen::create([
            'nama' => $name,
            'path' => $path,
            'tipe' => 'file',
            'parent_id' => $parentId,
            'user_id' => $userId,
            'mime_type' => $mimeType,
            'size' => $size,
        ]);
    }

    /**
     * Check if file/folder should be skipped
     *
     * @param string $name File or folder name
     * @return bool
     */
    private function shouldSkip(string $name): bool
    {
        // Skip hidden files (starting with .)
        if (Str::startsWith($name, '.')) {
            return true;
        }

        // Skip backup files
        if (Str::endsWith($name, ['.backup', '.bak', '.tmp', '~'])) {
            return true;
        }

        // Skip system files
        $systemFiles = [
            'Thumbs.db',
            'desktop.ini',
            '.DS_Store',
            '__MACOSX',
        ];

        if (in_array($name, $systemFiles)) {
            return true;
        }

        return false;
    }
}
