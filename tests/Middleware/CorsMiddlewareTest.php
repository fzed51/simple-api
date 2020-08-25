<?php

namespace Tests\Middleware;

use App\Middleware\CorsMiddleware;
use Slim\Http\Request;
use Slim\Http\Response;
use Tests\Functional\ControllerTestCase;

class CorsMiddlewareTest extends ControllerTestCase
{

    public function test__construct()
    {
        $mw = new CorsMiddleware($this->getContainer());
        $this->assertInstanceOf(CorsMiddleware::class, $mw);
    }

    public function test__invoke()
    {
        $run = 0;
        $mw = new CorsMiddleware($this->getContainer());
        $req = $this->getRequest('GET', 'item');
        $rep = $this->getResponse();
        $next = static function (Request $request, Response $response) use (&$run): Response {
            $run++;
            return $response;
        };
        $rep = $mw($req, $rep, $next);
        $this->assertEquals(1, $run);
        $repHeaders = $rep->getHeaders();
        $this->assertIsArray($repHeaders);
        $this->assertCount(3, $repHeaders);
        $expect = array_map(
            'strtolower',
            [
                'Access-Control-Allow-Origin',
                'Access-Control-Allow-Headers',
                'Access-Control-Allow-Method'
            ]
        );
        sort($expect);
        $keys = array_keys(array_change_key_case($repHeaders));
        sort($keys);
        $this->assertSame($expect, $keys);
    }
}
