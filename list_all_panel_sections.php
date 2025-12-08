<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Section;
use Illuminate\Support\Facades\DB;

echo "=== TÌM TẤT CẢ PANEL SECTIONS ===\n\n";

// Lấy tất cả sections bắt đầu với 'panel'
$panelSections = Section::where('name', 'like', 'panel%')
    ->orderBy('name')
    ->get();

echo "TỔNG SỐ: " . $panelSections->count() . " panel sections\n\n";

// Nhóm theo category
$grouped = [];
foreach ($panelSections as $section) {
    $parts = explode('_', $section->name);
    if (count($parts) >= 2) {
        $category = $parts[1]; // Lấy phần sau 'panel_'
    } else {
        $category = 'other';
    }
    
    if (!isset($grouped[$category])) {
        $grouped[$category] = [];
    }
    
    $grouped[$category][] = $section;
}

ksort($grouped);

foreach ($grouped as $category => $sections) {
    echo str_repeat("=", 100) . "\n";
    echo strtoupper($category) . " (" . count($sections) . " sections)\n";
    echo str_repeat("=", 100) . "\n";
    
    foreach ($sections as $section) {
        echo sprintf("[%4d] %-60s %s\n", $section->id, $section->name, $section->caption);
    }
    
    echo "\n";
}
