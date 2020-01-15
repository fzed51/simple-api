<?php

namespace Tests\App\Action;

use App\action\DeleteEntity;
use Tests\Functional\ActionTestCase;

class DeleteEntityTest extends ActionTestCase
{

    private $ref;

    public function setup(): void
    {
        $this->ref = $this->addEntity($this->getOwner()->getRef(), 'item', ['foo' => 'bar']);
    }

    public function test_ConstructionDeDeleteEntity()
    {
        $deleteEntity = new DeleteEntity($this->getPdo());
        $this->assertInstanceOf(DeleteEntity::class, $deleteEntity);

    }

    public function test__supprimeUneEntity()
    {
        $pdo = $this->getPdo();
        $nbEntity =$this->dbCount('entity');
        $deleteEntity = new DeleteEntity($pdo);
        $deleteEntity->hydrateOwnerAndRessource($this->getOwner(), 'item');
        $ok = $deleteEntity($this->ref);
        $newNbEntity = $this->dbCount('entity');
        $this->assertLessThan($nbEntity, $newNbEntity);
        $this->assertTrue($ok);
    }
    
    public function test__supprimeUneEntityInconnu()
    {
        $pdo = $this->getPdo();
        $nbEntity = $this->dbCount('entity');
        $deleteEntity = new DeleteEntity($pdo);
        $deleteEntity->hydrateOwnerAndRessource($this->getOwner(), 'item');
        $ok = $deleteEntity('unknow');
        $newNbEntity = $this->dbCount('entity');
        $this->assertEquals($nbEntity, $newNbEntity);
        $this->assertFalse($ok);
    }
}
