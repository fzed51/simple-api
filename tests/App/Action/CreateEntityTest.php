<?php

namespace Tests\App\Action;

use App\action\CreateEntity;
use Tests\Functional\ActionTestCase;

class CreateEntityTest extends ActionTestCase
{
    public function test_construcUneAction(): void
    {
        $action = new CreateEntity($this->getPdo());
        $this->assertInstanceOf(CreateEntity::class, $action);
    }

    public function test_creationDUneEntity(): void
    {
        $pdo = $this->getPdo();
        $nbEntity = $this->dbCount('entity');
        $createEntity = new CreateEntity($this->getPdo());
        $createEntity->hydrateOwnerAndRessource($this->getOwner(), 'item');
        $newRef = $createEntity(json_encode(['foo' => 'bar']));
        $newNbEntity = $this->dbCount('entity');
        $this->assertTrue($nbEntity < $newNbEntity, "$nbEntity < $newNbEntity");
        $this->assertIsString($newRef);
        $this->assertTrue(strlen($newRef) > 10);
    }
}
