<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CorsMiddleware
 */
class CorsMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {

        $method = $request->getMethod();
        $origin = $request->getServerParam('HTTP_ORIGIN');
        if ($method == 'OPTIONS') {
            return $response->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Headers', 'content-type')
                ->withHeader('Access-Control-Allow-Method', 'GET POST DELETE');
        }
        $response = $next($request, $response);
        return $response->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', 'content-type')
            ->withHeader('Access-Control-Allow-Method', 'GET POST DELETE');
    }
}