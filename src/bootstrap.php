<?php
declare(strict_types=1);

use SimpleApi\Api;

require __DIR__ . '/../vendor/autoload.php';

$api = new Api(
    __DIR__ . '/../config/settings.php',
    __DIR__ . '/./dependencies.php',
    __DIR__ . '/./routes.php'
    //, __DIR__ . '/../var/cache'
);

$api->run();
