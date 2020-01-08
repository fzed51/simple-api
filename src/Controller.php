<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 08/01/2020
 * Time: 10:07
 */

namespace App;


use Psr\Container\ContainerInterface;

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

}