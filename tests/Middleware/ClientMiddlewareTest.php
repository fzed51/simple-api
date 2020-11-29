<?php

namespace Tests\App\Middleware;

use App\Middleware\ClientMiddleware;
use App\Entity\Client;
use Slim\Http\Request;
use Slim\Http\Response;
use Tests\Functional\ControllerTestCase;

/**
 * test de ClientMiddleware
 * @package Tests\App\Middleware
 */
class ClientMiddlewareTest extends ControllerTestCase
{
    /**
     * test de __construct
     */
    public function test__construct(): void
    {
        $mw = new ClientMiddleware($this->getContainer(), $this->getClients());
        $this->assertInstanceOf(ClientMiddleware::class, $mw);
    }

    /**
     * test de __invoke
     */
    public function test__invoke(): void
    {
        $self = $this;
        $request = $this->getRequest(
            'GET',
            'item',
            [
                'HTTP_AUTHORIZATION' => $this->getClientBearerToken()
            ]
        );
        $response = $this->getResponse();
        $run = 0;
        $next = static function (Request $request, Response $response) use ($self, &$run): Response {
            $run++;
            $client = $request->getAttribute('client');
            $self->assertInstanceOf(Client::class, $client);
            return $response;
        };
        $mw = new ClientMiddleware($this->getContainer(), $this->getClients());
        $mw($request, $response, $next);
        $this->assertEquals(1, $run);
    }

    /**
     * test de __badInvoke
     */
    public function test__badInvoke(): void
    {
        $self = $this;
        $request = $this->getRequest(
            'GET',
            'item',
            [
                'HTTP_AUTHORIZATION' => 'Bearer 123aef-123aef-123aef-123aef'
            ]
        );
        $response = $this->getResponse();
        $run = 0;
        $next = static function (Request $request, Response $response) use (&$run): Response {
            $run++;
            return $response;
        };

        $mw = new ClientMiddleware($this->getContainer(), $this->getClients());
        $response = $mw($request, $response, $next);
        $this->assertEquals(0, $run);
        $this->assertErrorResponse($response);
    }
}
