<?php

namespace Tests\Functional;

use Slim\App;
use Slim\Http\Response;


/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
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
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array $requestHeader
     * @param mixed $requestData the request data
     * @return \Slim\Http\Response
     * @throws \Throwable
     */
    public function runApp(string $requestMethod, string $requestUri, $requestHeader = [], $requestData = null)
    {
        $request = $this->getRequest($requestMethod, $requestUri, $requestHeader, $requestData);

        // Set up a response object
        $response = new Response();

        // Use the application settings
        $settings = require __DIR__ . '/../../src/settings.php';

        // Instantiate the application
        $app = new App($settings);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../src/dependencies.php';
        $dependencies($app);
        $this->getContainer($app->getContainer() ?: null);

        // Register middleware
        if ($this->withMiddleware) {
            $middleware = require __DIR__ . '/../../src/middleware.php';
            $middleware($app);
        }

        // Register routes
        $routes = require __DIR__ . '/../../src/routes.php';
        $routes($app);

        // Process the application
        /* @var \Slim\Http\Response $response */
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }
}
