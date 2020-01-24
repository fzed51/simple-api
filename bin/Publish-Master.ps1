[CmdletBinding()]
param ()

function Test-StatusIsNotClear {
    [int]$nbElement = (git status --porcelain).length
    if ($nbElement -gt 0) {
        return $true
    }
    return $false
}

function Get-CurrentBranch {
    return git symbolic-ref --short HEAD
}

function New-DbPrivate {
    Param (
    [string]$DbHost,
    [string]$DbUser,
    [string]$DbPass
    )
    $content = @"
<?php

if (!defined('DB_PROVIDER')) {
    define('DB_PROVIDER', 'mysql');
    define('DB_HOST', '$DbHost');
    define('DB_NAME', '$DbUser');
    define('DB_USER', '$DbUser');
    define('DB_PASS', '$DbPass');
}
"@
    New-Item './src/private.php' -Value $content
}

function New-DbMigrate {
    Param (
    [string]$DbHost,
    [string]$DbUser,
    [string]$DbPass
    )
    $content = @"
{
    "migration_directory": "./ressources/migration",
    "config_intern": {
        "provider": "mysql",
        "host": "$DbHost",
        "port": 3306,
        "name": "$DbUser",
        "user": "$DbUser",
        "pass": "$DbPass"
    }
}
"@
    New-Item './migration-config.json' -Value $content
}

if (Test-StatusIsNotClear) {
    Write-Host `
        "Impossible de publier l'API. Des fichiers ne sont pas commit"
    return;
}
    
[string]$CurrentBranch = Get-CurrentBranch
if ( $CurrentBranch -notlike 'master') {
    Write-Host "La branche courrante n'est pas la branche master."
    # return;
}
    
[string]$CurrentLocation = Get-Location
$Filezilla = Get-Command $env:ProgramFiles/FileZilla*/FileZilla.exe -ErrorAction Stop
$7z = Get-Command 7z.exe -ErrorAction Stop
    
git archive --format zip -0 -o .\publish.zip HEAD
$Archive = Get-Item .\publish.zip    

Set-Location ..
New-Item $(New-Guid) -type Directory | Select-Object -ExpandProperty Name | Set-Location
[string]$PublishLocation = Get-Location

&$7z x $($Archive.FullName)    
composer install -o --no-dev

Write-Host "Renseignement FTP" -ForegroundColor Green
$FtpUrl = Read-Host "host "
$FtpUser = Read-Host "user "
Write-Host "Renseignement de MySQL"
$MysqlHost = Read-Host "host "
$MySqlDbName = Read-Host "nom de la base "
$MySqlPassword = Read-Host "mot de passe de la base "

New-DbMigrate -DbHost $MysqlHost -DbUser $MySqlDbName -DbPass $MySqlPassword
New-DbPrivate -DbHost $MysqlHost -DbUser $MySqlDbName -DbPass $MySqlPassword

$ScriptMigration = Resolve-Path ".\vendor\bin/migrate.*" | Sort-Object -Descending | Select-Object -First 1 -ExpandProperty Path


&$Filezilla -l=ask "sftp://$FtpUser@$($FtpUrl):22" -a $PublishLocation

Write-Host "Attente de la fermeture de filezilla"
do {
    Start-Sleep -s 3
    $FilezillaProcess = Get-Process filezilla -ErrorAction SilentlyContinue
} while ($null -ne $FilezillaProcess)

Set-Location $CurrentLocation
Remove-Item $PublishLocation -Recurse -Force
