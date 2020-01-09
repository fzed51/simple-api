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
        $getAll = new GetAllEntities($this->getPdo());
        $this->assertInstanceOf(GetAllEntities::class, $getAll);
    }


    public function test__lireLesEntities(): void
    {
        $getAll = new GetAllEntities($this->getPdo());
        $getAll->hydrateOwnerAndRessource($this->getOwner(), 'item');
        $entities = $getAll();
        $this->assertIsArray($entities);
        $this->assertCount(2, $entities);
        $this->assertIsArray($entities[0]);
        $entity = $entities[0];
        $this->assertArrayHasKey('id', $entity);
        $this->assertArrayHasKey('foo', $entity);
        $this->assertEquals($this->refEntity, $entity['id']);
        $this->assertEquals('bar', $entity['foo']);
    }
}
