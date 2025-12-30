<?php

require __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Role;
use App\Models\Section;
use App\Models\Permission;

echo "=== GRANT MANAGER PERMISSION TO VIEW STUDENTS LIST ===\n\n";

// Step 1: Get Manager Role
$managerRole = Role::where('name', 'manager')->first();

if (!$managerRole) {
    echo "❌ ERROR: Manager role not found!\n";
    exit(1);
}

echo "✅ Found Manager Role:\n";
echo "   - ID: {$managerRole->id}\n";
echo "   - Name: {$managerRole->name}\n\n";

// Step 2: Get admin_users_list Section
$usersListSection = Section::where('name', 'admin_users_list')->first();

if (!$usersListSection) {
    echo "❌ ERROR: Section 'admin_users_list' not found!\n";
    exit(1);
}

echo "✅ Found Section:\n";
echo "   - ID: {$usersListSection->id}\n";
echo "   - Name: {$usersListSection->name}\n";
echo "   - Caption: {$usersListSection->caption}\n\n";

// Step 3: Check if permission already exists
$existingPermission = Permission::where('role_id', $managerRole->id)
    ->where('section_id', $usersListSection->id)
    ->first();

if ($existingPermission) {
    if ($existingPermission->allow) {
        echo "ℹ️  Permission already exists and is ALLOWED\n";
        echo "   - No action needed. Manager already has access to Students list.\n";
    } else {
        echo "⚠️  Permission exists but is DENIED. Updating to ALLOW...\n";
        $existingPermission->allow = true;
        $existingPermission->save();
        echo "✅ Permission updated successfully!\n";
    }
} else {
    echo "📝 Creating new permission...\n";
    Permission::create([
        'role_id' => $managerRole->id,
        'section_id' => $usersListSection->id,
        'allow' => true
    ]);
    echo "✅ Permission created successfully!\n";
}

echo "\n=== VERIFICATION ===\n";

// Verify the permission
$verifyPermission = Permission::where('role_id', $managerRole->id)
    ->where('section_id', $usersListSection->id)
    ->first();

if ($verifyPermission && $verifyPermission->allow) {
    echo "✅ VERIFIED: Manager role now has 'admin_users_list' permission\n";
    echo "\n📋 Next Steps:\n";
    echo "   1. Clear Laravel cache: php artisan cache:clear\n";
    echo "   2. Log in as Manager and check sidebar for 'Students' menu\n";
    echo "   3. Navigate to /admin/students to view the list\n";
} else {
    echo "❌ VERIFICATION FAILED: Permission not set correctly\n";
    exit(1);
}

echo "\n✅ DONE!\n";
