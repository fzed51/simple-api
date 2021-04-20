<?php
/**
 * User: Fabien Sanchez
 * Date: 07/01/2020
 * Time: 17:58
 */

namespace Tests\Functional;

use App\Middleware\ClientMiddleware;
use App\Renderer\ApiRenderer;
use DomainException;
use InstanceResolver\ResolverClass;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class ControllerTestCase extends ActionTestCase
{

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     * @return \Psr\Container\ContainerInterface
     */
    protected function getContainer(ContainerInterface $container = null): ContainerInterface
    {
        if (null === $container) {
            $container = new Container();
        }

        $self = $this;

        $container[PDO::class] = static function () use ($self) {
            return $self->getPDO();
        };
        $container['resolve'] = static function (ContainerInterface $c) {
            return new ResolverClass($c);
        };
        $container['renderer'] = static function (ContainerInterface $c) {
            return new ApiRenderer($c->get('response'));
        };
        $container[ClientMiddleware::class] = static function (ContainerInterface $c) use ($self) {
            return new ClientMiddleware($c, $self->getClients());
        };
        return $container;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array<string, string> $header
     * @param mixed $body
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
        // ECRITURE DU BODY
        if (null !== $body) {
            if (!is_string($body)) {
                $request = $request->withHeader('content-type', 'application/json');
                $body = json_encode($body);
            }
            $b = $request->getBody();
            $b->write($body);
            $request = $request->withBody($b);
        }
        return $request;
    }

    /**
     * @param string $method -- GET, POST, DELETE, PUT, PATCH, OPTION
     * @param string $uri
     * @param array<string, string> $header
     * @param mixed $body
     * @return \Slim\Http\Request
     */
    protected function getRequestWithClient(string $method, string $uri, array $header = [], $body = null): Request
    {
        $request = $this->getRequest($method, $uri, $header, $body);
        $client = $this->getClient();
        return $request->withAttribute('client', $client);
    }

    protected function getResponse(): Response
    {
        return new Response();
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $expectedResponse
     */
    protected function assertSuccessResponse(ResponseInterface $expectedResponse): void
    {
        // la source doit être une reponse
        self::assertInstanceOf(Response::class, $expectedResponse);
        $body = (string)$expectedResponse->getBody();
        // le body de la source doit être un json valide
        self::assertJson($body);
        $data = json_decode($body, false);
        // la donnee doit être une reponse de l'api valide
        self::assertIsObject($data);
        self::assertObjectHasAttribute('success', $data);
        self::assertObjectHasAttribute('data', $data);
        self::assertObjectHasAttribute('error', $data);
        self::assertIsBool($data->success);
        // la reponse doit être un succes
        $messageErr = (!$data->success) ? '(' . (string)$data->error->status . ') ' . $data->error->message : '';
        self::assertTrue(
            $data->success,
            "La réponse n'est pas de type 'success' elle a retournée une erreur : " . $messageErr
        );
    }


    /**
     * @param \Psr\Http\Message\ResponseInterface $expectedResponse
     * @param int $code donner un code pour verifier le code de la réponse
     */
    protected function assertErrorResponse(ResponseInterface $expectedResponse, int $code = -1): void
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
        // la reponse doit être une error
        $this->assertFalse($data->success);
        if ($code > 0) {
            $this->assertEquals($code, $data->error->status);
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return string
     */
    protected function gerRawDataResponse(ResponseInterface $response): string
    {
        return (string)$response->getBody();
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    protected function getDataResponse(ResponseInterface $response)
    {
        $body = $this->gerRawDataResponse($response);

        $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
        if (!isset($data->data)) {
            throw new DomainException("(getDataResponse) la réponse n'est pas valide car elle ne contient pas de propriété 'data'");
        }
        return $data->data;
    }

    protected function getClientBearerToken(): string
    {
        return 'Bearer ' . $this->getClient()->getRef();
    }
}
