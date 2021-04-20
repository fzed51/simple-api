<?php

namespace Tests;

use App\Controller;
use App\UserController;
use Tests\Functional\ControllerTestCase;

class UserControllerTest extends ControllerTestCase
{

    /**
     * @return mixed
     */
    public function getUserController()
    {
        return new UserController($this->getContainer());
    }

    public function testConstructeur()
    {
        $controleur = $this->getUserController();
        self::assertInstanceOf(Controller::class, $controleur);
        self::assertInstanceOf(UserController::class, $controleur);
    }

    public function testCreate()
    {
        $ctrl = $this->getUserController();
        $req = $this->getRequestWithClient(
            'POST',
            'connect',
            [],
            [
                'name' => 'Jean',
                'email' => 'jean.durant@mail.net',
                'pass' => 'azerty123',
                'confirm' => 'azerty123',
                'roles' => []
            ]
        );
        $rep = $ctrl->create($req, $this->getResponse(), []);
        $this->assertSuccessResponse($rep);
    }
}
