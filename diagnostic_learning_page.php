<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== LEARNING PAGE DIAGNOSTIC ===\n\n";

// Get authenticated user
$userId = 3; // Change this to your test user ID
echo "Testing with User ID: {$userId}\n";

try {
    $user = DB::table('users')->where('id', $userId)->first();
    
    if (!$user) {
        echo "✗ User not found!\n";
        exit(1);
    }
    
    echo "✓ User found: {$user->full_name} (Role: " . ($user->role ? $user->role->name : 'No Role') . ")\n\n";
    
    // Check permission
    echo "1. Checking Learning Page Permission:\n";
    $hasPermission = $user->hasPermission('panel_webinars_learning_page');
    echo "   panel_webinars_learning_page: " . ($hasPermission ? '✓ YES' : '✗ NO') . "\n";
    
    if (!$hasPermission) {
        echo "\n   User permissions:\n";
        $permissions = $user->role ? $user->role->permissions()->pluck('name')->toArray() : [];
        foreach (array_slice($permissions, 0, 10) as $perm) {
            echo "   - {$perm}\n";
        }
    }
    
    // Check if user has purchased a course
    echo "\n2. Checking Course Purchases:\n";
    $sales = \App\Models\Sale::where('buyer_id', $user->id)
        ->where('type', 'webinar')
        ->get();
    
    echo "   Total purchases: " . $sales->count() . "\n";
    
    if ($sales->count() > 0) {
        echo "\n   Purchased courses:\n";
        foreach ($sales->take(3) as $sale) {
            $webinar = \App\Models\Webinar::find($sale->webinar_id);
            if ($webinar) {
                echo "   - {$webinar->title} (Slug: {$webinar->slug})\n";
                echo "     Learning URL: /panel/webinars/learning/{$webinar->slug}\n";
            }
        }
    }
    
    // Check view files
    echo "\n3. Checking View Files:\n";
    $viewPath = resource_path('views/design_1/web/courses/learning_page');
    echo "   Path: {$viewPath}\n";
    echo "   Exists: " . (file_exists($viewPath) ? '✓ YES' : '✗ NO') . "\n";
    
    // Check storage permissions
    echo "\n4. Storage Permissions:\n";
    $storagePath = storage_path('framework/views');
    echo "   Path: {$storagePath}\n";
    echo "   Writable: " . (is_writable($storagePath) ? '✓ YES' : '✗ NO') . "\n";
    
    // Try to compile a simple view
    echo "\n5. Testing View Compilation:\n";
    try {
        $compiled = view()->exists('design_1.web.courses.learning_page.index');
        echo "   View exists: " . ($compiled ? '✓ YES' : '✗ NO') . "\n";
    } catch (Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
