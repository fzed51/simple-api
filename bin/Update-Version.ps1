[CmdletBinding()]
param (
    [ValidateScript( {
            function test ($obj) {   
                $Properties = $obj.psobject.Properties.Name
                if ($Properties -inotcontains 'Major') { return $false }
                if ($Properties -inotcontains 'Minor') { return $false }
                if ($Properties -inotcontains 'Patch') { return $false }
                if ($Properties -inotcontains 'PreRelease') { return $false }
                return $true
            }
            return test $_
        })]
    [PSCustomObject]$Version,
    [Int]$Major,
    [Int]$Minor,
    [Int]$Patch,
    [string]$PreRelease,
    [ValidateSet("major", "minor", "patch")]
    [string]$Increment,
    [switch]$NoPreRelease,
    [switch]$Tag,
    [switch]$Quiet,
    [switch]$PassThru
)

function Write-Variable {
    param (
        [String]
        $Nom,
        $Variable
    )
    Write-Host ($nom + " : ") -ForegroundColor Cyan -NoNewline
    Write-Host $Variable
}

class Version {
    [Int]$Major
    [Int]$Minor
    [Int]$Patch
    [string]$PreRelease

    Version() {
        $this.Major = 0
        $this.Minor = 0
        $this.Patch = 0
        $this.PreRelease = ''
    }
    Version([Int]$Major) {
        $this.Major = $Major
        $this.Minor = 0
        $this.Patch = 0
        $this.PreRelease = ''
    }
    Version(
        [Int]$Major,
        [Int]$Minor
    ) {
        $this.Major = $Major
        $this.Minor = $Minor
        $this.Patch = 0
        $this.PreRelease = ''
    }
    Version(
        [Int]$Major,
        [Int]$Minor,
        [Int]$Patch
    ) {
        $this.Major = $Major
        $this.Minor = $Minor
        $this.Patch = $Patch
        $this.PreRelease = ''
    }
    Version(
        [Int]$Major,
        [Int]$Minor,
        [Int]$Patch,
        [string]$PreRelease
    ) {
        $this.Major = $Major
        $this.Minor = $Minor
        $this.Patch = $Patch
        $this.PreRelease = $PreRelease
    }

    [string] ToString() {
        [string]$out = $this.Major.ToString() + "." + $this.Minor.ToString() + "." + $this.Patch.ToString()
        if ($this.PreRelease -ne '') {
            $out = $out + "-" + $this.PreRelease 
        }
        return $out;
    }

    [string] ToJson() {
        return $this | ConvertTo-Json
    }    
}

$CurrentVersion = [Version]::new(0, 1)

if (Test-Path version.json) {
    try {
        $DataVersion = Get-Content version.json | ConvertFrom-Json
        $CurrentVersion.Major = $DataVersion.Major
        $CurrentVersion.Minor = $DataVersion.Minor
        $CurrentVersion.Patch = $DataVersion.Patch
        $CurrentVersion.PreRelease = $DataVersion.PreRelease
    }
    catch {
        if (-not $Quiet) {
            Write-Host "le fichier 'version.json' n'est pas valide" -ForegroundColor Red
        }
        $CurrentVersion = [Version]::new(0, 1)
    }
}

$StartVersion = $CurrentVersion.ToString()

if ($PSBoundParameters.ContainsKey('Version')) {
    $CurrentVersion.Major = $Version.Major
    $CurrentVersion.Minor = $Version.Minor
    $CurrentVersion.Patch = $Version.Patch
    $CurrentVersion.PreRelease = $Version.PreRelease
}
else {
    if ($PSBoundParameters.ContainsKey('Major')) {
        $CurrentVersion.Major = $Major
        $CurrentVersion.Minor = 0
        $CurrentVersion.Patch = 0
    }
    if ($PSBoundParameters.ContainsKey('Minor')) {
        $CurrentVersion.Minor = $Minor
        $CurrentVersion.Patch = 0
    }
    if ($PSBoundParameters.ContainsKey('Patch')) {
        $CurrentVersion.Patch = $Patch
    }
}

if ($PSBoundParameters.ContainsKey('Increment')) {
    switch ($Increment) {
        'major' { 
            $CurrentVersion.Major = $CurrentVersion.Major + 1
            $CurrentVersion.Minor = 0
            $CurrentVersion.Patch = 0
        }
        'minor' {
            $CurrentVersion.Minor = $CurrentVersion.Minor + 1
            $CurrentVersion.Patch = 0
        }
        'patch' {
            $CurrentVersion.Patch = $CurrentVersion.Patch + 1
        }
    }
}

if ($PSBoundParameters.ContainsKey('PreRelease')) {
    $CurrentVersion.PreRelease = $PreRelease
}

if ($NoPreRelease) {
    $CurrentVersion.PreRelease = ''
}

$EndVersion = $CurrentVersion.ToString()
if (-not $Quiet) {
    if ($StartVersion -eq $EndVersion) {
        Write-Variable "Version courrante" $CurrentVersion.ToString()
    }
    else {
        Write-Variable "Nouvelle version" $CurrentVersion.ToString()
    }
}

if ($Tag) {
    [string]$TagName = 'v' + $CurrentVersion.ToString()
    git tag $TagName
}

$CurrentVersion.ToJson() | Set-Content version.json

if ($PassThru) {
    return $CurrentVersion.ToJson() | ConvertFrom-Json
}