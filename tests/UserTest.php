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

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userPassWord = 'A6F8B8RT9';
        $this->userDateValide = [
            'ref' => 'azeazeaze',
            'login' => 'johndoe',
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
}
