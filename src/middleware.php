<?php

use App\Middleware\CorsMiddleware;
use App\Middleware\OwnerMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(OwnerMiddleware::class);
    $app->add(CorsMiddleware::class);
    // e.g: $app->add(new \Slim\Csrf\Guard);
};
