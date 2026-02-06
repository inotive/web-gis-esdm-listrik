<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ['name' => 'inspeksi.view', 'display_name' => 'Lihat Data Inspeksi'],
            ['name' => 'inspeksi.create', 'display_name' => 'Tambahkan Data Inspeksi'],
            ['name' => 'inspeksi.edit', 'display_name' => 'Edit Data Inspeksi'],
            ['name' => 'inspeksi.delete', 'display_name' => 'Hapus Data Inspeksi'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm['name'],
                'guard_name' => 'web',
                'group' => 'Data Inspeksi',
                'display_name' => $perm['display_name']
            ]);
        }
        
        // Assign to Super Admin and Admin by default
        $roles = ['superadmin', 'admin'];
        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                // Assign all new permissions
                foreach ($permissions as $perm) {
                    $role->givePermissionTo($perm['name']);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'inspeksi.view',
            'inspeksi.create',
            'inspeksi.edit',
            'inspeksi.delete',
        ];

        foreach ($permissions as $name) {
            $permission = Permission::where('name', $name)->first();
            if ($permission) {
                $permission->delete();
            }
        }
    }
};
