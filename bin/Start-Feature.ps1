[CmdletBinding()]
param (
    [string]
    $FeatureName
)

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

function Test-SynchronizedWithOrigin {
    [string]$CurrentBranch = Get-CurrentBranch
    [string[]]$Diff = git diff --name-only "origin/$CurrentBranch"
    return $($Diff.Count -eq 0)
}

[string]$CurrentBranch = Get-CurrentBranch
[string]$DevBranch = Get-DevBranch

if (Test-StatusIsNotClear) {
    Write-Host `
        "Impossible de d'ajouter la feature. Des fichier ne sont pas commit"
    return;
}

[string]$FeatureFullName = "feature/$FeatureName"

if ($CurrentBranch -ne $DevBranch) {
    git checkout $DevBranch
}
if (-not (Test-SynchronizedWithOrigin)) {
    Write-Host `
        "Impossible de d'ajouter la feature. La branche dev n'est pas synchronisée avec le remote"
    return;
}
git pull
git branch $FeatureFullName
git checkout $FeatureFullName