[CmdletBinding()]
param (
    [switch]$DeleteBranch
)

[int]$nbElement = (git status --porcelain).length

if ($nbElement -gt 0) {
    Write-Host 
    "Impossible de cloturer la feature. Des fichier ne sont pas commit"
    return;
}


[string]$DevBranch = git branch `
| ForEach-Object { $_.trim(' *') } `
| Where-Object { $_ -like "dev*" }
[string]$CurrentBranch = git symbolic-ref --short HEAD

[array]$CommitMessage = @(git log --format="%s" "$DevBranch..$CurrentBranch")
[array]::Reverse($CommitMessage)
[string]$StrCommitMessage = $CommitMessage -join "`n"
[string]$MergeMessage = "*** Merge $CurrentBranch into $DevBranch ***`n`n$StrCommitMessage"

git checkout $DevBranch
git merge --no-ff --no-commit $CurrentBranch
git commit -m $mergeMessage

if ($DeleteBranch) {
    git branch -D  $CurrentBranch
}