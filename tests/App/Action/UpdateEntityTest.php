<?php

namespace Tests\App\Action;

use App\action\UpdateEntity;
use Tests\Functional\ActionTestCase;

class UpdateEntityTest extends ActionTestCase
{

    public function test_construcUneUpdateEntity(): void
    {
        $updateEntity = new UpdateEntity($this->getPdo());
        $this->assertInstanceOf(UpdateEntity::class, $updateEntity);
    }

    public function test__invoke(): void
    {
        $ref = $this->addEntity($this->getClient()->getRef(), 'item', ['foo' => 'bar']);
        $updateEntity = new UpdateEntity($this->getPdo());
        $updateEntity->hydrateClientAndRessource($this->getClient(), 'item');
        $updateEntity($ref, json_encode(['foo' => 'aze']));
        $ent = $this->getEntity($this->getClient()->getRef(), 'item', $ref);
        $this->assertIsArray($ent);
        $this->assertArrayHasKey('data', $ent);
        $data = json_decode($ent['data'], true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('foo', $data);
        $this->assertEquals('aze', $data['foo']);
    }
}
