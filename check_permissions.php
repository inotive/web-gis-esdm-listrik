<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$roles = ['superadmin', 'admin', 'ppk', 'konsultan', 'desa', 'perusahaan'];
$permissionsToCheck = [
    'permohonan.approve',
    'permohonan.process',
    'permohonan.create', // Should be GONE
    'perizinan.approve', // Should be GONE
    'dokumen.download', // Should be GONE
];

echo "--- Checking Permission Existence ---\n";
foreach ($permissionsToCheck as $permName) {
    echo "Permission '$permName': " . (Permission::where('name', $permName)->exists() ? 'EXISTS' : 'GONE') . "\n";
}

echo "\n--- Checking Role Permissions ---\n";
foreach ($roles as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if (!$role) {
        echo "Role '$roleName' NOT FOUND\n";
        continue;
    }
    
    echo "Role: $roleName\n";
    foreach ($permissionsToCheck as $permName) {
        if (!Permission::where('name', $permName)->exists()) continue;
        
        $has = $role->hasPermissionTo($permName) ? 'YES' : 'NO';
        echo "  - Can $permName? $has\n";
    }
    echo "\n";
}
