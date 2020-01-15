<?php

namespace Tests\App;

use App\RessourceController;
use Tests\Functional\ControllerTestCase;

class RessourceControllerTest extends ControllerTestCase
{
    public function test_constructeurDuRessourceController()
    {
        $control = new RessourceController($this->getContainer());
        $this->assertInstanceOf(RessourceController::class, $control);
    }

    public function test_RecupereToutesLesRessources()
    {
        $nbEntity = $this->dbCount('entity', "ressource = 'item'");
        $control = new RessourceController($this->getContainer());
        $response = $control->getAll(
            $this->getRequest('GET', '/item'),
            $this->getResponse(),
            ['ressource' => 'item']
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
        $this->assertIsArray($data);
        $this->assertCount($nbEntity, $data);
    }

    public function test_RecupereUneRessource()
    {
        $refEntity = $this->addEntity(
            $this->getOwner()->getRef(),
            'item',
            [
                'foo' => 'aze'
            ]
        );
        $control = new RessourceController($this->getContainer());
        $response = $control->getOne(
            $this->getRequest('GET', "/item/$refEntity"),
            $this->getResponse(),
            [
                'ressource' => 'item',
                'ref' => $refEntity
            ]
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
    }

    public function test_AjouteUneRessource()
    {
        $nbEntity = $this->dbCount('entity', "ressource = 'item'");
        $control = new RessourceController($this->getContainer());
        $response = $control->create(
            $this->getRequest(
                'POST',
                '/item',
                [],
                [
                    'foo' => 'bar'
                ]),
            $this->getResponse(),
            ['ressource' => 'item']
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
        $this->assertIsObject($data);
        $newNbEntity = $this->dbCount('entity', "ressource = 'item'");
        $this->assertEquals($nbEntity + 1, $newNbEntity);
    }

    public function test_ModifiUneRessource(): void
    {
        $refEntity = $this->addEntity(
            $this->getOwner()->getRef(),
            'item',
            [
                'foo' => 'aze'
            ]
        );
        $control = new RessourceController($this->getContainer());
        $response = $control->update(
            $this->getRequest(
                'POST',
                '/item/' . $refEntity,
                [],
                [
                    'id' => $refEntity,
                    'foo' => 'baz'
                ]
            ),
            $this->getResponse(),
            ['ressource' => 'item', 'ref' => $refEntity]
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
    }

    public function test_SupprimeUneRessource(): void
    {
        $refEntity = $this->addEntity(
            $this->getOwner()->getRef(),
            'item',
            [
                'foo' => 'aze'
            ]
        );
        $control = new RessourceController($this->getContainer());
        $response = $control->delete(
            $this->getRequest(
                'DELETE',
                '/item/' . $refEntity
            ),
            $this->getResponse(),
            ['ressource' => 'item', 'ref' => $refEntity]
        );
        $this->assertSuccessResponse($response);
    }
}
