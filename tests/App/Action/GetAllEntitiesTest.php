<?php

namespace Tests\App\Action;

use App\action\GetAllEntities;
use Tests\Functional\ActionTestCase;

class GetAllEntitiesTest extends ActionTestCase
{

    private $refEntity;

    public function setup(): void
    {
        $this->refEntity = $this->addEntity($this->getOwner()->getRef(), 'item', ['foo' => 'bar']);
        $this->addEntity($this->getOwner()->getRef(), 'item', ['foo' => 'aze']);
    }

    public function test__construitUneActionGetAllEntities(): void
    {
        $getAll = new GetAllEntities($this->getPdo(), $this->getOwner(), 'item');
        $this->assertInstanceOf(GetAllEntities::class, $getAll);
    }


    public function test__lireLesEntities(): void
    {
        $getAll = new GetAllEntities($this->getPdo(), $this->getOwner(), 'item');
        $entities = $getAll();
        $this->assertCount(2, $entities);
        $this->assertEquals($this->refEntity, $entities[0]['id']);
        $this->assertEquals('aze', $entities[1]['foo']);
    }
}
