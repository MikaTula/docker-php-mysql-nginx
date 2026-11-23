$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

Write-Host "Stopping containers and deleting volumes..."
docker compose down -v --remove-orphans

$logs = Join-Path $PSScriptRoot "..\logs"
if (Test-Path $logs) {
    Get-ChildItem -Path $logs -Recurse -File -ErrorAction SilentlyContinue |
        Remove-Item -Force
    Write-Host "Log files removed."
}

Write-Host "Starting in the background..."
docker compose up -d --build
