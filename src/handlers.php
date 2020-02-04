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
            $origin = $request->getServerParam('HTTP_ORIGIN', 'http://localhost');
            return $formatter->error($code, $message)->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Headers', 'content-type, Authorization')
                ->withHeader('Access-Control-Allow-Method', 'GET, POST, DELETE');
        };
    };

    $container['phpErrorHandler'] = static function (ContainerInterface $c) {
        return static function ($request, $response, \Throwable $exception) use ($c) {
            /** @var \App\Renderer\ApiRenderer $formatter */
            $formatter = $c->get('renderer');
            $code = 500;
            $message = 'Erreur interne';
            error_log(json_encode([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTrace()
            ]));
            $origin = $request->getServerParam('HTTP_ORIGIN', 'http://localhost');
            return $formatter->error($code, $message)->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Headers', 'content-type, Authorization')
                ->withHeader('Access-Control-Allow-Method', 'GET, POST, DELETE');
        };
    };

    $container['notFoundHandler'] = static function (ContainerInterface $c) {
        return static function ($request, $response) use ($c) {
            /** @var \App\Renderer\ApiRenderer $formatter */
            $formatter = $c->get('renderer');
            $origin = $request->getServerParam('HTTP_ORIGIN', 'http://localhost');
            return $formatter->error(404, 'Ressource non trouvée')
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Headers', 'content-type, Authorization')
                ->withHeader('Access-Control-Allow-Method', 'GET, POST, DELETE');
        };
    };

    return $container;
};
