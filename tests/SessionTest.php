<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 31/05/2020
 * Time: 19:58
 */

namespace Tests;

use App\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends BaseUserSession
{
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->token = '123456789abcdef';
        $this->dataValide['token'] = $this->token;
    }


    public function test_construct_valid_session()
    {
        $session = new Session($this->dataValide);
        $this->assertInstanceOf(Session::class, $session);
    }

    public function testGetToken()
    {
        $session = new Session($this->dataValide);
        $this->assertEquals($this->token, $session->getToken());
    }
}
