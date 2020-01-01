<?php

use App\Middleware\OwnerMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function (ContainerInterface $c) {
        return new \App\Renderer\ApiRenderer($c->get('response'));
    };

    $container[OwnerMiddleware::class] = function (ContainerInterface $c) {
        $owners = json_decode(
            file_get_contents(__DIR__ . '/../ressources/owner.json'),
            true
        );
        return new OwnerMiddleware($c, $owners);
    };


};
