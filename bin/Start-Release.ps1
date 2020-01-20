[CmdletBinding()]
param (
    [Parameter()]
    [TypeName]
    $ParameterName
)

[string]$DevBranch = git branch `
| ForEach-Object { $_.trim(' *') } `
| Where-Object { $_ -like "dev*" } `
| Select-Object -First 1
[string]$CurrentBranch = git symbolic-ref --short HEAD

if ( $DevBranch -ne $CurrentBranch ) {
    Write-Host -ForegroundColor White -BackgroundColor Red `
    "Commencer un Release à partir de la branche de dev."
    return;
}

[int]$nbElement = (git status --porcelain).length
if ($nbElement -gt 0) {
    Write-Host -ForegroundColor White -BackgroundColor Red `
    "Impossible de d'ajouter la feature. Des fichier ne sont pas commit"
    return;
}