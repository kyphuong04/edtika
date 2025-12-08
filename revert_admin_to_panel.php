<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Permission;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

echo "=== REVERT ADMIN ROLE - DÙNG PANEL RIÊNG NHƯ ORGANIZATION ===\n\n";

$adminRole = Role::find(4);

if (!$adminRole) {
    echo "❌ Không tìm thấy admin role!\n";
    exit;
}

echo "BƯỚC 1: Đổi is_admin về FALSE\n";
echo str_repeat("-", 80) . "\n";

$adminRole->is_admin = false;
$adminRole->save();

echo "✅ Đã set admin role is_admin = FALSE\n";
echo "   → Admin giờ sẽ truy cập /panel/* thay vì /admin/*\n\n";

echo "BƯỚC 2: Cấp lại permissions phù hợp với panel\n";
echo str_repeat("-", 80) . "\n";

// Xóa permissions cũ
Permission::where('role_id', 4)->delete();
echo "✅ Đã xóa permissions cũ của admin\n";

// Lấy permissions từ teacher role làm base (vì teacher cũng dùng panel)
$teacherPermissions = Permission::where('role_id', 3)
    ->where('allow', 1)
    ->pluck('section_id')
    ->toArray();

// Thêm thêm một số quyền quản lý cho admin
$additionalSections = Section::where(function($query) {
    // Panel sections cho quản lý
    $query->where('name', 'like', 'panel_users%')
          ->orWhere('name', 'like', 'panel_webinars%')
          ->orWhere('name', 'like', 'panel_students%')
          ->orWhere('name', 'like', 'panel_financial%')
          ->orWhere('name', 'like', 'panel_support%')
          ->orWhere('name', 'like', 'panel_comments%');
})->pluck('id')->toArray();

$allPermissions = array_unique(array_merge($teacherPermissions, $additionalSections));

$permissionsAdded = 0;
foreach ($allPermissions as $sectionId) {
    Permission::create([
        'role_id' => 4,
        'section_id' => $sectionId,
        'allow' => true
    ]);
    $permissionsAdded++;
}

echo "✅ Đã thêm {$permissionsAdded} permissions cho admin\n\n";

echo "BƯỚC 3: Kiểm tra kết quả\n";
echo str_repeat("-", 80) . "\n";

$adminRole = Role::find(4);
$adminPerms = Permission::where('role_id', 4)->where('allow', 1)->count();

$roles = [
    ['name' => 'CEO', 'id' => 6],
    ['name' => 'Manager', 'id' => 5],
    ['name' => 'Admin', 'id' => 4],
    ['name' => 'Teacher', 'id' => 3],
    ['name' => 'Student', 'id' => 2],
    ['name' => 'User', 'id' => 1],
];

printf("%-15s %-15s %-15s %-20s %-25s\n", "Role", "is_admin", "Permissions", "Truy cập", "Quản lý");
echo str_repeat("-", 80) . "\n";

foreach ($roles as $roleData) {
    $role = Role::find($roleData['id']);
    if ($role) {
        $permsCount = Permission::where('role_id', $role->id)->where('allow', 1)->count();
        $isAdmin = $role->is_admin ? 'TRUE' : 'FALSE';
        
        $access = '';
        $manage = '';
        
        if ($role->is_admin) {
            $access = '/admin/*';
            $manage = 'Toàn hệ thống';
        } else if ($role->name === 'teacher' || $role->name === 'admin') {
            $access = '/panel/*';
            $manage = 'Courses của mình';
        } else {
            $access = '/panel/*';
            $manage = 'Cá nhân';
        }
        
        printf("%-15s %-15s %-15s %-20s %-25s\n",
            $roleData['name'],
            $isAdmin,
            $permsCount,
            $access,
            $manage
        );
    }
}

echo "\n\n";

echo str_repeat("=", 80) . "\n";
echo "✅ ĐÃ HOÀN THÀNH!\n";
echo str_repeat("=", 80) . "\n\n";

echo "Admin role giờ:\n";
echo "  ✅ is_admin = FALSE\n";
echo "  ✅ Truy cập /panel/* (giống Teacher/Organization cũ)\n";
echo "  ✅ Có {$adminPerms} permissions\n";
echo "  ✅ Quản lý courses và students (tương tự Organization)\n";
echo "  ✅ KHÔNG truy cập /admin/* (dành riêng cho CEO/Manager)\n\n";

echo "Phân biệt:\n";
echo "  🔴 SUPERADMIN (CEO/Manager):\n";
echo "     - is_admin = TRUE\n";
echo "     - Truy cập /admin/*\n";
echo "     - Quản lý TOÀN BỘ hệ thống + Settings + Roles\n\n";
echo "  🟡 ADMIN (giống Organization cũ):\n";
echo "     - is_admin = FALSE\n";
echo "     - Truy cập /panel/*\n";
echo "     - Quản lý courses, students (phạm vi giới hạn)\n\n";
echo "  🟢 TEACHER/STUDENT/USER:\n";
echo "     - is_admin = FALSE\n";
echo "     - Truy cập /panel/*\n";
echo "     - Chỉ quản lý nội dung cá nhân\n\n";

echo "LƯU Ý:\n";
echo "  ⚠️  Admin giờ KHÔNG vào được /admin/* nữa\n";
echo "  ⚠️  Admin chỉ thấy courses/students trong phạm vi được assign\n";
echo "  ✅ Nếu cần admin quản lý TOÀN HỆ THỐNG, cần tạo user với role CEO/Manager\n";
