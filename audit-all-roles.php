<?php
/**
 * Comprehensive Role Permissions Audit
 * Tests all roles and identifies features that may not align with their service scope
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\User;
use App\Models\Role;
use App\Mixins\Panel\SidebarItems;

echo "=== COMPREHENSIVE ROLE PERMISSIONS AUDIT ===\n\n";

// Define all roles to test
$rolesToTest = [
    'user' => 'Lead/Prospect',
    'student' => 'Enrolled Student',
    'teacher' => 'Instructor',
    'admin' => 'Organization Admin',
    'manager' => 'Manager',
    'ceo' => 'CEO/Owner'
];

$auditResults = [];

foreach ($rolesToTest as $roleName => $roleDescription) {
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "🔍 ROLE: " . strtoupper($roleName) . " ($roleDescription)\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";
    
    // Find a user with this role
    $testUser = User::where('role_name', $roleName)->first();
    
    if (!$testUser) {
        echo "⚠️  No user found with role '$roleName', creating test user...\n";
        
        // Get role ID
        $roleObj = Role::where('name', $roleName)->first();
        if (!$roleObj) {
            echo "❌ Role '$roleName' not found in database!\n\n";
            continue;
        }
        
        // Create test user
        try {
            $testUser = new User();
            $testUser->full_name = "Test " . ucfirst($roleName);
            $testUser->email = "test_{$roleName}_" . time() . "@example.com";
            $testUser->role_id = $roleObj->id;
            $testUser->role_name = $roleName;
            $testUser->password = bcrypt('password');
            $testUser->status = 'active';
            $testUser->created_at = time();
            $testUser->save();
            echo "✅ Test user created\n";
        } catch (\Exception $e) {
            echo "❌ Failed to create test user: " . $e->getMessage() . "\n\n";
            continue;
        }
    }
    
    echo "👤 Testing with: {$testUser->full_name} (ID: {$testUser->id})\n\n";
    
    // Login as this user temporarily
    auth()->login($testUser);
    
    try {
        $items = SidebarItems::getItems();
        
        // Analyze each section
        $analysis = [];
        
        foreach ($items as $sectionName => $sectionItems) {
            if (!is_array($sectionItems) || count($sectionItems) === 0) {
                continue;
            }
            
            foreach ($sectionItems as $itemName => $itemData) {
                $subItems = [];
                if (isset($itemData['items']) && is_array($itemData['items'])) {
                    foreach ($itemData['items'] as $subItem) {
                        if (isset($subItem['text'])) {
                            $subItems[] = $subItem['text'];
                        }
                    }
                }
                
                $analysis[$sectionName][$itemName] = [
                    'text' => $itemData['text'] ?? $itemName,
                    'sub_items' => $subItems
                ];
            }
        }
        
        // Store results
        $auditResults[$roleName] = $analysis;
        
        // Display results
        echo "📋 Available Features:\n";
        foreach ($analysis as $section => $items) {
            echo "\n  ▶ " . strtoupper(str_replace('_', ' ', $section)) . "\n";
            foreach ($items as $itemKey => $itemInfo) {
                echo "    • {$itemInfo['text']}";
                if (!empty($itemInfo['sub_items'])) {
                    echo " (" . count($itemInfo['sub_items']) . " sub-items)";
                }
                echo "\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Logout
    auth()->logout();
    
    echo "\n\n";
}

// Generate analysis report
echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "📊 ANALYSIS & RECOMMENDATIONS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Analyze USER role
if (isset($auditResults['user'])) {
    echo "🔴 USER ROLE (Lead) - Issues:\n";
    $issues = [];
    
    if (isset($auditResults['user']['evaluation']) && !empty($auditResults['user']['evaluation'])) {
        $issues[] = "❌ Has evaluation features (should be EMPTY for leads)";
    }
    
    if (isset($auditResults['user']['education']['webinars'])) {
        $webinarItems = $auditResults['user']['education']['webinars']['sub_items'];
        if (in_array('My purchases', $webinarItems)) {
            $issues[] = "❌ Can see 'My purchases' in webinars";
        }
        if (in_array('Course Notes', $webinarItems)) {
            $issues[] = "❌ Can see 'Course Notes'";
        }
    }
    
    if (isset($auditResults['user']['financial_&_marketing']['store'])) {
        $storeItems = $auditResults['user']['financial_&_marketing']['store']['sub_items'];
        if (in_array('My purchases', $storeItems)) {
            $issues[] = "❌ Can see 'My purchases' in store";
        }
    }
    
    if (isset($auditResults['user']['financial_&_marketing']['financial'])) {
        $financialItems = $auditResults['user']['financial_&_marketing']['financial']['sub_items'];
        if (in_array('Payout', $financialItems)) {
            $issues[] = "❌ Can see 'Payout' (leads have no income)";
        }
    }
    
    if (empty($issues)) {
        echo "  ✅ No issues found\n";
    } else {
        foreach ($issues as $issue) {
            echo "  $issue\n";
        }
    }
    echo "\n";
}

// Analyze STUDENT role
if (isset($auditResults['student'])) {
    echo "🟢 STUDENT ROLE - Status:\n";
    $checks = [];
    
    if (isset($auditResults['student']['evaluation']) && !empty($auditResults['student']['evaluation'])) {
        $checks[] = "✅ Has evaluation features (correct)";
    } else {
        $checks[] = "❌ Missing evaluation features!";
    }
    
    if (isset($auditResults['student']['education']['webinars'])) {
        $webinarItems = $auditResults['student']['education']['webinars']['sub_items'];
        if (in_array('My purchases', $webinarItems)) {
            $checks[] = "✅ Can see 'My purchases' (correct)";
        } else {
            $checks[] = "❌ Cannot see 'My purchases'!";
        }
    }
    
    foreach ($checks as $check) {
        echo "  $check\n";
    }
    echo "\n";
}

// Analyze TEACHER role
if (isset($auditResults['teacher'])) {
    echo "🔵 TEACHER ROLE - Status:\n";
    $checks = [];
    
    if (isset($auditResults['teacher']['education']['webinars'])) {
        $webinarItems = $auditResults['teacher']['education']['webinars']['sub_items'];
        if (in_array('New', $webinarItems)) {
            $checks[] = "✅ Can create courses (correct)";
        }
    }
    
    if (isset($auditResults['teacher']['members'])) {
        if (isset($auditResults['teacher']['members']['teachers'])) {
            $checks[] = "❌ Can manage teachers (should be admin-only)";
        }
        if (isset($auditResults['teacher']['members']['students'])) {
            $checks[] = "✅ Can manage students (correct)";
        }
    }
    
    foreach ($checks as $check) {
        echo "  $check\n";
    }
    echo "\n";
}

// Analyze ADMIN role
if (isset($auditResults['admin'])) {
    echo "🟡 ADMIN ROLE - Status:\n";
    $checks = [];
    
    if (isset($auditResults['admin']['members']['teachers'])) {
        $checks[] = "✅ Can manage teachers (correct)";
    } else {
        $checks[] = "❌ Cannot manage teachers!";
    }
    
    if (isset($auditResults['admin']['members']['students'])) {
        $checks[] = "✅ Can manage students (correct)";
    } else {
        $checks[] = "❌ Cannot manage students!";
    }
    
    // Should have all teacher features
    if (isset($auditResults['admin']['education']['webinars'])) {
        $webinarItems = $auditResults['admin']['education']['webinars']['sub_items'];
        if (in_array('New', $webinarItems)) {
            $checks[] = "✅ Can create courses like teacher (correct)";
        }
    }
    
    foreach ($checks as $check) {
        echo "  $check\n";
    }
    echo "\n";
}

echo "✨ Audit complete!\n\n";
