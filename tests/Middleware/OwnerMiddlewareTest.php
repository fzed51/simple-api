<?php

namespace Tests\App\Middleware;

use App\Middleware\OwnerMiddleware;
use App\Entity\Owner;
use Slim\Http\Request;
use Slim\Http\Response;
use Tests\Functional\ControllerTestCase;

/**
 * test de OwnerMiddleware
 * @package Tests\App\Middleware
 */
class OwnerMiddlewareTest extends ControllerTestCase
{
    /**
     * test de __construct
     */
    public function test__construct(): void
    {
        $mw = new OwnerMiddleware($this->getContainer(), $this->getOwners());
        $this->assertInstanceOf(OwnerMiddleware::class, $mw);
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

        $mw = new OwnerMiddleware($this->getContainer(), $this->getOwners());
        $response = $mw($request, $response, $next);
        $this->assertEquals(0, $run);
        $this->assertErrorResponse($response);
    }
}
