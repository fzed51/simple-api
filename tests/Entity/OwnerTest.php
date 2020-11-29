<?php

namespace Tests\Entity;

use App\Entity\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class OwnerTest
 * @package Tests
 */
class OwnerTest extends TestCase
{
    /**
     * test de __construct
     */
    public function test__construct(): void
    {
        $dataOwner = ['ref' => 'azeaze'];
        $owner = new Client($dataOwner);
        $this->assertInstanceOf(Client::class, $owner);
    }

    /**
     * test de __construct with bad data
     */
    public function test__constructWithBadData(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);
        $dataOwner = [];
        $owner = new Client($dataOwner);// @phpstan-ignore-line
    }

    /**
     * test de GetRef
     */
    public function testGetRef(): void
    {
        $dataOwner = ['ref' => 'azeaze'];
        $owner = new Client($dataOwner);
        $this->assertEquals($dataOwner['ref'], $owner->getRef());
    }

    /**
     * test de GetDescription
     */
    public function testGetDescription(): void
    {
        $dataOwner = ['ref' => 'azeaze', 'description' => 'bar'];
        $owner = new Client($dataOwner);
        $this->assertEquals($dataOwner['description'], $owner->getDescription());
    }

    /**
     * test de HasRessource
     */
    public function testHasRessource(): void
    {
        $dataOwner = [
            'ref' => 'azeaze',
            'description' => 'bar',
            'ressources' => ['ressource1']
        ];
        $owner = new Client($dataOwner);
        $this->assertTrue($owner->hasRessource($dataOwner['ressources'][0]));
    }

    /**
     * test de HasRessourceIsFail
     */
    public function testHasRessourceIsFail(): void
    {
        $dataOwner = [
            'ref' => 'azeaze',
            'description' => 'bar',
            'ressources' => ['ressource1']
        ];
        $owner = new Client($dataOwner);
        $this->assertFalse($owner->hasRessource('wxcvbn'));
    }
}
