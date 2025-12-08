<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use Illuminate\Support\Facades\DB;

echo "=== RESTORE ADMIN PERMISSIONS ===\n\n";

// Get CEO permissions (full access)
$ceoRole = Role::where('name', 'ceo')->first();
$adminRole = Role::where('name', 'admin')->first();
$managerRole = Role::where('name', 'manager')->first();

if (!$ceoRole || !$adminRole) {
    echo " CEO or Admin role not found!\n";
    exit(1);
}

echo "CEO Role ID: {$ceoRole->id}\n";
echo "Admin Role ID: {$adminRole->id}\n";
echo "Manager Role ID: {$managerRole->id}\n\n";

// Get all CEO permissions
$ceoPermissions = DB::table('permissions')
    ->where('role_id', $ceoRole->id)
    ->where('allow', 1)
    ->get();

echo "CEO has {$ceoPermissions->count()} permissions\n\n";

// Copy to Admin role
echo "Copying CEO permissions to Admin...\n";
$copied = 0;
foreach ($ceoPermissions as $perm) {
    $exists = DB::table('permissions')
        ->where('role_id', $adminRole->id)
        ->where('section_id', $perm->section_id)
        ->exists();
    
    if (!$exists) {
        DB::table('permissions')->insert([
            'role_id' => $adminRole->id,
            'section_id' => $perm->section_id,
            'allow' => 1,
        ]);
        $copied++;
    }
}

echo " Copied {$copied} permissions to Admin\n\n";

// Copy to Manager role
echo "Copying CEO permissions to Manager...\n";
$copiedManager = 0;
foreach ($ceoPermissions as $perm) {
    $exists = DB::table('permissions')
        ->where('role_id', $managerRole->id)
        ->where('section_id', $perm->section_id)
        ->exists();
    
    if (!$exists) {
        DB::table('permissions')->insert([
            'role_id' => $managerRole->id,
            'section_id' => $perm->section_id,
            'allow' => 1,
        ]);
        $copiedManager++;
    }
}

echo " Copied {$copiedManager} permissions to Manager\n\n";

// Verify
$adminPerms = DB::table('permissions')->where('role_id', $adminRole->id)->where('allow', 1)->count();
$managerPerms = DB::table('permissions')->where('role_id', $managerRole->id)->where('allow', 1)->count();

echo "=== FINAL COUNT ===\n";
echo "CEO: {$ceoPermissions->count()} permissions\n";
echo "Admin: {$adminPerms} permissions\n";
echo "Manager: {$managerPerms} permissions\n\n";

echo "DONE! Refresh browser (Ctrl+Shift+R) to see all menus.\n";
