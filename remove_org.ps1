Get-ChildItem -Path "app\Http\Controllers\Panel" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace '!\\$user->isOrganization\(\) and ', ''
    $content = $content -replace ' and !\\$user->isOrganization\(\)', ''
    $content = $content -replace '\\$user->isOrganization\(\) \|\| ', ''
    $content = $content -replace '\\$isOrganization = \\$user->isOrganization\(\);', '// organization removed'
    $content = $content -replace "'isOrganization' => \\$isOrganization,", "// 'isOrganization' removed"
    Set-Content -Path $_.FullName -Value $content
}

Get-ChildItem -Path "app\Http\Controllers\Api" -Filter "*.php" -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace '!\\$user->isOrganization\(\) and ', ''
    $content = $content -replace ' and !\\$user->isOrganization\(\)', ''  
    $content = $content -replace '\\$user->isOrganization\(\) \|\| ', ''
    $content = $content -replace '} elseif \(\\$user->isOrganization\(\)\)', '} // elseif organization removed'
    Set-Content -Path $_.FullName -Value $content
}

Write-Host "Done!"
