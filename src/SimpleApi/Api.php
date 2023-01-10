<?php
declare(strict_types=1);

namespace SimpleApi;

use DI\ContainerBuilder;
use Exception;
use HttpException\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use SimpleApi\Handlers\DefaultErrorHandler;
use SimpleApi\Handlers\HttpErrorHandler;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;

/**
 * Api
 */
class Api
{
    readonly public ErrorMiddleware $errorMiddleware;
    private App $app;

    /**
     * Constructeur de l'Api
     * @param string $settingsHandler fichier contenant le settings handler
     * @param string $dependenciesHandler fichier contenant le dependencies handler
     * @param string $routesHandler fichier contenant le routes handler
     * @param string $cacheDirectory le dossier où stocker le cache
     */
    public function __construct(
        string $settingsHandler,
        string $dependenciesHandler,
        string $routesHandler,
        string $cacheDirectory = ""
    ) {
        $containerBuilder = new ContainerBuilder();
        if (!empty($cacheDirectory)) { // true in production
            $containerBuilder->enableCompilation($cacheDirectory);
        }
        $settings = require $settingsHandler;
        $settings($containerBuilder);
        $dependencies = require $dependenciesHandler;
        $dependencies($containerBuilder);
        try {
            $container = $containerBuilder->build();
        } catch (Exception) {
            throw new RuntimeException("Impossible d'initialiser le container");
        }
        AppFactory::setContainer($container);
        $this->app = AppFactory::create();
        $callableResolver = $this->app->getCallableResolver();
        $routes = require $routesHandler;
        $routes($this->app);
        $this->app->addRoutingMiddleware();
        $responseFactory = $this->app->getResponseFactory();
        $httpErrorHandler = new HttpErrorHandler($responseFactory, $container);
        $defaultErrorHandler = new DefaultErrorHandler($responseFactory, $container);
        $this->errorMiddleware = $this->app->addErrorMiddleware(false, true, true);
        $this->errorMiddleware->setErrorHandler(HttpException::class, $httpErrorHandler);
        $this->errorMiddleware->setDefaultErrorHandler($defaultErrorHandler);
    }

    /**
     * Tester l'Api
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function test(ServerRequestInterface $request): ResponseInterface
    {
        return $this->app->handle($request);
    }

    /**
     * Executer l'API
     * @return void
     */
    public function run(): void
    {
        $this->app->run();
    }
}
