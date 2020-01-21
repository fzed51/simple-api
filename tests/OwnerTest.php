<?php

namespace Tests;

use App\Owner;
use PHPUnit\Framework\TestCase;

/**
 * Class OwnerTest
 * @package Tests
 */
class OwnerTest extends TestCase
{

    public function test__construct()
    {
        $dataOwner = ['ref' => 'azeaze'];
        $owner = new Owner($dataOwner);
        $this->assertInstanceOf(Owner::class, $owner);
    }

    public function test__construct_with_bad_data()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);
        $dataOwner = [];
        $owner = new Owner($dataOwner);
    }

    public function testGetRef()
    {
        $dataOwner = ['ref' => 'azeaze'];
        $owner = new Owner($dataOwner);
        $this->assertEquals($dataOwner['ref'], $owner->getRef());
    }

    public function testGetDescription()
    {
        $dataOwner = ['ref' => 'azeaze', 'description' => 'bar'];
        $owner = new Owner($dataOwner);
        $this->assertEquals($dataOwner['description'], $owner->getDescription());
    }

    public function testHasRessource()
    {
        $dataOwner = [
            'ref' => 'azeaze',
            'description' => 'bar',
            'ressources' => ['ressource1']
        ];
        $owner = new Owner($dataOwner);
        $this->assertTrue($owner->hasRessource($dataOwner['ressources'][0]));
    }

    public function testHasRessourceIsFail()
    {
        $dataOwner = [
            'ref' => 'azeaze',
            'description' => 'bar',
            'ressources' => ['ressource1']
        ];
        $owner = new Owner($dataOwner);
        $this->assertFalse($owner->hasRessource('wxcvbn'));
    }
}
