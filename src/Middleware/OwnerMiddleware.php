<?php


namespace App\Middleware;


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
        $authorization = $request->getServerParam('HTTP_AUTHORIZATION');
        $regex = '/^bearer ([a-f0-9\-]+)$/i';
        if (preg_match($regex, $authorization, $matches) === false) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $owner = $this->findOwner($matches[1]);
        if ($owner === null) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $request = $request->withAttribute('owner', $owner);
        return $next($request, $response);
    }
}