[CmdletBinding()]
param (
)

[int]$nbElement = (git status --porcelain).length

if ($nbElement -gt 0) {
    Write-Host 
    "Impossible de cloturer la feature. Des fichier ne sont pas commit"
    return;
}


[string]$DevBranch = git branch | % { $_.trim(' *') } | ? { $_ -like "dev*" }
[string]$CurrentBranch = git symbolic-ref --short HEAD

$CommitMessage = git log --format="%s" "$DevBranch..$CurrentBranch"
$mergeMessage = "*** Merge $CurrentBranch into $DevBranch ***`n`n$CommitMessage"

git checkout $DevBranch
git merge --no-ff --no-commit $CurrentBranch
git commit -m $mergeMessage