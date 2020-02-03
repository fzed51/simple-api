$p1 = Start-Process php -ArgumentList @( '-S', '0.0.0.0:8008', '-t', 'public', '-d', 'xdebug.default_enable=1') -PassThru
$p2 = Start-Process php -ArgumentList  @('-S','0.0.0.0:8009','-t','tests/Client') -WindowStyle Minimized -PassThru

Read-Host "Appuyer sur [Entrée] pour continuer"

$p1 | Stop-Process
$p2 | Stop-Process

# xdebug.trace_output_dir = trace
# xdebug.remote_enable = 0
# xdebug.profiler_output_dir = profile
# xdebug.profiler_output_name = cachegrind.out.%t-%s
# xdebug.trace_output_name = %t-%s.trace
# xdebug.remote_connect_back = 1
# xdebug.remote_autostart = 0
# xdebug.default_enable = 0
# xdebug.collect_params = 2
# xdebug.collect_vars = 3
# zend_extension = "php_xdebug-2.7.2-7.3-vc15-x86_64.dll"
# xdebug.profiler_enable = 0
# xdebug.auto_trace = 0