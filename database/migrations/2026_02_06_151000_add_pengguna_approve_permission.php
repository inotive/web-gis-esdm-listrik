<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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

        // Create permission
        Permission::firstOrCreate([
            'name' => 'pengguna.approve', 
            'guard_name' => 'web',
            'group' => 'Manajemen Pengguna',
            'display_name' => 'Approve Pengguna'
        ]);
        
        // Assign to Super Admin and Admin by default
        $roles = ['superadmin', 'admin'];
        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo('pengguna.approve');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = Permission::where('name', 'pengguna.approve')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};
