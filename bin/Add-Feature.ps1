[CmdletBinding()]
param (
    [string]
    $FeatureName
)

[string]$FeatureFullName = "feature/$FeatureName"

git add --all
git stash
git checkout develop
git pull
git branch $FeatureFullName
git checkout $FeatureFullName
git stash pop