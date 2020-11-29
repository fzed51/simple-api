[CmdletBinding()]
param ()

[string]$Description = Read-Host "Donnez une description du client "
[string[]]$Items = @()

do {
    [string]$Item = Read-Host "Ajouter une ressource gérée par le client. ([Entrée] pour arrêter) "
    $Item = $Item.Trim()
    if ($item -ne '') {
        $Items += $item
    }
} while ($item -ne '')

$client = @{
    ref         = New-Guid;
    description = $Description;
    ressources  = $Items
}

$client | ConvertTo-Json