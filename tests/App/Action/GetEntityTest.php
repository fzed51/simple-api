<?php

namespace Tests\App\Action;

use App\action\GetEntity;
use Tests\Functional\ActionTestCase;

class GetEntityTest extends ActionTestCase
{

    /**
     * @var string
     */
    private $refEntity;

    public function setup(): void
    {
        $this->refEntity = $this->addEntity($this->getOwner()->getRef(), 'item', ['foo' => 'bar']);
        $this->addEntity($this->getOwner()->getRef(), 'item', ['foo' => 'aze']);
    }

    public function test__construitUneActionGetEntity(): void
    {
        $getOne = new GetEntity($this->getPdo());
        $this->assertInstanceOf(GetEntity::class, $getOne);
    }

    public function test_lireUneEntity(): void
    {
        $getOne = new GetEntity($this->getPdo());
        $getOne->hydrateOwnerAndRessource($this->getOwner(), 'item');
        $entity = $getOne($this->refEntity);
        $this->assertIsArray($entity);
        $this->assertArrayHasKey('id', $entity);
        $this->assertArrayHasKey('foo', $entity);
        $this->assertEquals($this->refEntity, $entity['id']);
        $this->assertEquals('bar', $entity['foo']);
    }

    public function test_lireUneEntityInconnu(): void
    {
        $getOne = new GetEntity($this->getPdo());
        $getOne->hydrateOwnerAndRessource($this->getOwner(), 'item');
        $entity = $getOne('unknow');
        $this->assertNull($entity);
    }
}
