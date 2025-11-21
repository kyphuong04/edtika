#!/usr/bin/env pwsh
# Script để migrate database từ XAMPP sang Docker

param(
    [string]$BackupFile = "",
    [switch]$FromXAMPP = $false
)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Database Migration Tool" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Load .env
if (Test-Path ".env") {
    Get-Content ".env" | ForEach-Object {
        if ($_ -match '^([^#=]+)=(.*)$') {
            $key = $matches[1].Trim()
            $value = $matches[2].Trim()
            [Environment]::SetEnvironmentVariable($key, $value)
        }
    }
}

$DB_DATABASE = [Environment]::GetEnvironmentVariable("DB_DATABASE")
if (-not $DB_DATABASE) {
    $DB_DATABASE = "webieco2_edtika"
}

# Option 1: Export from XAMPP
if ($FromXAMPP) {
    Write-Host "[1/3] Exporting database from XAMPP MySQL..." -ForegroundColor Yellow
    
    $backupPath = "database_backup_$(Get-Date -Format 'yyyyMMdd_HHmmss').sql"
    
    Write-Host "Database: $DB_DATABASE" -ForegroundColor Gray
    Write-Host "Output: $backupPath" -ForegroundColor Gray
    
    & "C:\xampp\mysql\bin\mysqldump.exe" -uroot $DB_DATABASE > $backupPath
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ Database exported successfully" -ForegroundColor Green
        $BackupFile = $backupPath
    } else {
        Write-Host "✗ Failed to export database" -ForegroundColor Red
        exit 1
    }
}

# Option 2: Use existing backup file
if ($BackupFile -eq "") {
    Write-Host "Please provide a backup file:" -ForegroundColor Yellow
    Write-Host "  .\migrate-database.ps1 -BackupFile 'path\to\backup.sql'" -ForegroundColor Gray
    Write-Host "Or export from XAMPP directly:" -ForegroundColor Yellow
    Write-Host "  .\migrate-database.ps1 -FromXAMPP" -ForegroundColor Gray
    exit 0
}

if (-not (Test-Path $BackupFile)) {
    Write-Host "✗ Backup file not found: $BackupFile" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "[2/3] Checking Docker containers..." -ForegroundColor Yellow

$mysqlRunning = docker-compose ps mysql | Select-String "Up"
if (-not $mysqlRunning) {
    Write-Host "✗ MySQL container is not running" -ForegroundColor Red
    Write-Host "Please start containers first: docker-compose up -d" -ForegroundColor Yellow
    exit 1
}
Write-Host "✓ MySQL container is running" -ForegroundColor Green

Write-Host ""
Write-Host "[3/3] Importing database to Docker MySQL..." -ForegroundColor Yellow
Write-Host "This may take a few minutes..." -ForegroundColor Gray

Get-Content $BackupFile | docker-compose exec -T mysql mysql -uroot -proot $DB_DATABASE

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Database imported successfully" -ForegroundColor Green
    Write-Host ""
    Write-Host "Database '$DB_DATABASE' is now available in Docker MySQL" -ForegroundColor Green
    Write-Host "You can access it via PHPMyAdmin: http://localhost:8080" -ForegroundColor Cyan
} else {
    Write-Host "✗ Failed to import database" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Clear Laravel caches:" -ForegroundColor Gray
Write-Host "   docker-compose exec php php artisan config:clear" -ForegroundColor Gray
Write-Host "   docker-compose exec php php artisan cache:clear" -ForegroundColor Gray
Write-Host "2. Visit http://edtika.local" -ForegroundColor Gray
Write-Host "3. Update license domain to 'edtika.local'" -ForegroundColor Gray
Write-Host ""
