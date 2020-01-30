[CmdletBinding()]
param ()

function Get-CurrentBranch {
    return git symbolic-ref --short HEAD
}

function Test-StatusIsNotClear {
    [int]$nbElement = (git status --porcelain).length
    if ($nbElement -gt 0) {
        return $true
    }
    return $false
}

function Get-ScriptVersion {
    [string]$ScriptDirectory = Split-Path -Path $Script:MyInvocation.MyCommand.Definition -Parent
    [string]$ScriptVersion = Join-Path $ScriptDirectory "Update-Version.ps1"
    return $ScriptVersion 
}

function Get-DevBranch {
    return git branch `
    | ForEach-Object { $_.trim(' *') } `
    | Where-Object { $_ -like "dev*" } `
    | Select-Object -First 1
}

if (Test-StatusIsNotClear) {
    Write-Host `
        "Impossible de cloturer la feature. Des fichier ne sont pas commit"
    return;
}

[string]$CurrentBranch = Get-CurrentBranch
if ( $CurrentBranch -notlike 'release/*') {
    Write-Host "La branche courrante n'est pas une branche de release."
    return;
}

[string]$ScriptVersion = Get-ScriptVersion


$CurrentVersion = &$ScriptVersion -PassThru -Quiet
git checkout master
git merge --no-ff --no-commit $CurrentBranch
&$ScriptVersion -Version $CurrentVersion -Quiet
&$ScriptVersion -NoPreRelease
git add version.json
[string]$strVersion = "v" + $CurrentVersion.Major + "." + $CurrentVersion.Minor + "." + $CurrentVersion.Patch
git commit -m "=== Publication de $strVersion ==="
&$ScriptVersion -Tag

[string]$DevBranch = Get-DevBranch
git checkout $CurrentBranch
[int]$NbLog = (git log --oneline "develop..HEAD" `
    | Measure-Object `
    | Select-Object -ExpandProperty Count)
git checkout $DevBranch
$CurrentDevVersion = &$ScriptVersion -PassThru -Quiet
git merge --no-ff --no-commit $CurrentBranch
&$ScriptVersion -Version $CurrentDevVersion -Quiet
if ($NbLog -gt 1) {
    &$ScriptVersion  -Increment Patch
}
git add version.json
git commit -m "=== Publication de $strVersion ==="