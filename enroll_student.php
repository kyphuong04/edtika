<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ENROLLING STUDENT TO COURSE ===\n\n";

// Configuration
$studentUserId = 3; // Change if needed
$courseSlug = 'Sample-Course';

try {
    // Step 1: Grant permission
    echo "Step 1: Granting permission...\n";
    
    $role = DB::table('roles')->where('name', 'user')->first();
    $section = DB::table('sections')->where('name', 'panel_webinars_learning_page')->first();
    
    if (!$role || !$section) {
        echo "✗ Role or Section not found!\n";
        exit(1);
    }
    
    // Find permission for this section
    $permission = DB::table('permissions')->where('section_id', $section->id)->first();
    
    if (!$permission) {
        echo "✗ Permission not found for section!\n";
        exit(1);
    }
    
    $hasPermission = DB::table('role_permissions')
        ->where('role_id', $role->id)
        ->where('permission_id', $permission->id)
        ->exists();
    
    if (!$hasPermission) {
        DB::table('role_permissions')->insert([
            'role_id' => $role->id,
            'permission_id' => $permission->id
        ]);
        echo "✓ Permission 'panel_webinars_learning_page' granted to 'user' role\n\n";
    } else {
        echo "✓ Already has permission\n\n";
    }
    
    // Step 2: Get course
    echo "Step 2: Finding course...\n";
    
    $course = DB::table('webinars')->where('slug', $courseSlug)->first();
    
    if (!$course) {
        echo "✗ Course not found!\n";
        exit(1);
    }
    
    echo "✓ Found: {$course->title}\n";
    echo "  Price: " . ($course->price ?? 0) . "\n";
    echo "  Status: {$course->status}\n\n";
    
    // Step 3: Check if already enrolled
    echo "Step 3: Checking enrollment...\n";
    
    $alreadyEnrolled = DB::table('sales')
        ->where('buyer_id', $studentUserId)
        ->where('webinar_id', $course->id)
        ->where('type', 'webinar')
        ->exists();
    
    if ($alreadyEnrolled) {
        echo "✓ Student already enrolled in this course\n\n";
    } else {
        // Step 4: Enroll student
        echo "Step 4: Enrolling student...\n";
        
        DB::table('sales')->insert([
            'buyer_id' => $studentUserId,
            'webinar_id' => $course->id,
            'type' => 'webinar',
            'amount' => $course->price ?? 0,
            'total_amount' => $course->price ?? 0,
            'tax' => 0,
            'commission' => 0,
            'discount' => 0,
            'created_at' => now(),
            'payment_method' => ($course->price == 0) ? 'free' : 'direct',
            'access_to_purchased_item' => 1
        ]);
        
        echo "✓ Student enrolled successfully!\n\n";
    }
    
    // Step 5: Verify
    echo "=== VERIFICATION ===\n\n";
    
    $user = DB::table('users')->where('id', $studentUserId)->first();
    echo "Student: {$user->full_name} (ID: {$user->id})\n";
    
    $userRole = DB::table('roles')->where('id', $user->role_id)->first();
    echo "Role: {$userRole->name}\n\n";
    
    $enrollment = DB::table('sales')
        ->join('webinars', 'sales.webinar_id', '=', 'webinars.id')
        ->where('sales.buyer_id', $studentUserId)
        ->where('webinars.slug', $courseSlug)
        ->select('sales.*', 'webinars.title')
        ->first();
    
    if ($enrollment) {
        echo "Enrollment Details:\n";
        echo "  Course: {$enrollment->title}\n";
        echo "  Amount: {$enrollment->amount}\n";
        echo "  Payment Method: {$enrollment->payment_method}\n";
        echo "  Enrolled: {$enrollment->created_at}\n\n";
    }
    
    echo "✓ COMPLETE! Student can now access:\n";
    echo "  http://edtika.local/course/learning/{$courseSlug}\n\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
