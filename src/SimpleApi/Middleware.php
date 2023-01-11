<?php
declare(strict_types=1);

namespace SimpleApi;

use DI\Container;

/**
 * Class de base pour les middlewares
 */
abstract class Middleware
{
    /**
     * @param \DI\Container $container
     */
    public function __construct(protected Container $container)
    {
    }
}
