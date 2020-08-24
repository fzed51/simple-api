<?php

namespace Test\App\Action;

use App\action\ConnectUser;
use App\action\CreateUser;
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
        $_SERVER['REMOTE_ADDR'] = '90.54.180.1';
        $_SERVER['HTTP_USER_AGENT'] = 'USER_AGENT';
        $create = new CreateUser($this->getPdo());
        $create->hydrateOwner($this->getOwner());
        $connect = new ConnectUser($this->getPdo());
        $connect->hydrateOwner($this->getOwner());
        $user = json_encode($this->getNewUser());
        $ref = $create($user);
        $session = $connect($user);
        self::assertIsString($session);
    }
}
