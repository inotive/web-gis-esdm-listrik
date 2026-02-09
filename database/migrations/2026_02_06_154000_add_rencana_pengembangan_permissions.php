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
            ['name' => 'rencana_pengembangan.view', 'display_name' => 'Lihat Data Rencana Pengembangan'],
            ['name' => 'rencana_pengembangan.create', 'display_name' => 'Tambah Data Rencana Pengembangan'],
            ['name' => 'rencana_pengembangan.edit', 'display_name' => 'Edit Data Rencana Pengembangan'],
            ['name' => 'rencana_pengembangan.delete', 'display_name' => 'Hapus Data Rencana Pengembangan'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm['name'],
                'guard_name' => 'web',
                'group' => 'Rencana Pengembangan',
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
            'rencana_pengembangan.view',
            'rencana_pengembangan.create',
            'rencana_pengembangan.edit',
            'rencana_pengembangan.delete',
        ];

        foreach ($permissions as $name) {
            $permission = Permission::where('name', $name)->first();
            if ($permission) {
                $permission->delete();
            }
        }
    }
};
