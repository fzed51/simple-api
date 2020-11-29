<?php

namespace Tests\App\Action;

use App\action\GetAllEntities;
use Tests\Functional\ActionTestCase;

class GetAllEntitiesWithLimitTest extends ActionTestCase
{

    public function setup(): void
    {
        $this->addEntity($this->getClient()->getRef(), 'item', ['foo' => 'bar']);
        $this->addEntity($this->getClient()->getRef(), 'item', ['foo' => 'aze']);
        $this->addEntity($this->getClient()->getRef(), 'item', ['foo' => 'baz']);
        $this->addEntity($this->getClient()->getRef(), 'item', ['foo' => 'wxc']);
        $this->addEntity($this->getClient()->getRef(), 'item', ['foo' => 'dsq']);
    }

    public function test__lireLesEntitiesWithLimit(): void
    {
        $getAll = new GetAllEntities($this->getPdo());
        $getAll->hydrateClientAndRessource($this->getClient(), 'item');
        $getAll->setLimit(3);
        $entities = $getAll();
        $this->assertIsArray($entities);
        $this->assertCount(3, $entities);
        $getAll->setLimit(3, 1);
        $entities = $getAll();
        $this->assertIsArray($entities);
        $this->assertCount(2, $entities);
    }
}
