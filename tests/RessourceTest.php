<?php

namespace Tests;

use App\Ressource;
use PHPUnit\Framework\TestCase;

/**
 * Class RessourceTest
 * @package Tests
 */
class RessourceTest extends TestCase
{

    public function test__construct()
    {
        $ressource = new Ressource('test');
        $this->assertInstanceOf(Ressource::class, $ressource);
    }

    public function testGetName()
    {
        $nom = 'test';
        $ressource = new Ressource($nom);
        $this->assertEquals($nom, $ressource->getName());
    }

    public function testIs()
    {
        $nom = 'test';
        $ressource = new Ressource($nom);
        $this->assertTrue($ressource->is($nom));
    }
}
