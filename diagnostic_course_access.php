<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COURSE ACCESS DIAGNOSTIC ===\n\n";

// Check storage permissions
$storagePath = storage_path('framework/views');
echo "1. Storage Path Permissions:\n";
echo "   Path: {$storagePath}\n";
echo "   Exists: " . (file_exists($storagePath) ? 'YES' : 'NO') . "\n";
echo "   Writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";
echo "   Readable: " . (is_readable($storagePath) ? 'YES' : 'NO') . "\n\n";

// Clear all caches
echo "2. Clearing Caches:\n";
try {
    Artisan::call('cache:clear');
    echo "   ✓ Cache cleared\n";
    
    Artisan::call('view:clear');
    echo "   ✓ View cache cleared\n";
    
    Artisan::call('config:clear');
    echo "   ✓ Config cleared\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Check if we can fetch courses
echo "\n3. Checking Courses:\n";
try {
    $courses = \App\Models\Webinar::where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    echo "   Found " . $courses->count() . " active courses\n";
    
    if ($courses->count() > 0) {
        echo "\n   Sample courses:\n";
        foreach ($courses as $course) {
            echo "   - ID: {$course->id} | Title: {$course->title} | Slug: {$course->slug}\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error fetching courses: " . $e->getMessage() . "\n";
}

// Check routes
echo "\n4. Checking Course Routes:\n";
try {
    $routes = Route::getRoutes();
    $courseRoutes = [];
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'course') !== false || strpos($uri, 'webinar') !== false) {
            $courseRoutes[] = $uri . ' [' . implode(',', $route->methods()) . ']';
        }
    }
    
    if (count($courseRoutes) > 0) {
        echo "   Found " . count($courseRoutes) . " course-related routes:\n";
        foreach (array_slice($courseRoutes, 0, 10) as $route) {
            echo "   - {$route}\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error checking routes: " . $e->getMessage() . "\n";
}

// Check view files
echo "\n5. Checking View Files:\n";
$viewPath = resource_path('views/design_1/web/courses/show');
echo "   View Path: {$viewPath}\n";
echo "   Exists: " . (file_exists($viewPath) ? 'YES' : 'NO') . "\n";

if (file_exists($viewPath)) {
    $files = scandir($viewPath);
    echo "   Files: " . implode(', ', array_diff($files, ['.', '..'])) . "\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
