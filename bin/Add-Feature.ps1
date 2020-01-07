[CmdletBinding()]
param (
    [Parameter(Mandatory = $true)]
    [ValidateNotNullOrEmpty()]
    [string]
    $FeatureName
)

[int]$nbElement = (git status --porcelain).length
if ($nbElement -gt 0) {
    Write-Host "Impossible de d'ajouter la feature. Des fichier ne sont pas commit" -ForegroundColor red
    return;
}

[string]$FeatureFullName = "feature/$FeatureName"

git checkout develop
git pull
git branch $FeatureFullName
git checkout $FeatureFullName