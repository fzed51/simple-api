<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 13:29
 */

namespace Tests;

use App\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    protected $userDateValide;
    protected $userPassWord;

    public function setUp(): void
    {
        $this->userPassWord = 'A6F8B8RT9';
        $this->userDateValide = [
            'ref' => 'azeazeaze',
            'name' => 'johndoe',
            'email' => 'john.doe@mail.net',
            'pass' => password_hash($this->userPassWord, PASSWORD_BCRYPT),
            'roles' => ['special']
        ];
    }


    public function test__construct()
    {

        $user = new User($this->userDateValide);
        $this->assertInstanceOf(User::class, $user);

    }

    public function test_getter()
    {
        $user = new User($this->userDateValide);
        $this->assertEquals($this->userDateValide['ref'], $user->getRef());
        $this->assertEquals($this->userDateValide['name'], $user->getName());
        $this->assertEquals($this->userDateValide['email'], $user->getEmail());
    }

    public function test_hasRoles()
    {
        $user = new User($this->userDateValide);
        $this->assertTrue($user->hasRoles($this->userDateValide['roles'][0]));
        $this->assertFalse($user->hasRoles('ADMIN'));
    }
}
