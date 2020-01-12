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
            $this->getRequest('POST', '/item', [
                'foo' => 'bar'
            ]),
            $this->getResponse(),
            ['ressource' => 'item']
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
        $this->assertIsArray($data);
        $newNbEntity = $this->dbCount('entity', "ressource = 'item'");
        $this->assertEquals($nbEntity + 1, $newNbEntity);
    }
}
