<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 13:29
 */

namespace Tests\Entity;

use App\Entity\NewUser;

class NewUserTest extends BaseUserSession
{
    public function setUp(): void
    {
        parent::setUp();
        $this->dataValide['confirm'] = $this->dataValide['pass'] = $this->passWord;
    }


    public function test__construct()
    {

        $user = new NewUser($this->dataValide);
        $this->assertInstanceOf(NewUser::class, $user);
    }

    public function test_getter()
    {
        $user = new NewUser($this->dataValide);
        $this->assertEquals($this->dataValide['name'], $user->getName());
        $this->assertEquals($this->dataValide['email'], $user->getEmail());
    }

    public function test_hasRoles()
    {
        $user = new NewUser($this->dataValide);
        $this->assertTrue($user->hasRoles($this->dataValide['roles'][0]));
        $this->assertFalse($user->hasRoles('ADMIN'));
    }

    public function test_getRoles()
    {
        $user = new NewUser($this->dataValide);
        $this->assertEquals($this->dataValide['roles'], $user->getRoles());
    }
}
