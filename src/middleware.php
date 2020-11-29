<?php

use App\Middleware\CorsMiddleware;
use App\Middleware\ClientMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(ClientMiddleware::class);
    $app->add(CorsMiddleware::class);
    // e.g: $app->add(new \Slim\Csrf\Guard);
};
