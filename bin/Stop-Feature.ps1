[CmdletBinding()]
param (
    [ValidateSet('breaking_change', 'feature', 'fix', 'none')]
    [string]$TypeUpdate = 'feature',
    [switch]$DeleteBranch
)

[int]$nbElement = (git status --porcelain).length

if ($nbElement -gt 0) {
    Write-Host 
    "Impossible de cloturer la feature. Des fichier ne sont pas commit"
    return;
}

[string]$ScriptDirectory = Split-Path -Path $MyInvocation.MyCommand.Definition -Parent
[string]$ScriptVersion = Join-Path $ScriptDirectory "Update-Version.ps1"

[string]$DevBranch = git branch `
| ForEach-Object { $_.trim(' *') } `
| Where-Object { $_ -like "dev*" }
[string]$CurrentBranch = git symbolic-ref --short HEAD

[array]$CommitMessage = @(git log --format="%s" "$DevBranch..$CurrentBranch")
[array]::Reverse($CommitMessage)
[string]$StrCommitMessage = $CommitMessage -join "`n"
[string]$MergeMessage = "*** Merge $CurrentBranch into $DevBranch ***`n`n$StrCommitMessage"

git checkout $DevBranch


$CurrentDevVersion = &$ScriptDirectory -PassThru

git merge --no-ff --no-commit $CurrentBranch
git commit -m $mergeMessage

if ($DeleteBranch) {
    git branch -D  $CurrentBranch
    #TODO : detecter la branche du remote
    #TODO : supprimer la branche du remote
}

switch ($TypeUpdate) {
    'breaking_change' {
        &$ScriptVersion -Major $CurrentDevVersion.Major -Minor $CurrentDevVersion.Minor -Patch $CurrentDevVersion.Patch -PreRelease 'dev' -Increment major
    }
    'feature' {  
        &$ScriptVersion -Major $CurrentDevVersion.Major -Minor $CurrentDevVersion.Minor -Patch $CurrentDevVersion.Patch -PreRelease 'dev' -Increment minor
    }
    'fix' {  
        &$ScriptVersion -Major $CurrentDevVersion.Major -Minor $CurrentDevVersion.Minor -Patch $CurrentDevVersion.Patch -PreRelease 'dev' -Increment patch
    }
    'none' {
        &$ScriptVersion -Major $CurrentDevVersion.Major -Minor $CurrentDevVersion.Minor -Patch $CurrentDevVersion.Patch -PreRelease 'dev' 
    }
}
