<?php

namespace Tests\Entity;

class LoginUserTest extends BaseUserSession
{

    public function test__construct()
    {
        $dataOwner = ['email' => 'azeaze', 'pass' => "roroa"];
        $owner = new LoginUser($dataOwner);
        $this->assertInstanceOf(LoginUser::class, $owner);
    }

    public function test__constructWithBadData()
    {
        $this->expectException(\Exception::class);
        $dataOwner = ['name' => 'azeaze', 'pass' => "roroa"];
        $owner = new LoginUser($dataOwner);
    }

    public function test__constructWithEmptyData()
    {
        $this->expectException(\Exception::class);
        $dataOwner = ['name' => '', 'pass' => "roroa"];
        $owner = new LoginUser($dataOwner);
    }
}