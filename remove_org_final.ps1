Get-ChildItem -Path "app\Http\Controllers\Admin" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace '!\\$user->isOrganization\(\) and ', ''
    $content = $content -replace ' and !\\$user->isOrganization\(\)', ''
    $content = $content -replace '\\$user->isOrganization\(\) \|\| ', ''
    $content = $content -replace '\\$creator->isOrganization\(\) and', 'false and // organization removed and'
    Set-Content -Path $_.FullName -Value $content
}

Get-ChildItem -Path "app\Http\Controllers\Web" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace 'if \(\\$user->isOrganization\(\)\)', 'if (false) // organization removed'
    $content = $content -replace '\\$isOrganizationRole = \(!empty\(\\$lastRequest\) and \\$lastRequest->role == Role::\\$organization\);', '// organization role removed'
    $content = $content -replace "'isOrganizationRole' => \\$isOrganizationRole,", "// 'isOrganizationRole' removed"  
    $content = $content -replace '\\$user->isAdmin\(\) or \\$user->isTeacher\(\) or \\$user->isOrganization\(\)', '\\$user->isAdmin() or \\$user->isTeacher()'
    Set-Content -Path $_.FullName -Value $content
}

Get-ChildItem -Path "app\Models" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace '\\$this->creator->isOrganization\(\) and', 'false and // organization removed and'
    $content = $content -replace '->user->isOrganization\(\) and', '->user->isTeacher() and // changed from organization'
    Set-Content -Path $_.FullName -Value $content
}

Get-ChildItem -Path "app\Exports" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace '->isOrganization\(\)', '->isTeacher() // changed from organization'
    Set-Content -Path $_.FullName -Value $content
}

Write-Host "Batch 2 Done!"
