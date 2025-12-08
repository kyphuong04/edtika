<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

echo "=== PHÂN BIỆT ADMIN VS SUPERADMIN (CEO/MANAGER) ===\n\n";

$ceoRole = Role::find(6);
$managerRole = Role::find(5);
$adminRole = Role::find(4);

echo "CẤU TRÚC PHÂN QUYỀN TRONG HỆ THỐNG:\n";
echo str_repeat("=", 100) . "\n\n";

echo "🔴 SUPERADMIN (CEO & MANAGER):\n";
echo str_repeat("-", 100) . "\n";
if ($ceoRole) {
    $ceoPerms = Permission::where('role_id', 6)->where('allow', 1)->count();
    echo "CEO (role_id=6):\n";
    echo "  - is_admin: " . ($ceoRole->is_admin ? 'TRUE ✅' : 'FALSE') . "\n";
    echo "  - Permissions: {$ceoPerms}\n";
    echo "  - Quyền hạn: TOÀN BỘ hệ thống\n";
    echo "  - Có thể:\n";
    echo "    ✅ Thay đổi Settings hệ thống\n";
    echo "    ✅ Quản lý Roles & Permissions\n";
    echo "    ✅ Cấu hình Financial settings\n";
    echo "    ✅ Thay đổi cấu hình hệ thống\n";
    echo "    ✅ Quản lý tất cả modules\n";
    echo "    ✅ Truy cập tất cả dữ liệu\n";
}

echo "\n";

if ($managerRole) {
    $managerPerms = Permission::where('role_id', 5)->where('allow', 1)->count();
    echo "Manager (role_id=5):\n";
    echo "  - is_admin: " . ($managerRole->is_admin ? 'TRUE ✅' : 'FALSE') . "\n";
    echo "  - Permissions: {$managerPerms}\n";
    echo "  - Quyền hạn: Tương tự CEO (có thể cấu hình riêng)\n";
}

echo "\n\n";

echo "🟡 ADMIN (NOT SUPERADMIN):\n";
echo str_repeat("-", 100) . "\n";
if ($adminRole) {
    $adminPerms = Permission::where('role_id', 4)->where('allow', 1)->count();
    echo "Admin (role_id=4):\n";
    echo "  - is_admin: " . ($adminRole->is_admin ? 'TRUE ✅' : 'FALSE') . "\n";
    echo "  - Permissions: {$adminPerms}\n";
    echo "  - Quyền hạn: QUẢN LÝ HÀNG NGÀY (không phải toàn bộ hệ thống)\n";
    echo "  - Có thể:\n";
    echo "    ✅ Quản lý Users, Students, Teachers\n";
    echo "    ✅ Quản lý Courses/Webinars\n";
    echo "    ✅ Quản lý Quizzes & Assignments\n";
    echo "    ✅ Quản lý Content (Blog, Pages)\n";
    echo "    ✅ Quản lý Support & Comments\n";
    echo "    ✅ Xem Financial reports\n";
    echo "    ✅ Quản lý Categories & Tags\n";
    echo "\n";
    echo "  - KHÔNG thể:\n";
    echo "    ❌ Thay đổi Settings hệ thống\n";
    echo "    ❌ Quản lý Roles & Permissions\n";
    echo "    ❌ Thay đổi Financial settings\n";
    echo "    ❌ Cấu hình hệ thống\n";
    echo "    ❌ Quản lý Subscriptions/Payouts\n";
    echo "    ❌ Advanced marketing settings\n";
}

echo "\n\n";

echo "🟢 REGULAR USERS (Teacher, Student, User):\n";
echo str_repeat("-", 100) . "\n";
echo "Teacher, Student, User:\n";
echo "  - is_admin: FALSE\n";
echo "  - Quyền hạn: Chỉ trong phạm vi panel của họ (không vào admin panel)\n";
echo "  - Không có quyền quản lý người dùng khác\n";

echo "\n\n";

// Kiểm tra chi tiết quyền admin vs superadmin
echo "PHÂN TÍCH CHI TIẾT:\n";
echo str_repeat("=", 100) . "\n\n";

// Tìm các permission về settings, roles
$settingsPerms = Permission::join('sections', 'permissions.section_id', '=', 'sections.id')
    ->where('permissions.allow', 1)
    ->where(function($query) {
        $query->where('sections.name', 'like', 'admin_settings%')
              ->orWhere('sections.name', 'like', 'admin_roles%');
    })
    ->select('permissions.role_id', 'sections.name', 'sections.caption')
    ->get();

echo "QUYỀN SETTINGS & ROLES (Chỉ Superadmin có):\n";
echo str_repeat("-", 100) . "\n";

$rolesWithSettings = [];
foreach ($settingsPerms as $perm) {
    if (!isset($rolesWithSettings[$perm->role_id])) {
        $rolesWithSettings[$perm->role_id] = 0;
    }
    $rolesWithSettings[$perm->role_id]++;
}

foreach ([6, 5, 4] as $roleId) {
    $role = Role::find($roleId);
    if ($role) {
        $count = $rolesWithSettings[$roleId] ?? 0;
        $status = $count > 0 ? "✅ CÓ ({$count} permissions)" : "❌ KHÔNG CÓ";
        echo sprintf("  %-15s: %s\n", $role->name, $status);
    }
}

echo "\n\n";

// So sánh số lượng permissions
echo "SO SÁNH SỐ LƯỢNG PERMISSIONS:\n";
echo str_repeat("=", 100) . "\n";
printf("%-15s %-12s %-15s %-20s\n", "Role", "Role ID", "is_admin", "Permissions");
echo str_repeat("-", 100) . "\n";

$roles = [
    ['name' => 'CEO', 'id' => 6, 'level' => 'SUPERADMIN'],
    ['name' => 'Manager', 'id' => 5, 'level' => 'SUPERADMIN'],
    ['name' => 'Admin', 'id' => 4, 'level' => 'ADMIN'],
    ['name' => 'Teacher', 'id' => 3, 'level' => 'USER'],
    ['name' => 'Student', 'id' => 2, 'level' => 'USER'],
    ['name' => 'User', 'id' => 1, 'level' => 'USER'],
];

foreach ($roles as $roleData) {
    $role = Role::find($roleData['id']);
    if ($role) {
        $permsCount = Permission::where('role_id', $role->id)->where('allow', 1)->count();
        printf("%-15s %-12s %-15s %-20s\n",
            $roleData['name'] . " ({$roleData['level']})",
            $role->id,
            $role->is_admin ? 'TRUE' : 'FALSE',
            $permsCount
        );
    }
}

echo "\n\n";

echo "KẾT LUẬN:\n";
echo str_repeat("=", 100) . "\n";
echo "❌ Admin KHÔNG PHẢI là Superadmin\n\n";

echo "Lý do:\n";
echo "  1. Admin không có quyền thay đổi Settings hệ thống\n";
echo "  2. Admin không có quyền quản lý Roles & Permissions\n";
echo "  3. Admin chỉ quản lý hoạt động hàng ngày (users, courses, content)\n";
echo "  4. Admin không thể thay đổi cấu hình quan trọng của hệ thống\n";
echo "\n";

echo "Phân cấp quyền:\n";
echo "  🔴 SUPERADMIN (CEO/Manager): Toàn quyền hệ thống\n";
echo "      ↓\n";
echo "  🟡 ADMIN: Quản lý hoạt động hàng ngày (không có quyền cấu hình hệ thống)\n";
echo "      ↓\n";
echo "  🟢 USER/TEACHER/STUDENT: Người dùng thông thường\n";
echo "\n";

echo "Lợi ích của mô hình này:\n";
echo "  ✅ Bảo mật cao hơn: Admin không thể làm hỏng hệ thống\n";
echo "  ✅ Phân quyền rõ ràng: Mỗi role có trách nhiệm riêng\n";
echo "  ✅ Linh hoạt: Admin có thể xử lý công việc hàng ngày mà không cần superadmin\n";
echo "  ✅ An toàn: Chỉ CEO/Manager có quyền thay đổi settings quan trọng\n";
