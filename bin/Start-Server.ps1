$p1 = Start-Process php -ArgumentList @( '-S', '0.0.0.0:8008', '-t', 'public') -WindowStyle Minimized -PassThru
$p2 = Start-Process php -ArgumentList  @('-S','0.0.0.0:9009','-t','tests/Client') -WindowStyle Minimized -PassThru

Read-Host "Appuyer sur [Entrée] pour continuer"

$p1 | Stop-Process
$p2 | Stop-Process