<?php

namespace Test\App\Action;

use App\action\ConnectUser;
use App\Entity\Session;
use Tests\Functional\ActionTestCase;

class ConnectUserTest extends ActionTestCase
{

    public function test__construct()
    {
        $connect = new ConnectUser($this->getPdo());
        $this->assertInstanceOf(ConnectUser::class, $connect);
    }

    public function test__invoke()
    {
        $connect = new ConnectUser($this->getPdo());
        $connect->hydrateOwner($this->getOwner());
        $user = json_encode($this->getNewUser());
        $session = $connect($user);
        self::assertInstanceOf(Session::class, $session);
    }
}
