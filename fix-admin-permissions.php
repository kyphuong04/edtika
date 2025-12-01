<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

echo "===========================================\n";
echo "  FIX LỖI 403: CẤP QUYỀN SETTINGS  \n";
echo "===========================================\n\n";

// Lấy Admin role
$adminRole = Role::where('name', Role::$admin)->first();
if (!$adminRole) {
    echo "❌ Không tìm thấy Admin role!\n";
    exit(1);
}
echo "✓ Admin Role ID: {$adminRole->id}\n";

// Lấy settings permissions
$sections = Section::where('name', 'LIKE', 'admin_settings%')->get();
echo "✓ Tìm thấy " . $sections->count() . " permissions\n\n";

// Check hiện tại
$current = DB::table('role_sections')
    ->where('role_id', $adminRole->id)
    ->whereIn('section_id', $sections->pluck('id'))
    ->pluck('section_id')
    ->toArray();

echo "Admin đang có: " . count($current) . " permissions\n\n";

// Tìm thiếu
$missing = $sections->whereNotIn('id', $current);

if ($missing->isEmpty()) {
    echo "✅ Đã có đầy đủ permissions!\n";
    echo "\nLàm thêm:\n";
    echo "1. Logout và login lại\n";
    echo "2. Ctrl+Shift+R để hard refresh\n";
    exit(0);
}

echo "⚠️ Thiếu {$missing->count()} permissions:\n";
foreach ($missing as $s) {
    echo "  - {$s->name}\n";
}

// Cấp quyền
echo "\n🔧 Đang cấp quyền...\n";
$added = 0;
foreach ($missing as $s) {
    try {
        DB::table('role_sections')->insertOrIgnore([
            'role_id' => $adminRole->id,
            'section_id' => $s->id,
        ]);
        echo "  ✓ {$s->name}\n";
        $added++;
    } catch (\Exception $e) {
        echo "  ✗ {$s->name}: {$e->getMessage()}\n";
    }
}

echo "\n✅ Đã cấp {$added} permissions!\n";
echo "\nBây giờ:\n";
echo "1. Logout\n";
echo "2. Login lại\n";
echo "3. Test save settings\n";
