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
        if ($method == 'OPTIONS') {
            return self::decorate($request, $response);
        }
        $response = $next($request, $response);
        return self::decorate($request, $response);
    }

    public static function decorate(Request $request, Response $response): Response
    {
        $origin = $request->getServerParam('HTTP_ORIGIN', 'http://localhost');
        return $response->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', 'content-type, Authorization')
            ->withHeader('Access-Control-Allow-Method', 'GET, POST, DELETE');
    }
}
