<?php
declare(strict_types=1);

namespace Test;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * Class de base pour tester les controleurs
 */
class ControllerTestCase extends ActionTestCase
{
    /**
     * @param int $expectedCode
     * @param mixed $response
     * @param string $message
     * @return mixed
     */
    protected static function assertResponseAndReturnData(int $expectedCode, mixed $response, string $message = ""
    ): mixed {
        self::assertInstanceOf(Response::class, $response);
        self::assertEquals($expectedCode, $response->getStatusCode());
        try {
            /** @var Response $response */
            $body = (string)$response->getBody();
            return json_decode($body, false, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $ex) {
            self::fail("Impossible de décoder les données de la réponsqe : " . $ex::class . ", " . $ex->getMessage());
        }
    }

    /**
     * Fabrique une requete Get
     * @param string $url
     * @param array<string,string|string[]> $headers
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function makeGetRequest(string $url, array $headers = []): Request
    {
        $factory = new ServerRequestFactory();
        $request = $factory->createServerRequest('GET', $url, $_SERVER);
        foreach ($headers as $headerName => $headerValue) {
            $request->withHeader($headerName, $headerValue);
        }
        return $request;
    }

    /**
     * Fabrique une reponse
     */
    protected function makeResponse(int $codeStatus = 200): Response
    {
        return (new ResponseFactory())->createResponse($codeStatus);
    }
}
