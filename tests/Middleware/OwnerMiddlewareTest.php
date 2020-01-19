<?php

namespace Tests\App\Middleware;

use App\Middleware\OwnerMiddleware;
use App\Owner;
use Slim\Http\Request;
use Slim\Http\Response;
use Tests\Functional\ControllerTestCase;

class OwnerMiddlewareTest extends ControllerTestCase
{

    public function test__construct()
    {
        $mw = new OwnerMiddleware($this->getContainer(), $this->getOwners());
        $this->assertInstanceOf(OwnerMiddleware::class, $mw);
    }

    public function test__invoke()
    {
        $self = $this;
        $request = $this->getRequest(
            'GET',
            'item',
            [
                'HTTP_AUTHORIZATION' => $this->getOwnerBearerToken()
            ]
        );
        $response = $this->getResponse();
        $run = 0;
        $next = static function (Request $request, Response $response) use ($self, &$run): Response {
            $run++;
            $owner = $request->getAttribute('owner');
            $self->assertInstanceOf(Owner::class, $owner);
            return $response;
        };
        $mw = new OwnerMiddleware($this->getContainer(), $this->getOwners());
        $mw($request, $response, $next);
        $this->assertEquals(1, $run);
    }

    public function test__badInvoke()
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

        $mw = new OwnerMiddleware($this->getContainer(), $this->getOwners());
        $response = $mw($request, $response, $next);
        $this->assertEquals(0, $run);
        $this->assertErrorResponse($response);
    }
}
