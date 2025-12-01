# ===================================================================
# Script tối ưu hóa hiệu suất Laravel cho môi trường local XAMPP
# ===================================================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  EDTIKA Performance Optimization Tool  " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Clear tất cả cache
Write-Host "[1/5] Clearing all Laravel caches..." -ForegroundColor Yellow
php artisan optimize:clear
Write-Host "✓ Caches cleared" -ForegroundColor Green
Write-Host ""

# 2. Optimize Laravel
Write-Host "[2/5] Optimizing Laravel..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache
Write-Host "✓ Laravel optimized" -ForegroundColor Green
Write-Host ""

# 3. Optimize Composer (regenerate autoload)
Write-Host "[3/5] Optimizing Composer autoload..." -ForegroundColor Yellow
composer dump-autoload -o
Write-Host "✓ Composer autoload optimized" -ForegroundColor Green
Write-Host ""

# 4. Clear session files (nếu có quá nhiều)
Write-Host "[4/5] Checking session files..." -ForegroundColor Yellow
$sessionPath = "storage/framework/sessions"
if (Test-Path $sessionPath) {
    $sessionFiles = Get-ChildItem $sessionPath -File
    $count = $sessionFiles.Count
    Write-Host "Found $count session files" -ForegroundColor Gray
    if ($count -gt 1000) {
        Write-Host "Cleaning old session files..." -ForegroundColor Yellow
        $sessionFiles | Where-Object { $_.LastWriteTime -lt (Get-Date).AddDays(-7) } | Remove-Item
        Write-Host "✓ Old session files cleaned" -ForegroundColor Green
    }
    else {
        Write-Host "✓ Session files OK" -ForegroundColor Green
    }
}
Write-Host ""

# 5. Storage permissions và cleanup
Write-Host "[5/5] Cleaning storage and logs..." -ForegroundColor Yellow
# Xóa log files cũ hơn 7 ngày
$logPath = "storage/logs"
if (Test-Path $logPath) {
    Get-ChildItem $logPath -Filter "*.log" | Where-Object { $_.LastWriteTime -lt (Get-Date).AddDays(-7) } | Remove-Item -Force
    Write-Host "✓ Old logs cleaned" -ForegroundColor Green
}
Write-Host ""

# Hiển thị thông tin hiện tại
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Current Configuration Status          " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Kiểm tra PHP memory
$memoryLimit = php -r "echo ini_get('memory_limit');"
Write-Host "PHP Memory Limit: $memoryLimit" -ForegroundColor Gray

# Kiểm tra opcache
$opcacheEnabled = php -r "echo extension_loaded('opcache') ? 'Enabled' : 'Disabled';"
Write-Host "OPcache Status: $opcacheEnabled" -ForegroundColor $(if ($opcacheEnabled -eq 'Enabled') { 'Green' } else { 'Red' })

# Kiểm tra APP_DEBUG từ .env
if (Test-Path ".env") {
    $appDebug = Get-Content .env | Select-String "APP_DEBUG"
    Write-Host "App Debug: $appDebug" -ForegroundColor Gray
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Optimization Complete!                 " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Review performance-recommendations.md for important fixes" -ForegroundColor White
Write-Host "2. Restart Apache in XAMPP control panel" -ForegroundColor White
Write-Host "3. Test your application at http://edtika.local" -ForegroundColor White
Write-Host ""
