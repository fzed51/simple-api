<?php

namespace Tests\Entity;

use App\Entity\Ressource;
use PHPUnit\Framework\TestCase;

/**
 * test de Ressource
 * @package Tests
 */
class RessourceTest extends TestCase
{
    /**
     * test de __construct
     */
    public function test__construct(): void
    {
        $ressource = new Ressource('test');
        $this->assertInstanceOf(Ressource::class, $ressource);
    }

    /**
     * test de GetName
     */
    public function testGetName(): void
    {
        $nom = 'test';
        $ressource = new Ressource($nom);
        $this->assertEquals($nom, $ressource->getName());
    }

    /**
     * test de Is
     */
    public function testIs(): void
    {
        $nom = 'test';
        $ressource = new Ressource($nom);
        $this->assertTrue($ressource->is($nom));
    }
}
