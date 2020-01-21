[CmdletBinding()]
param ()

function Test-StatusIsNotClear {
    [int]$nbElement = (git status --porcelain).length
    if ($nbElement -gt 0) {
        return $true
    }
    return $false
}

function Get-DevBranch {
    return git branch `
    | ForEach-Object { $_.trim(' *') } `
    | Where-Object { $_ -like "dev*" } `
    | Select-Object -First 1
}

function Get-CurrentBranch {
    return git symbolic-ref --short HEAD
}

function Read-YesNo {
    param (
        [string]$Question,
        [switch]$NoDefault
    )
    Write-Host $Question -NoNewline -ForegroundColor White
    Write-Host ' [' -NoNewline -ForegroundColor White
    if($NoDefault){
        Write-Host 'yes' -NoNewline -ForegroundColor Gray
    }else{
        Write-Host 'yes' -NoNewline -ForegroundColor Yellow
    }
    Write-Host '/' -NoNewline -ForegroundColor White
    if($NoDefault){
        Write-Host 'no' -NoNewline -ForegroundColor Yellow
    }else{
        Write-Host 'no' -NoNewline -ForegroundColor Gray
    }
    Write-Host ']' -ForegroundColor White
    do {
        $reponse = Read-Host
        if ($reponse -eq ''){
            if($NoDefault){
                $reponse = 'n'
            } else {
                $reponse = 'y'
            }
        }
        $reponse = $reponse.ToLower()[0]
        if($reponse -eq 'y'){
            return $true
        }
        if($reponse -eq 'n'){
            return $false
        }
        Write-Host "Je n'ai pas compris la réponse"
    } while ($true)
}

function Get-ScriptVersion {
    [string]$ScriptDirectory = Split-Path -Path $Script:MyInvocation.MyCommand.Definition -Parent
    [string]$ScriptVersion = Join-Path $ScriptDirectory "Update-Version.ps1"
    return $ScriptVersion 
}

if (Test-StatusIsNotClear) {
    Write-Host "Impossible de d'ajouter la feature. Des fichier ne sont pas commit"
    # return;
}

[string]$DevBranch = Get-DevBranch
[string]$CurrentBranch = Get-CurrentBranch

if ( $DevBranch -ne $CurrentBranch) {
    if (-not (Read-YesNo "Attention vous n'êtes pas sur la abranche de dev! Voulez-vous continuer ? " -NoDefault)) {
        return;
    } else {
        git checkout $DevBranch
    }
}

$ScriptVersion = Get-ScriptVersion
$CurrentVersion = &$ScriptVersion -Quiet -PassThru
$ReleaseName = "v" + $CurrentVersion.Major + "-" + $CurrentVersion.Minor + "-" + $CurrentVersion.Patch
$ReleaseFullName = "release/" + $ReleaseName
&$ScriptVersion -PreRelease rc
git add .
git stash
git checkout -B $ReleaseFullName
git stash pop
git add .
git commit -m "*** debut de $ReleaseName release"