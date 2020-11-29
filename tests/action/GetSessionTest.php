<?php

namespace Tests\action;

use App\action\GetSession;
use Tests\Functional\ActionTestCase;

/**
 * test de GetSessionTest
 * @package Tests\action
 */
class GetSessionTest extends ActionTestCase
{

    public function testConstructor(): void
    {
        $action = new GetSession($this->getPdo());
        self::assertInstanceOf(GetSession::class, $action);
    }

    public function testInvocke()
    {
        $idUser = $this->addUser(
            $this->getClient()->getRef(),
            'John Doe',
            'john.doe@mail.com',
            'mot2Passe',
            [],
            true
        );
        $user = $this->getDbUser($this->getClient()->getRef(), $idUser);
        $action = new GetSession($this->getPdo());
        $action->hydrateClient($this->getClient());
        $session = $action($idUser);
        self::assertIsArray($session);
        $props = ['ref', 'client', 'name', 'email', 'role', 'session_public_token', 'session_expiration'];
        foreach ($props as $property) {
            self::assertArrayHasKey($property, $session);
        }
        self::assertIsArray($session['role']);
    }
}
