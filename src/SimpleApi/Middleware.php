<?php
declare(strict_types=1);

namespace SimpleApi;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class de base pour les middlewares
 */
abstract class Middleware implements MiddlewareInterface
{
    /**
     * @param \DI\Container $container
     */
    public function __construct(protected Container $container)
    {
    }
}
