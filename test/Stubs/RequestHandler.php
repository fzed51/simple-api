<?php
declare(strict_types=1);

namespace Test\Stubs;

use Closure;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

/**
 * RequestHandler Stub
 */
class RequestHandler implements RequestHandlerInterface
{

    private Closure|null $handler = null;

    /**
     * Constructeur de RequestHandler Stub
     * @param null|callable(Request $r):Response $handler
     */
    public function __construct(callable|null $handler = null)
    {
        if ($handler !== null) {
            $this->handler = $handler(...);
        }
    }

    /**
     * @inheritDoc
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(Request $request): Response
    {
        if ($this->handler === null) {
            return (new ResponseFactory(200))->createResponse();
        }
        return ($this->handler)($request);
    }
}
