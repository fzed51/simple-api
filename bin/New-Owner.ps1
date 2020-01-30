[CmdletBinding()]
param ()

[string]$Description = Read-Host "Donnez une description du owner "
[string[]]$Items = @()

do {
    [string]$Item = Read-Host "Ajouter une ressource gérée par le owner. ([Entrée] pour arrêter) "
    $Item = $Item.Trim()
    if ($item -ne '') {
        $Items += $item
    }
} while ($item -ne '')

$owner = @{
    ref         = New-Guid;
    description = $Description;
    ressources  = $Items
}

$owner | ConvertTo-Json