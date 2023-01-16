<?php
declare(strict_types=1);

namespace SimpleApi\Middlewares;

use DI\Definition\ValueDefinition;
use HttpException\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleApi\Elements\Entity;
use SimpleApi\Middleware;

/**
 * Class pour récupérer l'entity dans le header
 */
class EntityMiddleware extends Middleware
{

    /**
     * @throws \HttpException\ForbiddenException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader('X-APIKEY')) {
            throw new ForbiddenException("L'APIKEY n'est pas présente.");
        }
        $apiKey = implode('', $request->getHeader('X-APIKEY'));
        $entities = $this->container->get('Entites');
        if (!array_key_exists($apiKey, $entities)) {
            throw new ForbiddenException("L'APIKEY n'est pas valide.");
        }
        $this->container->set(Entity::class, new ValueDefinition($entities[$apiKey]));
        return $handler->handle($request);
    }
}
