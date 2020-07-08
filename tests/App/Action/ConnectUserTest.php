<?php

namespace Test\App\Action;

use App\action\ConnectUser;
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
        $user = $this->getNewUser();
        $connect = new ConnectUser($this->getPdo());
        $session = $connect($user);
    }
}
