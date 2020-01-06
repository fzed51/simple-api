<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return static function (App $app) {
    $container = $app->getContainer();

    $app->post('/{ressource}', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->success($request->getParsedBody());
    });
    $app->get('/{ressource}', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->success(['prenom' => 'fabien']);
    });
    $app->post('/{ressource}/{ref}', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->success($request->getParsedBody());
    });
    $app->get('/{ressource}/{ref}', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->success(['prenom' => 'fabien']);
    });
    $app->delete('/{ressource}/{ref}', function (Request $request, Response $response, array $args) use ($container) {
        return $container->get('renderer')->success(['prenom' => 'fabien']);
    });


    //error_log(var_export($container->get('router')->getRoutes(), true));
};
