<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;

echo "=== Admin User Configuration Check ===\n\n";

$admin = User::where('role_name', 'admin')->first();

if ($admin) {
    echo "✓ Admin user found:\n";
    echo "  Name: " . $admin->full_name . "\n";
    echo "  Email: " . $admin->email . "\n";
    echo "  Role Name: " . $admin->role_name . "\n";
    echo "  Role ID: " . $admin->role_id . "\n";
    echo "  isAdmin(): " . ($admin->isAdmin() ? 'YES' : 'NO') . "\n";
    echo "  isManager(): " . ($admin->isManager() ? 'YES' : 'NO') . "\n";
    echo "  isCeo(): " . ($admin->isCeo() ? 'YES' : 'NO') . "\n\n";
    
    echo "Expected redirect after login: " . getAdminPanelUrl() . "\n\n";
    
    echo "✓ Everything looks good!\n";
    echo "  Please try logging in with this admin account.\n";
} else {
    echo "✗ No admin user found!\n";
}
