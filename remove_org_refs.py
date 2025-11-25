import re
import sys

# Files to process
files_to_update = [
    ('app/Http/Controllers/Panel/UpcomingCoursesController.php', [
        (r'\$isOrganization = \$user->isOrganization\(\);', '// organization role removed'),
        (r"'isOrganization' => \$isOrganization,", "// 'isOrganization' removed"),
        (r"'teachers' => \$teachers,", "// 'teachers' removed"),  
        (r'if \(\$isOrganization\) \{\s+\$teachers = \$user->getOrganizationTeachers\(\)->get\(\);\s+\}', '// organization teachers removed'),
        (r"empty\(\$data\['teacher_id'\]\) and \$user->isOrganization\(\)", "false // organization check removed"),
        (r'\$data\[.teachers.\] = \$user->getOrganizationTeachers\(\)->get\(\);', '// organization teachers removed'),
    ]),
    ('app/Http/Controllers/Web/BecomeInstructorController.php', [
        (r'\$isOrganizationRole', '\$removed_org_role'),
    ]),
    ('app/Http/Controllers/Web/UserProfileController.php', [
        (r'if \(\$user->isOrganization\(\)\)', 'if (false) // organization removed'),
    ]),
    ('app/Http/Controllers/Admin/WebinarController.php', [
        (r'\$creator->isOrganization\(\) and', 'false and // organization removed and'),
    ]),
    ('app/Models/Webinar.php', [
        (r'\$this->creator->isOrganization\(\) and', 'false and // organization removed and'),
    ]),
    ('app/Models/Category.php', [
        (r'!\$occupation->user->isOrganization\(\) and', ''),
    ]),
]

for filepath, replacements in files_to_update:
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        for pattern, replacement in replacements:
            content = re.sub(pattern, replacement, content, flags=re.MULTILINE | re.DOTALL)
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        
        print(f"✓ Updated {filepath}")
    except Exception as e:
        print(f"✗ Error updating {filepath}: {e}")

print("\nDone!")
