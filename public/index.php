<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

function logvar($var): void
{
    $h = fopen('trace.log', 'a+');
    $maintenant = (new DateTime())->format(DATE_ATOM);
    $type = gettype($var);
    if ($type === 'object') {
        $type = get_class($var);
    }
    if ($type === 'ressource') {
        $var = (int)$var;
    }
    $varStr = json_encode($var);
    fwrite($h, sprintf('[%s] (%s) %s', $maintenant, $type, $varStr) . PHP_EOL);
    fclose($h);
}

require __DIR__ . '/../vendor/autoload.php';

session_start();
chdir(__DIR__ . '/..');

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Set up handlers
$handlers = require __DIR__ . '/../src/handlers.php';
$handlers($app);

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

// Run app
$app->run();
