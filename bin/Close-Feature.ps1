[CmdletBinding()]
param (
    [switch]$DeleteBranch
)

[int]$nbElement = (git status --porcelain).length
if ($nbElement -gt 0) {
    Write-Host "Impossible de cloturer la feature. Des fichier ne sont pas commit" -ForegroundColor red
    return;
}


[string]$DevBranch = git branch `
| ForEach-Object { $_.trim(' *') } `
| Where-Object { $_ -like "dev*" }
[string]$CurrentBranch = git symbolic-ref --short HEAD

$CommitMessage = git log --format="%s" "$DevBranch..$CurrentBranch"
$mergeMessage = "*** Merge $CurrentBranch into $DevBranch ***`n`n$CommitMessage"

git checkout $DevBranch
git pull
git merge --no-ff --no-commit $CurrentBranch
git commit -m $mergeMessage

if ($DeleteBranch) {
    git branch -D  $CurrentBranch
}