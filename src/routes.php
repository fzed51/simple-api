<?php

use Slim\App;

return static function (App $app) {
    $app->post('/{ressource}', \App\RessourceController::class . ':create');
    $app->get('/{ressource}', \App\RessourceController::class . ':getAll');
    $app->post('/{ressource}/{ref}', \App\RessourceController::class . ':update');
    $app->get('/{ressource}/{ref}', \App\RessourceController::class . ':getOne');
    $app->delete('/{ressource}/{ref}', \App\RessourceController::class . ':delete');
};
