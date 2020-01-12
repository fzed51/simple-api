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

    public function test_RecupereToutesLesRessource()
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
}
