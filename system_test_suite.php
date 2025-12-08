<?php
/**
 * Comprehensive System Test Suite
 * Tests all roles, permissions, and core functionalities
 */

require_once __DIR__ . '/vendor/autoload.php';

class SystemTestSuite
{
    private $db;
    private $results = [];
    private $passed = 0;
    private $failed = 0;

    public function __construct()
    {
        // Database connection
        $this->db = new PDO(
            'mysql:host=localhost;dbname=webieco2_edtika;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function runAllTests()
    {
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         COMPREHENSIVE SYSTEM TEST SUITE                   ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n\n";

        $this->testDatabaseSchema();
        $this->testRoles();
        $this->testPermissions();
        $this->testAdminPermissions();
        $this->testIELTSTables();
        $this->testUsers();
        
        $this->printSummary();
    }

    private function testDatabaseSchema()
    {
        echo "\n📊 Testing Database Schema...\n";
        echo str_repeat("─", 60) . "\n";

        // Test 1: Check critical tables exist
        $tables = ['users', 'roles', 'permissions', 'sections', 'webinars', 'quizzes'];
        foreach ($tables as $table) {
            $exists = $this->tableExists($table);
            $this->assert("Table '$table' exists", $exists);
        }
    }

    private function testRoles()
    {
        echo "\n👥 Testing Roles Configuration...\n";
        echo str_repeat("─", 60) . "\n";

        // Test 1: All 6 roles exist
        $stmt = $this->db->query("SELECT COUNT(*) FROM roles WHERE id IN (1,2,3,4,5,6)");
        $count = $stmt->fetchColumn();
        $this->assert("All 6 roles exist", $count == 6);

        // Test 2: Role flags are correct
        $roles = $this->db->query("SELECT id, name, is_admin FROM roles WHERE id IN (1,2,3,4,5,6)")->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($roles as $role) {
            $expected_is_admin = in_array($role['name'], ['manager', 'ceo']) ? 1 : 0;
            $this->assert(
                "Role '{$role['name']}' has correct is_admin flag ({$expected_is_admin})",
                $role['is_admin'] == $expected_is_admin
            );
        }
    }

    private function testPermissions()
    {
        echo "\n🔐 Testing Permissions System...\n";
        echo str_repeat("─", 60) . "\n";

        // Test 1: Permissions table has data
        $stmt = $this->db->query("SELECT COUNT(*) FROM permissions WHERE allow = 1");
        $count = $stmt->fetchColumn();
        $this->assert("Permissions table has data", $count > 0);
        echo "   ℹ️  Total permissions: $count\n";

        // Test 2: Each role has permissions
        $stmt = $this->db->query("
            SELECT r.name, COUNT(p.id) as perm_count
            FROM roles r
            LEFT JOIN permissions p ON r.id = p.role_id AND p.allow = 1
            WHERE r.id IN (1,2,3,4,5,6)
            GROUP BY r.id, r.name
        ");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->assert(
                "Role '{$row['name']}' has permissions",
                $row['perm_count'] > 0
            );
            echo "   ℹ️  {$row['name']}: {$row['perm_count']} permissions\n";
        }
    }

    private function testAdminPermissions()
    {
        echo "\n⭐ Testing Admin Role Permissions...\n";
        echo str_repeat("─", 60) . "\n";

        // Get permission counts
        $stmt = $this->db->query("
            SELECT 
                (SELECT COUNT(*) FROM permissions WHERE role_id = 2 AND allow = 1) as student,
                (SELECT COUNT(*) FROM permissions WHERE role_id = 3 AND allow = 1) as teacher,
                (SELECT COUNT(*) FROM permissions WHERE role_id = 4 AND allow = 1) as admin
        ");
        $counts = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "   ℹ️  Student permissions: {$counts['student']}\n";
        echo "   ℹ️  Teacher permissions: {$counts['teacher']}\n";
        echo "   ℹ️  Admin permissions: {$counts['admin']}\n";

        // Test 1: Admin has >= Student permissions
        $this->assert(
            "Admin has at least as many permissions as Student",
            $counts['admin'] >= $counts['student']
        );

        // Test 2: Admin has >= Teacher permissions
        $this->assert(
            "Admin has at least as many permissions as Teacher",
            $counts['admin'] >= $counts['teacher']
        );

        // Test 3: Check for missing Student permissions
        $stmt = $this->db->query("
            SELECT COUNT(*) as missing
            FROM permissions p
            WHERE p.role_id = 2 AND p.allow = 1
            AND p.section_id NOT IN (
                SELECT section_id FROM permissions WHERE role_id = 4 AND allow = 1
            )
        ");
        $missing_student = $stmt->fetchColumn();
        $this->assert(
            "Admin has all Student permissions",
            $missing_student == 0
        );
        if ($missing_student > 0) {
            echo "   ⚠️  Missing $missing_student Student permissions\n";
        }

        // Test 4: Check for missing Teacher permissions
        $stmt = $this->db->query("
            SELECT COUNT(*) as missing
            FROM permissions p
            WHERE p.role_id = 3 AND p.allow = 1
            AND p.section_id NOT IN (
                SELECT section_id FROM permissions WHERE role_id = 4 AND allow = 1
            )
        ");
        $missing_teacher = $stmt->fetchColumn();
        $this->assert(
            "Admin has all Teacher permissions",
            $missing_teacher == 0
        );
        if ($missing_teacher > 0) {
            echo "   ⚠️  Missing $missing_teacher Teacher permissions\n";
        }

        // Test 5: Check critical admin permissions
        $critical = [
            'panel_organization_instructors',
            'panel_organization_students',
            'panel_webinars',
            'panel_blog'
        ];

        foreach ($critical as $perm) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM permissions p
                JOIN sections s ON p.section_id = s.id
                WHERE p.role_id = 4 AND p.allow = 1 AND s.name = ?
            ");
            $stmt->execute([$perm]);
            $has_perm = $stmt->fetchColumn() > 0;
            $this->assert("Admin has '$perm' permission", $has_perm);
        }
    }

    private function testIELTSTables()
    {
        echo "\n📚 Testing IELTS Tables...\n";
        echo str_repeat("─", 60) . "\n";

        $ielts_tables = [
            'ielts_skills',
            'ielts_question_types',
            'ielts_diagnostic_tests',
            'ielts_writing_submissions',
            'ielts_speaking_submissions',
            'ielts_user_progress'
        ];

        foreach ($ielts_tables as $table) {
            $exists = $this->tableExists($table);
            $this->assert("IELTS table '$table' exists", $exists);
        }

        // Test IELTS skills data
        if ($this->tableExists('ielts_skills')) {
            $stmt = $this->db->query("SELECT COUNT(*) FROM ielts_skills");
            $count = $stmt->fetchColumn();
            $this->assert("IELTS skills table has data (4 skills)", $count == 4);
        }

        // Test IELTS question types data
        if ($this->tableExists('ielts_question_types')) {
            $stmt = $this->db->query("SELECT COUNT(*) FROM ielts_question_types");
            $count = $stmt->fetchColumn();
            $this->assert("IELTS question types table has data", $count > 0);
        }
    }

    private function testUsers()
    {
        echo "\n👤 Testing Test Users...\n";
        echo str_repeat("─", 60) . "\n";

        $test_users = [
            ['email' => 'user@test.com', 'role' => 'user'],
            ['email' => 'student@test.com', 'role' => 'student'],
            ['email' => 'teacher@test.com', 'role' => 'teacher'],
            ['email' => 'admin@test.com', 'role' => 'admin'],
            ['email' => 'manager@test.com', 'role' => 'manager'],
            ['email' => 'ceo@test.com', 'role' => 'ceo']
        ];

        foreach ($test_users as $user) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM users u
                JOIN roles r ON u.role_id = r.id
                WHERE u.email = ? AND r.name = ?
            ");
            $stmt->execute([$user['email'], $user['role']]);
            $exists = $stmt->fetchColumn() > 0;
            $this->assert(
                "Test user '{$user['email']}' exists with role '{$user['role']}'",
                $exists
            );
        }
    }

    private function tableExists($table)
    {
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE '$table'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    private function assert($description, $condition)
    {
        $status = $condition ? '✓ PASS' : '✗ FAIL';
        $color = $condition ? "\033[32m" : "\033[31m";
        $reset = "\033[0m";
        
        echo "   {$color}{$status}{$reset} {$description}\n";
        
        if ($condition) {
            $this->passed++;
        } else {
            $this->failed++;
        }
        
        $this->results[] = [
            'description' => $description,
            'passed' => $condition
        ];
    }

    private function printSummary()
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║                    TEST SUMMARY                            ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        
        $total = $this->passed + $this->failed;
        $pass_rate = $total > 0 ? round(($this->passed / $total) * 100, 2) : 0;
        
        echo "\n";
        echo "   Total Tests: $total\n";
        echo "   ✓ Passed: {$this->passed}\n";
        echo "   ✗ Failed: {$this->failed}\n";
        echo "   Pass Rate: {$pass_rate}%\n";
        echo "\n";
        
        if ($this->failed == 0) {
            echo "   🎉 ALL TESTS PASSED! System is working correctly.\n";
        } else {
            echo "   ⚠️  SOME TESTS FAILED. Please review the failures above.\n";
        }
        
        echo "\n";
        echo str_repeat("═", 60) . "\n";
    }
}

// Run tests
try {
    $suite = new SystemTestSuite();
    $suite->runAllTests();
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
