<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use SimpleApi\Handlers\DefaultErrorHandler;
use SimpleApi\Handlers\HttpErrorHandler;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) { // Should be set to true in production
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/./settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/./dependencies.php';
$dependencies($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Register routes
$routes = require __DIR__ . '/./routes.php';
$routes($app);

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();

/**
// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, false);
register_shutdown_function($shutdownHandler);
*/

// Add Routing Middleware
$app->addRoutingMiddleware();

// Create Errors Handlers
$httpErrorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
$defaultErrorHandler = new DefaultErrorHandler($callableResolver, $responseFactory);
// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(false, true, true);
$errorMiddleware->setErrorHandler(HttpException\HttpException::class, $httpErrorHandler);
$errorMiddleware->setDefaultErrorHandler($defaultErrorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);