<?php

namespace Tests\App;

use App\RessourceController;
use Tests\Functional\ControllerTestCase;

/**
 * test de  RessourceController
 * @package Tests\App
 */
class RessourceControllerTest extends ControllerTestCase
{
    /**
     * test de constructeurDuRessourceController
     */
    public function test_constructeurDuRessourceController(): void
    {
        $control = new RessourceController($this->getContainer());
        $this->assertInstanceOf(RessourceController::class, $control);
    }

    /**
     * test de RecupereToutesLesRessources
     * @throws \JsonException
     */
    public function test_RecupereToutesLesRessources(): void
    {
        $nbEntity = $this->dbCount('entity', "ressource = 'item'");
        $control = new RessourceController($this->getContainer());
        $response = $control->getAll(
            $this->getRequestWithOwner('GET', '/item'),
            $this->getResponse(),
            ['ressource' => 'item']
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
        $this->assertIsArray($data);
        $this->assertCount($nbEntity, $data);
    }

    /**
     * test de RecupereUneRessource
     * @throws \JsonException
     */
    public function test_RecupereUneRessource(): void
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
            $this->getRequestWithOwner('GET', "/item/$refEntity"),
            $this->getResponse(),
            [
                'ressource' => 'item',
                'ref' => $refEntity
            ]
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
    }

    /**
     * test de AjouteUneRessource
     * @throws \JsonException
     */
    public function test_AjouteUneRessource(): void
    {
        $nbEntity = $this->dbCount('entity', "ressource = 'item'");
        $control = new RessourceController($this->getContainer());
        $response = $control->create(
            $this->getRequestWithOwner(
                'POST',
                '/item',
                [],
                [
                    'foo' => 'bar'
                ]
            ),
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
            $this->getRequestWithOwner(
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
            $this->getRequestWithOwner(
                'DELETE',
                '/item/' . $refEntity
            ),
            $this->getResponse(),
            ['ressource' => 'item', 'ref' => $refEntity]
        );
        $this->assertSuccessResponse($response);
    }
}
