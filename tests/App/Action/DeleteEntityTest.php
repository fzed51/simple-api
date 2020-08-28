<?php

namespace Tests\App\Action;

use App\action\DeleteEntity;
use Tests\Functional\ActionTestCase;

/**
 * test de DeleteEntity
 * @package Tests\App\Action
 */
class DeleteEntityTest extends ActionTestCase
{

    /** @var string  */
    private $ref;

    /**
     * setup
     */
    public function setup(): void
    {
        $this->ref = $this->addEntity($this->getOwner()->getRef(), 'item', ['foo' => 'bar']);
    }

    /**
     * test de ConstructionDeDeleteEntity
     */
    public function test_ConstructionDeDeleteEntity(): void
    {
        $deleteEntity = new DeleteEntity($this->getPdo());
        $this->assertInstanceOf(DeleteEntity::class, $deleteEntity);
    }

    /**
     * test de supprimeUneEntity
     */
    public function test__supprimeUneEntity(): void
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

    /**
     * test de supprimeUneEntityInconnu
     */
    public function test__supprimeUneEntityInconnu(): void
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
