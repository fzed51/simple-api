<?php
declare(strict_types=1);

namespace Test;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Test\Stubs\RequestHandler;

/**
 * Class de test de base pour les Middleware
 */
class MiddlewareTestCase extends ControllerTestCase
{
    /**
     * Fabrique un RequestHandler
     * @param null|callable(Request $r):Response $handler
     * @return \Test\Stubs\RequestHandler
     */
    protected function makeRequestHandler(callable|null $handler = null): RequestHandlerInterface
    {
        return new RequestHandler($handler);
    }
}
