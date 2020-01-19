<?php


namespace App\Middleware;


use App\Owner;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class OwnerMiddleware extends Middleware
{

    private $owners;

    public function __construct(ContainerInterface $container, array $owners)
    {
        $this->owners = $owners;
        parent::__construct($container);
    }

    private function findOwner($owner): ?array
    {
        $i = array_search(
            $owner,
            array_column($this->owners, 'ref'),
            true
        );
        if ($i === false) {
            return null;
        }
        return $this->owners[$i];
    }

    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $authorization = $request->getHeader('HTTP_AUTHORIZATION');
        if (null === $authorization) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $authorization = $authorization[0];
        $regex = '/^bearer ([a-f0-9\-]+)$/i';
        $result = preg_match($regex, $authorization, $matches);
        if ($result === false || $result === 0) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $owner = $this->findOwner($matches[1]);
        if ($owner === null) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $owner = new Owner($owner);
        $request = $request->withAttribute('owner', $owner);
        return $next($request, $response);
    }
}