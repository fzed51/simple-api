<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->success(['prenom' => 'fabien']);
    });

    $app->post(' / ', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->success($request->getParsedBody());
    });
};
