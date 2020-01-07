<?php

namespace Tests\App;

use App\RessourceController;
use Slim\Http\Response;
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
        $control = new RessourceController($this->getContainer());
        $response = $control->getAll(
            $this->getRequest('GET', '/item'),
            $this->getResponse(),
            ['ressource' => 'item']
        );
        $this->assertInstanceOf(Response::class, $response);
    }
}
