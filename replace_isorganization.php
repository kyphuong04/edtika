<?php

// Comprehensive script to replace ALL isOrganization() references

$baseDir = __DIR__;

// Find all PHP files
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($baseDir . '/app/Http/Controllers'),
    RecursiveIteratorIterator::SELF_FIRST
);

$filesUpdated = 0;
$filesChecked = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filePath = $file->getPathname();
        $filesChecked++;
        
        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        // Check if file contains isOrganization
        if (strpos($content, 'isOrganization') === false) {
            continue;
        }
        
        // Replace all variations
        $content = str_replace('->isOrganization()', '->isAdmin()', $content);
        $content = str_replace('$user->isOrganization()', '$user->isAdmin()', $content);
        $content = str_replace('$creator->isOrganization()', '$creator->isAdmin()', $content);
        $content = str_replace('$teacher->isOrganization()', '$teacher->isAdmin()', $content);
        $content = str_replace('!$user->isAdmin()', '!$user->isAdmin()', $content); // Keep as is
        
        // Replace variable assignments (but keep variable name for backward compatibility)
        $content = preg_replace('/\$isOrganization\s*=\s*\$user->isOrganization\(\)/', '\$isOrganization = \$user->isAdmin()', $content);
        
        // Replace array keys - keep old key name for backward compat
        // $content = str_replace("'isOrganization' => \$isOrganization,", "'isAdmin' => \$isOrganization,", $content);
        
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
echo "  Files checked: $filesChecked\n";
echo "  Files updated: $filesUpdated\n";
echo "✅ Replacement complete!\n";
