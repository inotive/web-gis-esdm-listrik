<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

// Ensure we have a user to test with (Superadmin)
$user = User::role('superadmin')->first();
if (!$user) {
    die("No superadmin user found to test with.\n");
}

$role = Role::where('name', 'superadmin')->first();

echo "Testing Permission Enforcement for User: {$user->email} (Role: superadmin)\n";
echo str_repeat("-", 60) . "\n";
echo sprintf("%-30s | %-30s | %-10s\n", "Feature/Route", "Permission Tested", "Result");
echo str_repeat("-", 60) . "\n";

$tests = [
    // Control Test (Should Pass if logic exists)
    ['name' => 'User Management', 'route' => 'admin.hak-akses.user.index', 'perm' => 'user.view'],
    
    // Modules to Test
    ['name' => 'Data Desa', 'route' => 'admin.desa.index', 'perm' => 'desa.view'],
    ['name' => 'Data Perusahaan', 'route' => 'admin.perusahaan.index', 'perm' => 'perusahaan.view'],
    // Infrastructure - Ignored for now
    // ['name' => 'Data Gardu', 'route' => 'admin.gardu.index', 'perm' => 'infrastruktur.gardu.view'],
    // ['name' => 'Data Jaringan', 'route' => 'admin.infrastruktur.index', 'perm' => 'infrastruktur.jaringan.view'], 
    // ['name' => 'Data Jalan', 'route' => 'admin.jalan.index', 'perm' => 'jalan.view'],
    ['name' => 'Perizinan', 'route' => 'admin.perizinan.index', 'perm' => 'perizinan.view'],
    ['name' => 'Permohonan', 'route' => 'admin.permohonan.index', 'perm' => 'permohonan.view'],
    ['name' => 'Dokumen', 'route' => 'admin.dokumen.index', 'perm' => 'dokumen.view'],
    ['name' => 'Kategori Permohonan', 'route' => 'admin.kategori-permohonan.index', 'perm' => 'kategori_permohonan.view'],
    ['name' => 'Data Inspeksi', 'route' => 'admin.inspeksi.index', 'perm' => 'inspeksi.view'],
    ['name' => 'Rekap Data', 'route' => 'admin.rekap-data.index', 'perm' => 'rekap.view'],
    ['name' => 'Rencana Pengembangan', 'route' => 'admin.rencana-pengembangan.index', 'perm' => 'rencana_pengembangan.view'],
];

// Helper to simulate request
function checkAccess($route, $user) {
    try {
        // Authenticate as the user
        Auth::login($user);
        
        // Create a fake request
        $request = Illuminate\Http\Request::create(route($route), 'GET');
        
        // Dispatch the request through the kernel to trigger middleware
        $response = app()->handle($request);
        
        return $response->getStatusCode();
    } catch (\Exception $e) {
        // If route doesn't exist or other error
        return "Error: " . $e->getMessage();
    }
}

foreach ($tests as $test) {
    $permName = $test['perm'];
    $routeName = $test['route'];
    
    // 1. Check if permission exists in Seeder/DB
    if (!Permission::where('name', $permName)->exists()) {
         echo sprintf("%-30s | %-30s | %-10s\n", $test['name'], $permName, "MISSING DB");
         continue;
    }

    // 2. Revoke Permission temporarily
    $role->revokePermissionTo($permName);
    
    // Clear permission cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
    // 3. Test Access
    $statusWithoutPerm = checkAccess($routeName, $user);
    
    // 4. Restore Permission
    $role->givePermissionTo($permName);
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // 5. Evaluate
    // If status is 200, it means removing permission did NOTHING -> Logic Missing
    // If status is 403, it means removing permission BLOCKED access -> Logic Exists
    $result = ($statusWithoutPerm == 200) ? "NO LOGIC (Cosmetic)" : "SECURE";
    
    if ($statusWithoutPerm != 200 && $statusWithoutPerm != 403) {
        $result = "HTTP $statusWithoutPerm"; // Unexpected error
    }

    echo sprintf("%-30s | %-30s | %-10s\n", $test['name'], $permName, $result);
}

