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
        $deleteEntity = new DeleteEntity($this->getPdo(), $this->getOwner(), 'item');
        $this->assertInstanceOf(DeleteEntity::class, $deleteEntity);

    }

    public function test__supprimeUneEntity()
    {
        $pdo = $this->getPdo();
        $nbEntity = (int)$pdo->query('select count(*) from entity')->fetchColumn();
        $deleteEntity = new DeleteEntity($pdo, $this->getOwner(), 'item');
        $ok = $deleteEntity($this->ref);
        $newNbEntity = (int)$pdo->query('select count(*) from entity')->fetchColumn();
        $this->assertLessThan($nbEntity, $newNbEntity);
        $this->assertTrue($ok);
    }
    
    public function test__supprimeUneEntityInconnu()
    {
        $pdo = $this->getPdo();
        $nbEntity = (int)$pdo->query('select count(*) from entity')->fetchColumn();
        $deleteEntity = new DeleteEntity($pdo, $this->getOwner(), 'item');
        $ok = $deleteEntity('unknow');
        $newNbEntity = (int)$pdo->query('select count(*) from entity')->fetchColumn();
        $this->assertEquals($nbEntity, $newNbEntity);
        $this->assertFalse($ok);
    }
}
