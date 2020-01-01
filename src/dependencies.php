<?php

use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function (ContainerInterface $c) {
        return new \App\Renderer\ApiRenderer($c->get('response'));
    };


};
