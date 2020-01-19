<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 16/01/2020
 * Time: 17:50
 */

namespace Tests;

use Tests\Functional\AppTestCase;

class End2EndTest extends AppTestCase
{

    /**
     * @throws \Throwable
     */
    public function test_LireLesRessources(): void
    {
        $response = $this->runApp(
            'GET',
            '/item',
            [
                'Authorization' => $this->getOwnerBearerToken()
            ]
        );
        $this->assertSuccessResponse($response);
        $data = $this->getDataResponse($response);
        $this->assertIsArray($data);
    }

    /**
     * @throws \Throwable
     */
    public function test_LireLesRessourcesQuandOnEstPasOwner(): void
    {
        $response = $this->runApp(
            'GET',
            '/item',
            [
                'Authorization' => 'Bearer 123ade-123ade-123ade-123ade'
            ]
        );
        $this->assertErrorResponse($response);
    }

    /**
     * @throws \Throwable
     */
    public function test_AjouterUneRessource(): void
    {
        $nbItems = $this->dbCount('entity');
        $response = $this->runApp(
            'POST',
            '/item',
            [
                'Authorization' => $this->getOwnerBearerToken()
            ],
            [
                'foo' => 'bar'
            ]
        );
        //echo (string)$response->getBody();
        $this->assertSuccessResponse($response);
        $afterNbItem = $this->dbCount('entity');
        $this->assertEquals($nbItems + 1, $afterNbItem);
    }

    public function test_ModifierUneRessource(): void
    {
        $refItem = $this->addEntity(
            $this->getOwner()->getRef(),
            'item',
            ['foo' => 'bar']
        );
        $response = $this->runApp(
            'POST',
            "/item/$refItem",
            [
                'Authorization' => $this->getOwnerBearerToken()
            ],
            [
                'foo' => 'baz'
            ]
        );
        $this->assertSuccessResponse($response);
    }

    public function test_SupprimerUneRessource(): void
    {
        $refItem = $this->addEntity(
            $this->getOwner()->getRef(),
            'item',
            ['foo' => 'bar']
        );
        $response = $this->runApp(
            'DELETE',
            "/item/$refItem",
            [
                'Authorization' => $this->getOwnerBearerToken()
            ]
        );
        $this->assertSuccessResponse($response);
    }

}
