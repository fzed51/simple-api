[CmdletBinding()]
param ()

[string]$Description = Read-Host "Donnez une description du owner "
[string[]]$Items = @()

do {
    [string]$Item = Read-Host "Ajouter une ressource g?r?e par le owner. ([Entr?e] pour arr?ter) "
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