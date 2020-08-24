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
            $this->getOwner()->getRef(),
            'John Doe',
            'john.doe@mail.com',
            'mot2Passe',
            [],
            true
        );
        $user = $this->getDbUser($this->getOwner()->getRef(), $idUser);
        $action = new GetSession($this->getPdo());
        $action->hydrateOwner($this->getOwner());
        $session = $action($idUser);
        self::assertIsArray($session);
        $props = ['ref', 'owner', 'name', 'email', 'role', 'session_public_token', 'session_expiration'];
        foreach ($props as $property) {
            self::assertArrayHasKey($property, $session);
        }
        self::assertIsArray($session['role']);
    }

}
