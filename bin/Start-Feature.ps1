[CmdletBinding()]
param (
    [string]
    $FeatureName
)

[int]$nbElement = (git status --porcelain).length
if ($nbElement -gt 0) {
    Write-Host 
    "Impossible de d'ajouter la feature. Des fichier ne sont pas commit"
    return;
}

[string]$FeatureFullName = "feature/$FeatureName"
[string]$DevBranch = git branch | ForEach-Object { $_.trim(' *') } | Where-Object { $_ -like "dev*" } | Select-Object -First 1

git checkout $DevBranch
git pull
git branch $FeatureFullName
git checkout $FeatureFullName