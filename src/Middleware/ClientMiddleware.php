<?php


namespace App\Middleware;

use App\Entity\Client;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ClientMiddleware extends Middleware
{

    /**
     * @var array<string,mixed>[]
     */
    private $client;

    /**
     * ClientMiddleware constructor.
     * @param \Psr\Container\ContainerInterface $container
     * @param array<string,mixed>[] $client
     */
    public function __construct(ContainerInterface $container, array $client)
    {
        $this->client = $client;
        parent::__construct($container);
    }

    /**
     * @param string $client
     * @return array<string,mixed>|null
     */
    private function findClient(string $client): ?array
    {
        $i = array_search(
            $client,
            array_column($this->client, 'ref'),
            true
        );
        if ($i === false) {
            return null;
        }
        return $this->client[$i];
    }

    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        /* @var string[]|null $authorization */
        $authorization = $request->getHeader('HTTP_AUTHORIZATION');
        if (empty($authorization)) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $authorization = $authorization[0];
        $regex = '/^bearer ([a-f0-9\-]+)$/i';
        $result = preg_match($regex, $authorization, $matches);
        if ($result === false || $result === 0) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $client = $this->findClient($matches[1]);
        if ($client === null) {
            return $this->container->get('renderer')->error(401, "Vous n'êtes pas autorisé à accéder à cette API.");
        }
        $client = new Client($client);
        $request = $request->withAttribute('client', $client);
        return $next($request, $response);
    }
}
