<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'user.create',
            'user.edit',
            'user.delete',
            'pengguna.view',
            'pengguna.reset_password',
        ];

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $permission->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We can optionally re-create them here if needed, but since they are unused, it might not be strictly necessary for rollback to "restore" unused stuff unless strict state management is required.
        // However, for completeness:
        $permissions = [
            ['name' => 'user.create', 'group' => 'Manajemen User', 'display_name' => 'Tambah User'],
            ['name' => 'user.edit', 'group' => 'Manajemen User', 'display_name' => 'Edit User'],
            ['name' => 'user.delete', 'group' => 'Manajemen User', 'display_name' => 'Hapus User'],
            ['name' => 'pengguna.view', 'group' => 'Manajemen Pengguna', 'display_name' => 'Lihat Pengguna Perusahaan'],
            ['name' => 'pengguna.reset_password', 'group' => 'Manajemen Pengguna', 'display_name' => 'Reset Password Pengguna'],
        ];

        foreach ($permissions as $p) {
            Permission::create(['name' => $p['name'], 'guard_name' => 'web', 'group' => $p['group'], 'display_name' => $p['display_name']]);
        }
    }
};
