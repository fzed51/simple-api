<?php

namespace Tests\Functional;

use Slim\App;

/**
 * Test de base pour App
 */
class AppTestCase extends ControllerTestCase
{
    /**
     * Use middleware when running application?
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * Process the application given a request method and URI
     * @param string $requestMethod the request method (GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array<string,string> $requestHeader the request header (content-type, x-header, ...)
     * @param mixed $requestData the request data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Throwable
     */
    public function runApp(string $requestMethod, string $requestUri, $requestHeader = [], $requestData = null)
    {
        $request = $this->getRequest($requestMethod, $requestUri, $requestHeader, $requestData);

        // Set up a response object
        $response = $this->getResponse();

        // Use the application settings
        $settings = require __DIR__ . '/../../src/settings.php';

        // Instantiate the application
        $app = new App($settings);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../src/dependencies.php';
        $dependencies($app);
        $this->getContainer($app->getContainer());

        // Set up handlers
        $handlers = require __DIR__ . '/../../src/handlers.php';
        $handlers($app);

        // Register middleware
        if ($this->withMiddleware) {
            $middleware = require __DIR__ . '/../../src/middleware.php';
            $middleware($app);
        }

        // Register routes
        $routes = require __DIR__ . '/../../src/routes.php';
        $routes($app);

        // Process the application
        /* @var \Psr\Http\Message\ResponseInterface $response */
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }
}
