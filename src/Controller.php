<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 08/01/2020
 * Time: 10:07
 */

namespace App;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;

class Controller
{

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getBodyRequest(Request $request)
    {
        return (string)$request->getBody();
    }

    protected function valideJson(string $string)
    {
        json_decode($string, true);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
