<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 31/05/2020
 * Time: 22:00
 */

namespace Tests;

use PHPUnit\Framework\TestCase;

class BaseUserSession extends TestCase
{
    protected $dataValide;
    protected $passWord;

    public function setUp(): void
    {
        $this->passWord = 'A6F8B8RT9';
        $this->dataValide = [
            'ref' => 'azeazeaze',
            'name' => 'johndoe',
            'email' => 'john.doe@mail.net',
            'pass' => password_hash($this->passWord, PASSWORD_BCRYPT),
            'roles' => ['special']
        ];
    }
}
