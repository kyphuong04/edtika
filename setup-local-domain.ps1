# Setup Local Domain for Edtika LMS
# This script must be run as Administrator

Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "  Edtika LMS - Local Domain Setup" -ForegroundColor Cyan
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "ERROR: This script must be run as Administrator!" -ForegroundColor Red
    Write-Host "Right-click on PowerShell and select 'Run as Administrator'" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit
}

# Step 1: Add hosts file entry
Write-Host "[1/3] Adding edtika.local to hosts file..." -ForegroundColor Yellow
$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$hostsContent = Get-Content $hostsPath -Raw
$domain = "127.0.0.1       edtika.local"

if ($hostsContent -notmatch "edtika\.local") {
    Add-Content -Path $hostsPath -Value "`n$domain"
    Write-Host "  ✓ Added edtika.local to hosts file" -ForegroundColor Green
} else {
    Write-Host "  ✓ edtika.local already exists in hosts file" -ForegroundColor Green
}

# Step 2: Verify virtual host configuration
Write-Host "`n[2/3] Checking XAMPP virtual host configuration..." -ForegroundColor Yellow
$vhostPath = "C:\xampp\apache\conf\extra\httpd-vhosts.conf"
$vhostContent = Get-Content $vhostPath -Raw

if ($vhostContent -match "edtika\.local") {
    Write-Host "  ✓ Virtual host configured for edtika.local" -ForegroundColor Green
} else {
    Write-Host "  ✗ Virtual host NOT configured for edtika.local" -ForegroundColor Red
    Write-Host "  Please add edtika.local to ServerAlias in $vhostPath" -ForegroundColor Yellow
}

# Step 3: Restart Apache
Write-Host "`n[3/3] Restarting Apache..." -ForegroundColor Yellow

# Try to stop Apache using net command
$stopResult = net stop Apache2.4 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Host "  ✓ Apache stopped" -ForegroundColor Green
    Start-Sleep -Seconds 2
    
    # Start Apache
    $startResult = net start Apache2.4 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  ✓ Apache started" -ForegroundColor Green
    } else {
        Write-Host "  ✗ Failed to start Apache" -ForegroundColor Red
        Write-Host "  Please start Apache manually from XAMPP Control Panel" -ForegroundColor Yellow
    }
} else {
    Write-Host "  ! Could not restart Apache automatically" -ForegroundColor Yellow
    Write-Host "  Please restart Apache manually from XAMPP Control Panel" -ForegroundColor Yellow
}

# Summary
Write-Host "`n==================================================" -ForegroundColor Cyan
Write-Host "  Setup Complete!" -ForegroundColor Green
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "1. Make sure Apache is running in XAMPP Control Panel" -ForegroundColor White
Write-Host "2. Open your browser and go to: http://edtika.local" -ForegroundColor White
Write-Host "3. Login and go to 'Licenses' menu" -ForegroundColor White
Write-Host "4. Change license domain from 'edtika.com' to 'edtika.local'" -ForegroundColor White
Write-Host ""
Write-Host "If you see any issues, check:" -ForegroundColor Yellow
Write-Host "- ionCube Loader is installed: http://edtika.local/check.php" -ForegroundColor White
Write-Host "- Database connection is working" -ForegroundColor White
Write-Host "- PHP error logs in C:\xampp\apache\logs\error.log" -ForegroundColor White
Write-Host ""

Read-Host "Press Enter to exit"
