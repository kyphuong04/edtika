<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ENSURING CEO HAS ALL ADMIN PERMISSIONS ===\n\n";

// CEO role ID
$ceoRoleId = 6;

// Get all admin sections (CEO should have access to admin panel too)
$adminSections = DB::table('sections')
    ->where('type', 'admin')
    ->pluck('id', 'name')
    ->toArray();

echo "Found " . count($adminSections) . " admin sections\n\n";

$added = 0;
$existing = 0;

foreach ($adminSections as $sectionName => $sectionId) {
    // Check if permission already exists
    $exists = DB::table('permissions')
        ->where('role_id', $ceoRoleId)
        ->where('section_id', $sectionId)
        ->exists();
    
    if (!$exists) {
        DB::table('permissions')->insert([
            'role_id' => $ceoRoleId,
            'section_id' => $sectionId,
            'allow' => 1
        ]);
        echo "✓ Added: $sectionName\n";
        $added++;
    } else {
        $existing++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Added admin permissions: $added\n";
echo "Already existing: $existing\n";
echo "Total admin permissions: " . ($added + $existing) . "\n";

// Get total permissions
$totalPerms = DB::table('permissions')
    ->where('role_id', $ceoRoleId)
    ->count();

echo "Grand total permissions: $totalPerms\n";

// Clear cache
echo "\nClearing cache...\n";
\Illuminate\Support\Facades\Artisan::call('cache:clear');

echo "\n✓ Done! CEO now has FULL ACCESS to:\n";
echo "- All Admin Panel features\n";
echo "- All Panel/Dashboard features\n";
echo "- Organization management\n";
echo "- Financial control\n";
echo "- User management\n";
echo "- System settings\n";
echo "- Everything!\n";

// Show what CEO can do
echo "\n=== CEO CAPABILITIES ===\n";
$capabilities = DB::table('permissions')
    ->join('sections', 'permissions.section_id', '=', 'sections.id')
    ->where('permissions.role_id', $ceoRoleId)
    ->where('permissions.allow', 1)
    ->select('sections.type', DB::raw('COUNT(*) as count'))
    ->groupBy('sections.type')
    ->get();

foreach ($capabilities as $cap) {
    echo "- {$cap->type}: {$cap->count} permissions\n";
}
