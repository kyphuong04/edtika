<?php

// Script to replace isOrganization() in Blade templates

$baseDir = __DIR__;

// Find all Blade files
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($baseDir . '/resources/views'),
    RecursiveIteratorIterator::SELF_FIRST
);

$filesUpdated = 0;
$filesChecked = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
        $filePath = $file->getPathname();
        $filesChecked++;
        
        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        // Check if file contains isOrganization
        if (strpos($content, 'isOrganization') === false) {
            continue;
        }
        
        // Replace all variations in Blade files
        $content = str_replace('->isOrganization()', '->isAdmin()', $content);
        $content = str_replace('$user->isOrganization()', '$user->isAdmin()', $content);
        $content = str_replace('$authUser->isOrganization()', '$authUser->isAdmin()', $content);
        $content = str_replace('$cardUser->isOrganization()', '$cardUser->isAdmin()', $content);
        $content = str_replace('$affiliate->affiliateUser->isOrganization()', '$affiliate->affiliateUser->isAdmin()', $content);
        $content = str_replace('$support->user->isOrganization()', '$support->user->isAdmin()', $content);
        $content = str_replace('$bundle->creator->isOrganization()', '$bundle->creator->isAdmin()', $content);
        $content = str_replace('$webinar->creator->isOrganization()', '$webinar->creator->isAdmin()', $content);
        $content = str_replace('$upcomingCourse->creator->isOrganization()', '$upcomingCourse->creator->isAdmin()', $content);
        
        // Keep variable names for backward compatibility (these are passed from controllers)
        // $isOrganization variable will still work since controllers set it based on isAdmin()
        
        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            $relativePath = str_replace($baseDir . '/', '', $filePath);
            echo "✅ Updated: $relativePath\n";
            $filesUpdated++;
        }
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Summary:\n";
echo "  Blade files checked: $filesChecked\n";
echo "  Blade files updated: $filesUpdated\n";
echo "✅ Blade template replacement complete!\n";
