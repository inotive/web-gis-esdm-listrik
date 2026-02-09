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

        // Create the permission
        Permission::create([
            'name' => 'permohonan.approve', 
            'guard_name' => 'web',
            'group' => 'Perizinan', 
            'display_name' => 'Approve Permohonan'
        ]);

        // Assign to Admin and Superadmin
        $roles = Role::whereIn('name', ['admin', 'superadmin'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo('permohonan.approve');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = Permission::where('name', 'permohonan.approve')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};
