<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 31/05/2020
 * Time: 19:58
 */

namespace Tests\Entity;

use App\Entity\Session;

class SessionTest extends BaseUserSession
{
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->token = '123456789abcdef';
        $this->dataValide['session_private_token'] = hash('sha256', random_bytes(10));
        $this->dataValide['session_public_token'] = hash('sha256', random_bytes(10));
        $this->dataValide['session_expiration'] = (new \DateTime())->format('Y-m-d H:i:s');
    }


    public function test_construct_valid_session()
    {
        $session = new Session($this->dataValide);
        self::assertInstanceOf(Session::class, $session);
    }

    public function testGetPublicToken()
    {
        $session = new Session($this->dataValide);
        self::assertEquals($this->dataValide['session_public_token'], $session->getSessionPublicToken());
    }
    public function testGetExpiration()
    {
        $session = new Session($this->dataValide);
        self::assertEquals($this->dataValide['session_expiration'], $session->getSessionExpiration());
    }
}
