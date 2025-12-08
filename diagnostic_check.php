<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking sections existence...\n\n";

$testSections = [
    'panel_bundles',
    'panel_quizzes',
    'panel_financial',
    'panel_marketing',
    'panel_others_logout'
];

foreach ($testSections as $sectionName) {
    $section = DB::table('sections')->where('name', $sectionName)->first();
    if ($section) {
        echo "✅ {$sectionName} - ID: {$section->id}\n";
        
        // Check if permission exists for admin role
        $perm = DB::table('permissions')
            ->where('role_id', 5)
            ->where('section_id', $section->id)
            ->first();
            
        if ($perm) {
            echo "   Permission exists: allow = {$perm->allow}\n";
        } else {
            echo "   Permission DOES NOT exist - needs INSERT\n";
        }
    } else {
        echo "❌ {$sectionName} - NOT FOUND IN SECTIONS TABLE\n";
    }
}
