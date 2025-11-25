# Remove all Role::$organization references

# First, make sure the constant is removed from Role.php
$roleFile = "app\Models\Role.php"
$content = Get-Content $roleFile -Raw
$content = $content -replace "static \\\$organization = 'organization';", "// static \$organization removed"
Set-Content -Path $roleFile -Value $content

# Process all files with Role::$organization
Get-ChildItem -Path "app" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    
    # Replace whereIn with Role::$organization
    $content = $content -replace "\[Role::\\\$teacher, Role::\\\$organization\]", "[Role::\$teacher]"
    $content = $content -replace "\[Role::\\\$organization, Role::\\\$teacher\]", "[Role::\$teacher]"
    
    # Replace single Role::$organization
    $content = $content -replace "Role::\\\$organization", "Role::\$teacher // changed from organization"
    
    # Fix specific patterns
    $content = $content -replace "where\('role_name', Role::\\\$teacher // changed from organization\)", "where('role_name', Role::\$teacher)"
    $content = $content -replace "== Role::\\\$teacher // changed from organization", "== Role::\$teacher"
    $content = $content -replace "\\('role_name', Role::\\\$teacher // changed from organization\\)", "('role_name', Role::\$teacher)"
    
    Set-Content -Path $_.FullName -Value $content
}

Write-Host "All Role::organization references removed!"
