<?php

use Psr\Container\ContainerInterface;
use Slim\App;

return static function (App $app): ContainerInterface {

    $container = $app->getContainer();

    $container['errorHandler'] = static function (ContainerInterface $c) {
        return static function ($request, $response, \Throwable $exception) use ($c) {
            /** @var \App\Renderer\ApiRenderer $formatter */
            $formatter = $c->get('renderer');
            $code = ($exception->getCode() >= 100 && $exception->getCode() < 600)
                ? $exception->getCode()
                : 500;
            $message = ($exception->getCode() >= 100 && $exception->getCode() < 600)
                ? $exception->getMessage()
                : 'Erreur interne';
            error_log(json_encode([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTrace()
            ]));
            return $formatter->error($code, $message);
        };
    };

    $container['notFoundHandler'] = static function (ContainerInterface $c) {
        return static function ($request, $response) use ($c) {
            /** @var \App\Renderer\ApiRenderer $formatter */
            $formatter = $c->get('renderer');
            return $formatter->error(404, 'Ressource non trouvée');
        };
    };

    return $container;
};
