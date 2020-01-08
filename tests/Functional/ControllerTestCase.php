<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 07/01/2020
 * Time: 17:58
 */

namespace Tests\Functional;

use App\Renderer\ApiRenderer;
use DomainException;
use PDO;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class ControllerTestCase extends ActionTestCase
{

    /**
     * @param \Slim\Container|null $container
     * @return \Slim\Container
     */
    protected function getContainer(Container $container = null): Container
    {
        if (null === $container) {
            $container = new Container();
        }
        $self = $this;

        $container[PDO::class] = static function () use ($self) {
            return $self->getPDO();
        };

        $container['renderer'] = static function (ContainerInterface $c) {
            return new ApiRenderer($c->get('response'));
        };

        return $container;
    }

    /**
     * @param string $method -- GET, POST, DELETE, PUT, PATCH, OPTION
     * @param string $uri
     * @param array $header
     * @param $body
     * @return \Slim\Http\Request
     */
    protected function getRequest(string $method, string $uri, array $header = [], $body = null): Request
    {
        // CREATION DE LA REQUETE DE BASE
        $m = strtoupper($method);
        if (!in_array($m, ['GET', 'POST', 'DELETE', 'PUT', 'PATCH', 'OPTION'])) {
            throw new RuntimeException("La méthode $method n'est pas valide");
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

    /**
     * @param \Slim\Http\Response $expectedResponse
     */
    protected function assertSuccessResponse($expectedResponse): void
    {
        // la source doit être une reponse
        $this->assertInstanceOf(Response::class, $expectedResponse);
        $body = (string)$expectedResponse->getBody();
        // le body de la source doit être un json valide
        $this->assertJson($body);
        $data = json_decode($body, false);
        // la donnee doit être une reponse de l'api valide
        $this->assertIsObject($data);
        $this->assertObjectHasAttribute('success', $data);
        $this->assertObjectHasAttribute('data', $data);
        $this->assertObjectHasAttribute('error', $data);
        // la reponse doit être un succes
        $this->assertTrue($data->success);
    }

    /**
     * @param \Slim\Http\Response $response
     * @return mixed
     */
    protected function getDataResponse(Response $response)
    {
        $body = (string)$response->getBody();
        $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
        if (!isset($data->data)) {
            throw new DomainException("la réponse n'est pas valide car elle ne contient pas de propriété 'data'");
        }
        return $data->data;
    }

}