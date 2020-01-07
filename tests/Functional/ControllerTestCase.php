<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 07/01/2020
 * Time: 17:58
 */

namespace Tests\Functional;

use RuntimeException;
use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class ControllerTestCase extends ActionTestCase
{


    protected function getContainer(Container $container = null): Container
    {
        if (null === $container) {
            $container = new Container();
        }
        $self = $this;
        $container[\PDO::class] = static function (Container $c) use ($self) {
            return $self->getPDO();
        };
        return $container;
    }

    /**
     * @param string $methode -- GET, POST, DELETE, PUT, PATCH, OPTION
     * @param string $uri
     * @param array $header
     * @param $body
     * @return \Slim\Http\Request
     */
    protected function getRequest(string $methode, string $uri, array $header = [], $body = null): Request
    {
        // CREATION DE LA REQUETE DE BASE
        $m = strtoupper($methode);
        if (!in_array($m, ['GET', 'POST', 'DELETE', 'PUT', 'PATCH', 'OPTION'])) {
            throw new RuntimeException("La méthode $methode n'est pas valide");
        }
        $env = Environment::mock([
            'REQUEST_METHOD' => $m,
            'REQUEST_URI' => $uri
        ]);
        $request = Request::createFromEnvironment($env);
        // MODIFICATION DU HEADER
        if (!empty($header)) {
            foreach ($header as $key => $value) {
                $request = $request->withHeader($key, $value);
            }
        }
        $owner = $this->getOwner();
        $request = $request->withAttribute('owner', $owner);
        // ECRITURE DU BODY
        if (null !== $body) {
            if (!is_string($body)) {
                $request = $request->withHeader('content-type', 'application/json');
                $body = json_encode($body);
            }
            $b = $request->getBody();
            $b->write($body);
        }
        return $request;
    }

    protected function getResponse(): Response
    {
        return new Response();
    }

}