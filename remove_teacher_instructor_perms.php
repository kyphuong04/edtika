<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== REMOVING TEACHER PERMISSIONS FOR MANAGING TEACHERS ===\n\n";

// Get teacher role
$teacherRole = DB::table('roles')->where('name', 'teacher')->first();

if (!$teacherRole) {
    echo "❌ Teacher role not found!\n";
    exit;
}

echo "Teacher Role: {$teacherRole->name} (ID: {$teacherRole->id})\n\n";

// Permissions to remove
$permsToRemove = [
    'panel_organization_instructors',
    'panel_organization_instructors_lists',
    'panel_organization_instructors_create',
    'panel_organization_instructors_edit',
    'panel_organization_instructors_delete',
];

echo "Removing permissions:\n";
echo str_repeat("=", 80) . "\n";

foreach ($permsToRemove as $permName) {
    $section = DB::table('sections')->where('name', $permName)->first();
    
    if ($section) {
        $existing = DB::table('permissions')
            ->where('role_id', $teacherRole->id)
            ->where('section_id', $section->id)
            ->first();
        
        if ($existing) {
            DB::table('permissions')
                ->where('role_id', $teacherRole->id)
                ->where('section_id', $section->id)
                ->delete();
            echo "✅ REMOVED: {$permName}\n";
        } else {
            echo "ℹ️  Not found: {$permName}\n";
        }
    } else {
        echo "❌ Section not found: {$permName}\n";
    }
}

// Verify
echo "\n" . str_repeat("=", 80) . "\n";
echo "VERIFICATION\n";
echo str_repeat("=", 80) . "\n\n";

$teacherUser = DB::table('users')->where('role_name', 'teacher')->first();
if ($teacherUser) {
    $user = App\User::find($teacherUser->id);
    
    echo "Test User: {$user->full_name}\n\n";
    
    echo "Teachers Management Permissions (should all be NO):\n";
    foreach ($permsToRemove as $perm) {
        $hasPerm = $user->can($perm);
        echo "  " . ($hasPerm ? "❌ YES (still has)" : "✅ NO (removed)") . " {$perm}\n";
    }
    
    echo "\nStudents Management Permissions (should all be YES):\n";
    $studentPerms = [
        'panel_organization_students',
        'panel_organization_students_lists',
        'panel_organization_students_create',
        'panel_organization_students_edit',
        'panel_organization_students_delete',
    ];
    
    foreach ($studentPerms as $perm) {
        $hasPerm = $user->can($perm);
        echo "  " . ($hasPerm ? "✅ YES" : "❌ NO") . " {$perm}\n";
    }
}

echo "\n✅ DONE!\n";

