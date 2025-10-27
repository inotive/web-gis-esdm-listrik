<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            echo "🔄 Membersihkan cache permission...\n";

            // Permissions definition
            $permissions = [
                // Dashboard
                ['name' => 'dashboard.view', 'group' => 'Dashboard', 'display_name' => 'Lihat Dashboard'],
                
                // Asset Management
                ['name' => 'asset.view', 'group' => 'Asset', 'display_name' => 'Lihat Daftar Asset'],
                ['name' => 'asset.create', 'group' => 'Asset', 'display_name' => 'Tambah Asset'],
                ['name' => 'asset.edit', 'group' => 'Asset', 'display_name' => 'Edit Asset'],
                ['name' => 'asset.delete', 'group' => 'Asset', 'display_name' => 'Hapus Asset'],
                ['name' => 'asset.peta', 'group' => 'Asset', 'display_name' => 'Lihat Peta Persebaran'],
                ['name' => 'asset.rekapitulasi', 'group' => 'Asset', 'display_name' => 'Lihat Rekapitulasi'],
                ['name' => 'asset.dokumen', 'group' => 'Asset', 'display_name' => 'Lihat Dokumen Asset'],
                ['name' => 'asset.export', 'group' => 'Asset', 'display_name' => 'Export Data Asset'],
                
                // Asset Table Columns
                ['name' => 'asset.column.kode', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Kode Asset'],
                ['name' => 'asset.column.kategori_tanah', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Kategori Tanah'],
                ['name' => 'asset.column.unit_kerja', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Unit Kerja'],
                ['name' => 'asset.column.status_hukum', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Status Hukum'],
                ['name' => 'asset.column.asal', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Asal Perolehan'],
                ['name' => 'asset.column.kabupaten', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Kabupaten'],
                ['name' => 'asset.column.kecamatan', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Kecamatan'],
                ['name' => 'asset.column.sertifikat', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Sertifikat'],
                ['name' => 'asset.column.aksi', 'group' => 'Kolom Tabel Asset', 'display_name' => 'Lihat Kolom Aksi'],
                
                // Role & Permission Management
                ['name' => 'role.view', 'group' => 'Role & Permission', 'display_name' => 'Lihat Daftar Role'],
                ['name' => 'role.create', 'group' => 'Role & Permission', 'display_name' => 'Tambah Role'],
                ['name' => 'role.edit', 'group' => 'Role & Permission', 'display_name' => 'Edit Role'],
                ['name' => 'role.delete', 'group' => 'Role & Permission', 'display_name' => 'Hapus Role'],
                ['name' => 'role.permission', 'group' => 'Role & Permission', 'display_name' => 'Kelola Permission Role'],
                
                // User Management
                ['name' => 'user.view', 'group' => 'Manajemen User', 'display_name' => 'Lihat Daftar User'],
                ['name' => 'user.create', 'group' => 'Manajemen User', 'display_name' => 'Tambah User'],
                ['name' => 'user.edit', 'group' => 'Manajemen User', 'display_name' => 'Edit User'],
                ['name' => 'user.delete', 'group' => 'Manajemen User', 'display_name' => 'Hapus User'],
                
                // Master Data
                ['name' => 'kategori_asset.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Kategori Asset'],
                ['name' => 'status_hukum.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Status Hukum'],
                ['name' => 'unit_kerja.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Unit Kerja'],
                ['name' => 'jabatan.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Jabatan'],
            ];

            DB::beginTransaction();
            
            echo "📝 Membuat permissions...\n";
            foreach ($permissions as $permission) {
                Permission::updateOrCreate(
                    [
                        'name' => $permission['name'],
                        'guard_name' => 'web'
                    ],
                    [
                        'group' => $permission['group'],
                        'display_name' => $permission['display_name']
                    ]
                );
            }
            
            echo "✅ " . count($permissions) . " permissions berhasil dibuat!\n\n";

            // Create Roles
            echo "👥 Membuat roles...\n";
            
            $superAdmin = Role::firstOrCreate(
                ['name' => 'Super Admin', 'guard_name' => 'web']
            );
            
            $admin = Role::firstOrCreate(
                ['name' => 'Admin', 'guard_name' => 'web']
            );
            
            $viewer = Role::firstOrCreate(
                ['name' => 'Viewer', 'guard_name' => 'web']
            );
            
            $operator = Role::firstOrCreate(
                ['name' => 'Operator', 'guard_name' => 'web']
            );

            // Assign permissions to roles
            echo "🔗 Assign permissions ke roles...\n";
            
            $allPermissions = Permission::all();
            
            // Super Admin - All permissions
            $superAdmin->syncPermissions($allPermissions);
            echo "   ✓ Super Admin: " . $superAdmin->permissions->count() . " permissions\n";
            
            // Admin - All permissions
            $admin->syncPermissions($allPermissions);
            echo "   ✓ Admin: " . $admin->permissions->count() . " permissions\n";
            
            // Viewer - Read only
            $viewerPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'asset.view',
                'asset.peta',
                'asset.rekapitulasi',
                'asset.column.kode',
                'asset.column.kategori_tanah',
                'asset.column.unit_kerja',
                'asset.column.kabupaten',
                'asset.column.kecamatan',
            ])->get();
            $viewer->syncPermissions($viewerPermissions);
            echo "   ✓ Viewer: " . $viewer->permissions->count() . " permissions\n";
            
            // Operator - Create & Edit
            $operatorPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'asset.view',
                'asset.create',
                'asset.edit',
                'asset.peta',
                'asset.dokumen',
                'asset.column.kode',
                'asset.column.kategori_tanah',
                'asset.column.unit_kerja',
                'asset.column.status_hukum',
                'asset.column.asal',
                'asset.column.kabupaten',
                'asset.column.kecamatan',
                'asset.column.sertifikat',
                'asset.column.aksi',
            ])->get();
            $operator->syncPermissions($operatorPermissions);
            echo "   ✓ Operator: " . $operator->permissions->count() . " permissions\n";

            DB::commit();
            
            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            echo "\n";
            echo "==========================================\n";
            echo "✅ SEEDER BERHASIL!\n";
            echo "==========================================\n";
            echo "📊 Total Permissions: " . Permission::count() . "\n";
            echo "👥 Total Roles: " . Role::count() . "\n";
            echo "==========================================\n\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ ERROR: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
            throw $e;
        }
    }
}