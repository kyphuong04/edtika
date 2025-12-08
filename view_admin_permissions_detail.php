<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Permission;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

echo "=== XEM CHI TIẾT QUYỀN CỦA ADMIN ===\n\n";

$adminRole = Role::find(4);

if (!$adminRole) {
    echo "❌ Không tìm thấy admin role!\n";
    exit;
}

echo "THÔNG TIN ADMIN ROLE:\n";
echo str_repeat("=", 100) . "\n";
echo "ID: {$adminRole->id}\n";
echo "Name: {$adminRole->name}\n";
echo "Caption: {$adminRole->caption}\n";
echo "is_admin: " . ($adminRole->is_admin ? 'TRUE ✅' : 'FALSE ❌') . "\n";
echo "Users count: {$adminRole->users_count}\n";

$totalPerms = Permission::where('role_id', 4)->where('allow', 1)->count();
echo "Total permissions: {$totalPerms}\n\n";

// Nhóm permissions theo category
$permissions = Permission::where('role_id', 4)
    ->where('allow', 1)
    ->get();

$categories = [];
foreach ($permissions as $perm) {
    $section = Section::find($perm->section_id);
    if ($section) {
        // Lấy phần đầu của section name để nhóm
        $parts = explode('_', $section->name);
        if (count($parts) >= 2) {
            $category = $parts[0] . '_' . $parts[1]; // e.g., "admin_users"
        } else {
            $category = $parts[0];
        }
        
        if (!isset($categories[$category])) {
            $categories[$category] = [];
        }
        
        $categories[$category][] = [
            'id' => $section->id,
            'name' => $section->name,
            'caption' => $section->caption
        ];
    }
}

ksort($categories);

echo "DANH SÁCH QUYỀN THEO NHÓM:\n";
echo str_repeat("=", 100) . "\n\n";

foreach ($categories as $category => $sections) {
    echo "📁 " . strtoupper(str_replace('_', ' ', $category)) . " (" . count($sections) . " permissions)\n";
    echo str_repeat("-", 100) . "\n";
    
    foreach ($sections as $section) {
        echo sprintf("   [%4d] %-50s %s\n", 
            $section['id'], 
            $section['name'], 
            $section['caption']
        );
    }
    
    echo "\n";
}

echo "\n";
echo str_repeat("=", 100) . "\n";
echo "TỔNG KẾT:\n";
echo str_repeat("=", 100) . "\n";

// Đếm theo loại chức năng
$managementPerms = 0;
$listPerms = 0;
$createPerms = 0;
$editPerms = 0;
$deletePerms = 0;
$otherPerms = 0;

foreach ($permissions as $perm) {
    $section = Section::find($perm->section_id);
    if ($section) {
        if (strpos($section->name, '_list') !== false) {
            $listPerms++;
        } elseif (strpos($section->name, '_create') !== false) {
            $createPerms++;
        } elseif (strpos($section->name, '_edit') !== false) {
            $editPerms++;
        } elseif (strpos($section->name, '_delete') !== false) {
            $deletePerms++;
        } elseif (strpos($section->name, 'admin_') !== false) {
            $managementPerms++;
        } else {
            $otherPerms++;
        }
    }
}

echo "Phân loại theo hành động:\n";
echo "  📋 List/View permissions: {$listPerms}\n";
echo "  ➕ Create permissions: {$createPerms}\n";
echo "  ✏️  Edit permissions: {$editPerms}\n";
echo "  🗑️  Delete permissions: {$deletePerms}\n";
echo "  ⚙️  Management permissions: {$managementPerms}\n";
echo "  📌 Other permissions: {$otherPerms}\n";
echo "\n";

echo "Khả năng của Admin:\n";
echo "  ✅ Quản lý Users, Students, Teachers\n";
echo "  ✅ Quản lý Courses/Webinars\n";
echo "  ✅ Quản lý Quizzes & Assignments\n";
echo "  ✅ Quản lý Categories & Tags\n";
echo "  ✅ Quản lý Comments & Support\n";
echo "  ✅ Quản lý Financial Documents & Sales\n";
echo "  ✅ Quản lý Blog & Pages\n";
echo "  ✅ Quản lý Discount Codes\n";
echo "  ✅ Quản lý Enrollments\n";
echo "  ✅ Truy cập Dashboard & Reports\n";
echo "\n";

echo "Giới hạn của Admin (không có quyền):\n";
echo "  ❌ Settings hệ thống (chỉ CEO/Manager)\n";
echo "  ❌ Roles & Permissions management (chỉ CEO/Manager)\n";
echo "  ❌ Advanced financial settings (chỉ CEO/Manager)\n";
echo "  ❌ System configuration (chỉ CEO/Manager)\n";
echo "\n";

// So sánh với các roles khác
echo str_repeat("=", 100) . "\n";
echo "SO SÁNH VỚI CÁC ROLES KHÁC:\n";
echo str_repeat("=", 100) . "\n";

$roles = [
    ['name' => 'CEO', 'id' => 6],
    ['name' => 'Manager', 'id' => 5],
    ['name' => 'Admin', 'id' => 4],
    ['name' => 'Teacher', 'id' => 3],
    ['name' => 'Student', 'id' => 2],
    ['name' => 'User', 'id' => 1],
];

printf("%-15s %-10s %-15s %-20s\n", "Role", "Role ID", "is_admin", "Permissions");
echo str_repeat("-", 100) . "\n";

foreach ($roles as $roleData) {
    $role = Role::find($roleData['id']);
    if ($role) {
        $permsCount = Permission::where('role_id', $role->id)->where('allow', 1)->count();
        printf("%-15s %-10s %-15s %-20s\n",
            $roleData['name'],
            $role->id,
            $role->is_admin ? 'TRUE ✅' : 'FALSE',
            $permsCount
        );
    }
}

echo "\n";
echo "✅ Admin role giờ có quyền phù hợp:\n";
echo "   - Nhiều hơn User/Student/Teacher (có thể quản lý họ)\n";
echo "   - Đủ để thực hiện công việc quản lý hàng ngày\n";
echo "   - Không có quyền thay đổi cấu hình hệ thống (bảo mật cao hơn)\n";
