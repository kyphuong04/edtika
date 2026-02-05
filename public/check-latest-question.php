<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Latest Table Completion Questions ===\n\n";

// Check Mock Bank
$mock = App\Models\IeltsMockQuestionBank::where('question_type', 'table_completion')
    ->orderBy('id', 'desc')
    ->first();

if ($mock) {
    echo "Mock Bank - Latest Question:\n";
    echo "  ID: {$mock->id}\n";
    echo "  Q{$mock->question_number}: {$mock->question_text}\n";
    echo "  table_structure: " . ($mock->table_structure ? 'EXISTS' : 'NULL') . "\n";
    if ($mock->table_structure) {
        echo "  Headers: " . implode(', ', $mock->table_structure['headers'] ?? []) . "\n";
        echo "  Rows: " . count($mock->table_structure['rows'] ?? []) . "\n";
    }
    echo "\n";
}

// Check Practice Bank
$practice = App\Models\IeltsPracticeQuestionBank::where('question_type', 'table_completion')
    ->orderBy('id', 'desc')
    ->first();

if ($practice) {
    echo "Practice Bank - Latest Question:\n";
    echo "  ID: {$practice->id}\n";
    echo "  Q{$practice->question_number ?? 'N/A'}: {$practice->question_text}\n";
    echo "  table_structure: " . ($practice->table_structure ? 'EXISTS' : 'NULL') . "\n";
    if ($practice->table_structure) {
        echo "  Headers: " . implode(', ', $practice->table_structure['headers'] ?? []) . "\n";
        echo "  Rows: " . count($practice->table_structure['rows'] ?? []) . "\n";
    }
    echo "\n";
}

echo "✅ Check complete!\n";
