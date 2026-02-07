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

                // Role & Permission Management
                ['name' => 'role.view', 'group' => 'Role & Permission', 'display_name' => 'Lihat Daftar Role'],
                ['name' => 'role.create', 'group' => 'Role & Permission', 'display_name' => 'Tambah Role'],
                ['name' => 'role.edit', 'group' => 'Role & Permission', 'display_name' => 'Edit Role'],
                ['name' => 'role.delete', 'group' => 'Role & Permission', 'display_name' => 'Hapus Role'],
                ['name' => 'role.permission', 'group' => 'Role & Permission', 'display_name' => 'Kelola Permission Role'],

                // User Management
                ['name' => 'user.view', 'group' => 'Manajemen User', 'display_name' => 'Lihat Daftar User'],
                ['name' => 'user.view', 'group' => 'Manajemen User', 'display_name' => 'Lihat Daftar User'],

                // Data Desa
                ['name' => 'desa.view', 'group' => 'Data Desa', 'display_name' => 'Lihat Data Desa'],
                ['name' => 'desa.create', 'group' => 'Data Desa', 'display_name' => 'Tambah Data Desa'],
                ['name' => 'desa.edit', 'group' => 'Data Desa', 'display_name' => 'Edit Data Desa'],
                ['name' => 'desa.delete', 'group' => 'Data Desa', 'display_name' => 'Hapus Data Desa'],

                // Data Perusahaan
                ['name' => 'perusahaan.view', 'group' => 'Data Perusahaan', 'display_name' => 'Lihat Data Perusahaan'],
                ['name' => 'perusahaan.create', 'group' => 'Data Perusahaan', 'display_name' => 'Tambah Perusahaan'],
                ['name' => 'perusahaan.edit', 'group' => 'Data Perusahaan', 'display_name' => 'Edit Perusahaan'],
                ['name' => 'perusahaan.delete', 'group' => 'Data Perusahaan', 'display_name' => 'Hapus Perusahaan'],
                ['name' => 'perusahaan.show', 'group' => 'Data Perusahaan', 'display_name' => 'Detail Perusahaan'],

                // Data Infrastruktur
                ['name' => 'infrastruktur.view', 'group' => 'Data Infrastruktur', 'display_name' => 'Lihat Data Infrastruktur'],
                ['name' => 'infrastruktur.gardu.view', 'group' => 'Data Infrastruktur', 'display_name' => 'Lihat Data Gardu'],
                ['name' => 'infrastruktur.gardu.create', 'group' => 'Data Infrastruktur', 'display_name' => 'Tambah Gardu'],
                ['name' => 'infrastruktur.gardu.edit', 'group' => 'Data Infrastruktur', 'display_name' => 'Edit Gardu'],
                ['name' => 'infrastruktur.gardu.delete', 'group' => 'Data Infrastruktur', 'display_name' => 'Hapus Gardu'],
                ['name' => 'infrastruktur.jaringan.view', 'group' => 'Data Infrastruktur', 'display_name' => 'Lihat Data Jaringan'],
                ['name' => 'infrastruktur.jaringan.create', 'group' => 'Data Infrastruktur', 'display_name' => 'Tambah Jaringan'],
                ['name' => 'infrastruktur.jaringan.edit', 'group' => 'Data Infrastruktur', 'display_name' => 'Edit Jaringan'],
                ['name' => 'infrastruktur.jaringan.delete', 'group' => 'Data Infrastruktur', 'display_name' => 'Hapus Jaringan'],
                ['name' => 'infrastruktur.pembangkit.view', 'group' => 'Data Infrastruktur', 'display_name' => 'Lihat Data Pembangkit'],
                ['name' => 'infrastruktur.pembangkit.create', 'group' => 'Data Infrastruktur', 'display_name' => 'Tambah Pembangkit'],
                ['name' => 'infrastruktur.pembangkit.edit', 'group' => 'Data Infrastruktur', 'display_name' => 'Edit Pembangkit'],
                ['name' => 'infrastruktur.pembangkit.delete', 'group' => 'Data Infrastruktur', 'display_name' => 'Hapus Pembangkit'],

                // Data Jalan & Aksesibilitas
                ['name' => 'jalan.view', 'group' => 'Data Jalan', 'display_name' => 'Lihat Data Jalan'],
                ['name' => 'jalan.create', 'group' => 'Data Jalan', 'display_name' => 'Tambah Data Jalan'],
                ['name' => 'jalan.edit', 'group' => 'Data Jalan', 'display_name' => 'Edit Data Jalan'],
                ['name' => 'jalan.delete', 'group' => 'Data Jalan', 'display_name' => 'Hapus Data Jalan'],

                // Perizinan dan Permohonan
                ['name' => 'perizinan.view', 'group' => 'Perizinan', 'display_name' => 'Lihat Data Perizinan'],
                ['name' => 'perizinan.create', 'group' => 'Perizinan', 'display_name' => 'Tambah Perizinan'],
                ['name' => 'perizinan.edit', 'group' => 'Perizinan', 'display_name' => 'Edit Perizinan'],
                ['name' => 'perizinan.delete', 'group' => 'Perizinan', 'display_name' => 'Hapus Perizinan'],
                ['name' => 'permohonan.view', 'group' => 'Perizinan', 'display_name' => 'Lihat Data Permohonan'],
                ['name' => 'permohonan.approve', 'group' => 'Perizinan', 'display_name' => 'Approve Permohonan'],
                ['name' => 'permohonan.edit', 'group' => 'Perizinan', 'display_name' => 'Edit Permohonan'],
                ['name' => 'permohonan.delete', 'group' => 'Perizinan', 'display_name' => 'Hapus Permohonan'],
                ['name' => 'permohonan.process', 'group' => 'Perizinan', 'display_name' => 'Proses Permohonan'],

                // Dokumen
                ['name' => 'dokumen.view', 'group' => 'Dokumen', 'display_name' => 'Lihat Dokumen'],
                ['name' => 'dokumen.create', 'group' => 'Dokumen', 'display_name' => 'Upload Dokumen'],
                ['name' => 'dokumen.edit', 'group' => 'Dokumen', 'display_name' => 'Edit Dokumen'],
                ['name' => 'dokumen.delete', 'group' => 'Dokumen', 'display_name' => 'Hapus Dokumen'],

                // Kategori Permohonan
                ['name' => 'kategori_permohonan.view', 'group' => 'Kategori Permohonan', 'display_name' => 'Lihat Kategori Permohonan'],
                ['name' => 'kategori_permohonan.create', 'group' => 'Kategori Permohonan', 'display_name' => 'Tambah Kategori'],
                ['name' => 'kategori_permohonan.edit', 'group' => 'Kategori Permohonan', 'display_name' => 'Edit Kategori'],
                ['name' => 'kategori_permohonan.delete', 'group' => 'Kategori Permohonan', 'display_name' => 'Hapus Kategori'],

                // Manajemen Pengguna (Perusahaan)
                ['name' => 'pengguna.create', 'group' => 'Manajemen Pengguna', 'display_name' => 'Tambah Pengguna'],
                ['name' => 'pengguna.edit', 'group' => 'Manajemen Pengguna', 'display_name' => 'Edit Pengguna'],
                ['name' => 'pengguna.delete', 'group' => 'Manajemen Pengguna', 'display_name' => 'Hapus Pengguna'],
                ['name' => 'pengguna.approve', 'group' => 'Manajemen Pengguna', 'display_name' => 'Approve Pengguna'],

                // Rekap Data
                ['name' => 'rekap.view', 'group' => 'Rekap Data', 'display_name' => 'Lihat Rekap Data'],
                ['name' => 'rekap.elektrifikasi', 'group' => 'Rekap Data', 'display_name' => 'Rekap Elektrifikasi'],
                ['name' => 'rekap.infrastruktur', 'group' => 'Rekap Data', 'display_name' => 'Rekap Infrastruktur'],
                ['name' => 'rekap.export', 'group' => 'Rekap Data', 'display_name' => 'Export Rekap'],

                // Master Data
                ['name' => 'kategori_asset.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Kategori Asset'],
                ['name' => 'status_hukum.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Status Hukum'],
                ['name' => 'unit_kerja.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Unit Kerja'],
                ['name' => 'jabatan.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Jabatan'],
                ['name' => 'wilayah.manage', 'group' => 'Master Data', 'display_name' => 'Kelola Data Wilayah'],

                // Data Inspeksi
                ['name' => 'inspeksi.view', 'group' => 'Data Inspeksi', 'display_name' => 'Lihat Data Inspeksi'],
                ['name' => 'inspeksi.create', 'group' => 'Data Inspeksi', 'display_name' => 'Tambahkan Data Inspeksi'],
                ['name' => 'inspeksi.edit', 'group' => 'Data Inspeksi', 'display_name' => 'Edit Data Inspeksi'],
                ['name' => 'inspeksi.delete', 'group' => 'Data Inspeksi', 'display_name' => 'Hapus Data Inspeksi'],

                // Rencana Pengembangan Bantuan
                ['name' => 'rencana_pengembangan.view', 'group' => 'Rencana Pengembangan', 'display_name' => 'Lihat Data Rencana Pengembangan'],
                ['name' => 'rencana_pengembangan.create', 'group' => 'Rencana Pengembangan', 'display_name' => 'Tambah Data Rencana Pengembangan'],
                ['name' => 'rencana_pengembangan.edit', 'group' => 'Rencana Pengembangan', 'display_name' => 'Edit Data Rencana Pengembangan'],
                ['name' => 'rencana_pengembangan.delete', 'group' => 'Rencana Pengembangan', 'display_name' => 'Hapus Data Rencana Pengembangan'],
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
                ['name' => 'superadmin', 'guard_name' => 'web']
            );

            $admin = Role::firstOrCreate(
                ['name' => 'admin', 'guard_name' => 'web']
            );

            $viewer = Role::firstOrCreate(
                ['name' => 'konsultan', 'guard_name' => 'web']
            );

            $operator = Role::firstOrCreate(
                ['name' => 'ppk', 'guard_name' => 'web']
            );

            // Assign permissions to roles
            echo "🔗 Assign permissions ke roles...\n";

            $allPermissions = Permission::all();

            // Super Admin - All permissions
            $superAdmin->syncPermissions($allPermissions);
            echo "   ✓ superadmin: " . $superAdmin->permissions->count() . " permissions (ALL)\n";

            // Admin - All permissions except role management
            $adminPermissions = Permission::where('group', '!=', 'Role & Permission')->get();
            $admin->syncPermissions($adminPermissions);
            echo "   ✓ admin: " . $admin->permissions->count() . " permissions\n";

            // Viewer - Read only permissions
            $viewerPermissions = Permission::where('name', 'like', '%.view')
                ->orWhere('name', 'like', '%.show')
                ->orWhere('name', 'dashboard.view')
                ->get();
            $viewer->syncPermissions($viewerPermissions);
            echo "   ✓ konsultan: " . $viewer->permissions->count() . " permissions (Read Only)\n";

            // Operator - Create, Edit, View (no delete, no approve)
            $operatorPermissions = Permission::where(function ($query) {
                $query->where('name', 'like', '%.view')
                    ->orWhere('name', 'like', '%.show')
                    ->orWhere('name', 'like', '%.create')
                    ->orWhere('name', 'like', '%.edit')
                    ->orWhere('name', 'permohonan.process')
                    ->orWhere('name', 'permohonan.approve')
                    ->orWhere('name', 'dashboard.view');
            })
                ->where('group', '!=', 'Role & Permission')
                ->where('group', '!=', 'Manajemen User')
                ->get();
            $operator->syncPermissions($operatorPermissions);
            echo "   ✓ ppk: " . $operator->permissions->count() . " permissions (CRUD except Delete)\n";

            // Desa & Perusahaan roles
            $desa = Role::firstOrCreate(['name' => 'desa', 'guard_name' => 'web']);
            $perusahaan = Role::firstOrCreate(['name' => 'perusahaan', 'guard_name' => 'web']);

            $frontendPermissions = Permission::where(function ($q) {
                $q->where('name', 'permohonan.view')
                    ->orWhere('name', 'permohonan.edit')
                    ->orWhere('name', 'dokumen.view');
            })->get();

            $desa->syncPermissions($frontendPermissions);
            $perusahaan->syncPermissions($frontendPermissions);
            echo "   ✓ desa: " . $desa->permissions->count() . " permissions\n";
            echo "   ✓ perusahaan: " . $perusahaan->permissions->count() . " permissions\n";

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
