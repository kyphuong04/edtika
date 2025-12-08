<?php
// Update admin role is_admin to 0

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Updating admin role...\n";

DB::table('roles')->where('id', 4)->update(['is_admin' => 0]);

echo "✅ Updated admin role: is_admin=0\n\n";

echo "Current roles in database:\n";
$roles = DB::table('roles')->orderBy('id')->get();
foreach ($roles as $role) {
    echo "  Role {$role->id}: {$role->name} (is_admin={$role->is_admin})\n";
}

echo "\n✅ Done!\n";
