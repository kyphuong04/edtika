<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "  FORCE CLEAR LOCALE & RESTART  \n";
echo "========================================\n\n";

// Clear all caches
\Artisan::call('cache:clear');
echo "✓ Cache cleared\n";

\Artisan::call('config:clear');
echo "✓ Config cleared\n";

\Artisan::call('view:clear');
echo "✓ Views cleared\n";

// Clear sessions
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✓ Sessions cleared\n";
}

echo "\n========================================\n";
echo "Now do this:\n";
echo "1. Close ALL browser tabs\n";
echo "2. Clear browser cookies for edtika.local:\n";
echo "   - Chrome: F12 > Application > Cookies\n";
echo "   - Delete all cookies for edtika.local\n";
echo "3. Close browser completely\n";
echo "4. Open new browser window\n";
echo "5. Go to http://edtika.local/\n";
echo "6. Should be in Vietnamese!\n";
echo "========================================\n";
