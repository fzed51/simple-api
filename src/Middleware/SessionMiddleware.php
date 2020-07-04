<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 31/05/2020
 * Time: 22:22
 */

namespace App\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;

class SessionMiddleware extends Middleware
{

    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        try {

            $pdo = $this->container->get(\PDO::class);
        } finally {

            return $next($request, $response);
        }
    }
}