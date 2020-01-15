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
[string]$DevBranch = git branch | % { $_.trim(' *') } | ? { $_ -like "dev*" }

git checkout $DevBranch
git pull
git branch $FeatureFullName
git checkout $FeatureFullName