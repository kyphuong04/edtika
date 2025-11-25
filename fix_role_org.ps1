# Simple literal string replacement
Get-ChildItem -Path "app" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    
    # Replace arrays with organization
    $content = $content -replace '\[Role::\$teacher, Role::\$organization\]', '[Role::$teacher]'
    $content = $content -replace '\[Role::\$organization, Role::\$teacher\]', '[Role::$teacher]'
    $content = $content -replace 'Role::\$organization, Role::\$teacher', 'Role::$teacher'
    $content = $content -replace 'Role::\$teacher, Role::\$organization', 'Role::$teacher'
    
    # Replace single usage
    $content = $content -replace 'Role::\$organization', 'Role::$teacher'
    
    Set-Content -Path $_.FullName -Value $content -NoNewline
}

Write-Host "Done!"
