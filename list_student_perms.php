<?php
// List all student permissions to see what admin is missing
try {
    $db = new PDO('mysql:host=localhost;dbname=webieco2_edtika', 'root', '');
    
    echo "=== STUDENT PERMISSIONS (role_id=2) ===\n";
    $stmt = $db->query("
        SELECT s.name, s.caption
        FROM permissions p
        JOIN sections s ON p.section_id = s.id
        WHERE p.role_id = 2 AND p.allow = 1
        AND s.name LIKE '%my%'
        ORDER BY s.name
    ");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   {$row['name']} - {$row['caption']}\n";
    }
    
    echo "\n=== CHECK IF ADMIN HAS THESE ===\n";
    $stmt = $db->query("
        SELECT s.name, s.caption,
        CASE WHEN EXISTS (
            SELECT 1 FROM permissions 
            WHERE role_id = 4 AND section_id = s.id AND allow = 1
        ) THEN '✓' ELSE '✗' END as admin_has
        FROM permissions p
        JOIN sections s ON p.section_id = s.id
        WHERE p.role_id = 2 AND p.allow = 1
        AND s.name LIKE '%my%'
        ORDER BY s.name
    ");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   {$row['admin_has']} {$row['name']}\n";
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
