# =============================================
#  WebERP / WebPOS — One-click start (PowerShell)
#  Usage: .\start.ps1
# =============================================

$ErrorActionPreference = "Stop"

function Write-Color($Text, $Color = "White") {
    Write-Host $Text -ForegroundColor $Color
}

Write-Color "=========================================" "Cyan"
Write-Color " 🚀  WebERP / WebPOS — Starting up" "Cyan"
Write-Color "=========================================" "Cyan"
Write-Host ""

# Check Docker
if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Color "[X] Docker is not installed." "Red"
    Write-Host "    Download Docker Desktop: https://docs.docker.com/desktop/install/windows-install/"
    Read-Host "Press Enter to exit"
    exit 1
}

# Check subcommands
$composeCmd = $null
if (docker compose version 2>$null) { $composeCmd = "docker", "compose" -join " " }
elseif (Get-Command docker-compose -ErrorAction SilentlyContinue) { $composeCmd = "docker-compose" }
else {
    Write-Color "[X] Docker Compose not found." "Red"
    exit 1
}

# Parse args
if ($args[0] -in @("stop", "--stop", "down")) {
    Write-Color "[*] Stopping WebERP containers..." "Yellow"
    Invoke-Expression "$composeCmd down"
    Write-Color "[OK] Stopped." "Green"
    Read-Host "Press Enter to exit"
    exit 0
}
if ($args[0] -eq "--reset") {
    Write-Color "[!] This will DELETE all database data!" "Red"
    $confirm = Read-Host "    Type 'yes' to confirm"
    if ($confirm -ne "yes") { Write-Host "Aborted."; exit 0 }
    Invoke-Expression "$composeCmd down -v"
    Write-Color "[OK] Database removed, restarting..." "Green"
}

Write-Color "[*] Building and starting containers..." "Yellow"
Write-Host "    (First run may take several minutes — this is normal!)"
Write-Host ""
Invoke-Expression "$composeCmd up -d --build"

Write-Host ""
Write-Color "=========================================" "Green"
Write-Color " [OK] WebERP / WebPOS is starting up!" "Green"
Write-Color "=========================================" "Green"
Write-Host ""
Write-Host "    Web App (Nginx):      http://localhost:8080"
Write-Host "    Web App (Artisan):    http://localhost:8000"
Write-Host "    phpMyAdmin:           http://localhost:8081"
Write-Host ""
Write-Host "    Stop:                 .\start.ps1 stop"
Write-Host "    Reset (wipe DB):      .\start.ps1 --reset"
Write-Host "    Live logs:            docker logs -f weberp-app"
Write-Host ""

Write-Host "Opening browser in 5 seconds..."
Start-Sleep -Seconds 5
Start-Process "http://localhost:8080"
