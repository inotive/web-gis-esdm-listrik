<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Remove the permission if it exists
        $permission = Permission::where('name', 'perizinan.approve')->first();
        if ($permission) {
            $permission->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create the permission if rolled back
        Permission::create([
            'name' => 'perizinan.approve',
            'guard_name' => 'web',
            'group' => 'Perizinan',
            'display_name' => 'Approve Perizinan'
        ]);
    }
};
