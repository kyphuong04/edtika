<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::statement('DROP TABLE IF EXISTS user_word_list_access');
    DB::statement('DROP TABLE IF EXISTS academic_word_list_words');
    DB::statement('DROP TABLE IF EXISTS academic_word_lists');
    
    echo "Tables dropped successfully!\n";
    
    // Run migrations
    exec('php artisan migrate --force', $output, $return);
    
    foreach ($output as $line) {
        echo $line . "\n";
    }
    
    if ($return === 0) {
        echo "Migrations completed successfully!\n";
    } else {
        echo "Migration failed with code: $return\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
