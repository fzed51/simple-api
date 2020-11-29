<?php

namespace Tests\Entity;

use App\Entity\LoginUser;

class LoginUserTest extends BaseUserSession
{

    public function test__construct()
    {
        $dataClient = ['email' => 'azeaze', 'pass' => "roroa"];
        $client = new LoginUser($dataClient);
        $this->assertInstanceOf(LoginUser::class, $client);
    }

    public function test__constructWithBadData()
    {
        $this->expectException(\Exception::class);
        $dataClinet = ['name' => 'azeaze', 'pass' => "roroa"];
        $login = new LoginUser($dataClinet);
    }

    public function test__constructWithEmptyData()
    {
        $this->expectException(\Exception::class);
        $dataClient = ['email' => '', 'pass' => "roroa"];
        $login = new LoginUser($dataClient);
    }
}
