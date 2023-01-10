<?php
declare(strict_types=1);

namespace SimpleApi\Handlers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

/**
 * Class de base pour les ErrorHandlers
 */
abstract class ErrorHandler implements ErrorHandlerInterface
{
    protected LoggerInterface|null $logger = null;

    /**
     * Constructeur
     * @param \Psr\Http\Message\ResponseFactoryInterface $responseFactory
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(
        protected ResponseFactoryInterface $responseFactory,
        protected ContainerInterface       $container
    ) {
    }

    /**
     * Returne un logger
     * @return \Psr\Log\LoggerInterface
     */
    protected function log(): LoggerInterface
    {
        try {
            if ($this->logger === null) {
                if (!$this->container->has(LoggerInterface::class)) {
                    $this->logger = new NullLogger();
                } else {
                    $this->logger = $this->container->get(LoggerInterface::class);
                }
            }
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            throw new RuntimeException("Impossible d'initialiser LoggerInterface : " . $e->getMessage());
        }
        return $this->logger;
    }
}
