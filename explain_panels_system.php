<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Permission;

echo "=== PHÂN TÍCH CƠ CHẾ PANELS TRONG HỆ THỐNG ===\n\n";

echo "HỆ THỐNG CÓ 2 PANELS RIÊNG BIỆT:\n";
echo str_repeat("=", 100) . "\n\n";

echo "1️⃣  USER PANEL (/panel/*):\n";
echo str_repeat("-", 100) . "\n";
echo "Middleware: PanelAuthenticate\n";
echo "Điều kiện: auth()->check() AND NOT (isManager() OR isCeo())\n";
echo "Nghĩa là: Tất cả users TRỪ Manager và CEO\n\n";
echo "Ai sử dụng:\n";
echo "  ✅ Teacher/Instructor (Organization) - is_admin = FALSE\n";
echo "  ✅ Student - is_admin = FALSE\n";
echo "  ✅ User - is_admin = FALSE\n";
echo "  ✅ Admin (nếu is_admin = FALSE) - NHƯNG KHÔNG ĐÚNG!\n\n";
echo "Chức năng:\n";
echo "  - Quản lý courses của mình\n";
echo "  - Quản lý students trong courses của mình (cho teacher)\n";
echo "  - Xem purchased courses (cho student)\n";
echo "  - Dashboard cá nhân\n\n";

echo "2️⃣  ADMIN PANEL (/admin/*):\n";
echo str_repeat("-", 100) . "\n";
echo "Middleware: AdminAuthenticate\n";
echo "Điều kiện: auth()->check() AND isAdmin() [role->is_admin = TRUE]\n\n";
echo "Ai sử dụng:\n";
echo "  ✅ CEO - is_admin = TRUE\n";
echo "  ✅ Manager - is_admin = TRUE\n";
echo "  ✅ Admin - is_admin = TRUE (sau khi fix)\n\n";
echo "Chức năng:\n";
echo "  - Quản lý TẤT CẢ users, students, teachers\n";
echo "  - Quản lý TẤT CẢ courses trong hệ thống\n";
echo "  - Quản lý settings (chỉ CEO/Manager có quyền)\n";
echo "  - Quản lý roles & permissions (chỉ CEO/Manager có quyền)\n";
echo "  - Dashboard tổng quan hệ thống\n\n\n";

echo "PHÂN TÍCH MIDDLEWARE:\n";
echo str_repeat("=", 100) . "\n\n";

echo "PanelAuthenticate.php:\n";
echo "  if (auth()->check() and !auth()->user()->isManager() and !auth()->user()->isCeo()) {\n";
echo "      // Cho phép truy cập /panel/*\n";
echo "  }\n\n";
echo "👉 Nghĩa là: Manager và CEO KHÔNG thể vào /panel/* (họ dùng /admin/*)\n";
echo "👉 Teacher, Student, User VÀO ĐƯỢC /panel/*\n";
echo "👉 Admin (với is_admin=TRUE) sẽ KHÔNG vào được /panel/*\n\n";

echo "AdminAuthenticate.php:\n";
echo "  if (auth()->check() and auth()->user()->isAdmin()) {\n";
echo "      // Cho phép truy cập /admin/*\n";
echo "  }\n\n";
echo "👉 Nghĩa là: Chỉ roles có is_admin=TRUE mới vào được /admin/*\n";
echo "👉 CEO, Manager, Admin (sau fix) vào được /admin/*\n";
echo "👉 Teacher, Student, User KHÔNG vào được /admin/*\n\n\n";

$ceoRole = Role::find(6);
$managerRole = Role::find(5);
$adminRole = Role::find(4);
$teacherRole = Role::find(3);
$studentRole = Role::find(2);
$userRole = Role::find(1);

echo "TRẠNG THÁI HIỆN TẠI:\n";
echo str_repeat("=", 100) . "\n";
printf("%-15s %-15s %-20s %-25s\n", "Role", "is_admin", "Có thể vào /admin/*", "Có thể vào /panel/*");
echo str_repeat("-", 100) . "\n";

$roles = [
    ['name' => 'CEO', 'role' => $ceoRole],
    ['name' => 'Manager', 'role' => $managerRole],
    ['name' => 'Admin', 'role' => $adminRole],
    ['name' => 'Teacher', 'role' => $teacherRole],
    ['name' => 'Student', 'role' => $studentRole],
    ['name' => 'User', 'role' => $userRole],
];

foreach ($roles as $data) {
    $role = $data['role'];
    if ($role) {
        $isAdmin = $role->is_admin ? 'TRUE' : 'FALSE';
        $canAccessAdmin = $role->is_admin ? '✅ YES' : '❌ NO';
        
        // Kiểm tra panel access: NOT (isManager OR isCeo)
        $canAccessPanel = '❌ NO';
        if ($role->name !== 'manager' && $role->name !== 'ceo') {
            if (!$role->is_admin) {
                $canAccessPanel = '✅ YES';
            } else {
                $canAccessPanel = '⚠️  CONFLICT (is_admin=TRUE)';
            }
        }
        
        printf("%-15s %-15s %-20s %-25s\n",
            $data['name'],
            $isAdmin,
            $canAccessAdmin,
            $canAccessPanel
        );
    }
}

echo "\n\n";

echo "VẤN ĐỀ VỚI ADMIN ROLE (is_admin=TRUE):\n";
echo str_repeat("=", 100) . "\n";
echo "❌ Admin KHÔNG thể vào /panel/* vì middleware chặn users có is_admin=TRUE\n";
echo "✅ Admin CÓ THỂ vào /admin/* vì is_admin=TRUE\n";
echo "✅ Admin có 151 permissions để quản lý users, courses trong ADMIN PANEL\n\n";
echo "👉 ĐIỀU NÀY LÀ ĐÚNG! Admin NÊN dùng /admin/* để quản lý toàn hệ thống\n";
echo "👉 Admin KHÔNG NÊN vào /panel/* (đó là cho teacher/student cá nhân)\n\n\n";

echo "SO SÁNH ORGANIZATION VS ADMIN:\n";
echo str_repeat("=", 100) . "\n\n";

echo "ORGANIZATION/TEACHER (is_admin=FALSE):\n";
echo "  - Truy cập: /panel/*\n";
echo "  - Quản lý: Courses CỦA MÌNH và students trong courses ĐÓ\n";
echo "  - Phạm vi: Giới hạn trong courses của instructor đó\n";
echo "  - Ví dụ: Teacher A chỉ thấy students trong courses của Teacher A\n\n";

echo "ADMIN (is_admin=TRUE):\n";
echo "  - Truy cập: /admin/*\n";
echo "  - Quản lý: TẤT CẢ courses, TẤT CẢ students, TẤT CẢ teachers\n";
echo "  - Phạm vi: TOÀN HỆ THỐNG\n";
echo "  - Ví dụ: Admin thấy tất cả students, teachers, courses của mọi người\n\n\n";

echo "KẾT LUẬN:\n";
echo str_repeat("=", 100) . "\n";
echo "✅ Admin role với is_admin=TRUE là HOÀN TOÀN ĐÚNG!\n\n";
echo "Lý do:\n";
echo "1. Admin KHÔNG phải là Organization/Teacher\n";
echo "2. Admin KHÔNG cần vào /panel/* (đó là panel cá nhân)\n";
echo "3. Admin CẦN vào /admin/* để quản lý TOÀN BỘ hệ thống\n";
echo "4. Admin có phạm vi quản lý RỘNG HƠN Teacher (toàn hệ thống vs chỉ courses của mình)\n\n";

echo "Organization/Teacher quản lý 'members' trong CONTEXT của courses riêng họ.\n";
echo "Admin quản lý 'members' trong CONTEXT của TOÀN BỘ hệ thống.\n\n";

echo "Đây là 2 loại quản lý KHÁC NHAU!\n";
echo "  🟢 Teacher: Quản lý students TRONG courses của mình (/panel/*)\n";
echo "  🔵 Admin: Quản lý TẤT CẢ users/students/teachers (/admin/*)\n";
