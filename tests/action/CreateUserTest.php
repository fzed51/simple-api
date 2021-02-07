<?php

namespace Tests\action;

use App\action\CreateUser;
use Exception;
use Tests\Functional\ActionTestCase;

/**
 * test de CreateUserTest
 */
class CreateUserTest extends ActionTestCase
{

    /**
     * test l'action avec des données valide
     */
    public function testActionWithValidData(): void
    {
        /** @noinspection JsonEncodingApiUsageInspection */
        $newUser = json_encode([
            'name' => 'Jean',
            'email' => 'jean.r@mail.net',
            'roles' => ['gest'],
            'pass' => 'pass',
            'confirm' => 'pass'
        ]);
        $action = new CreateUser($this->getPdo());
        $action->hydrateClient($this->getClient());
        $before = $this->dbCount('user');
        $idUser = $action($newUser);
        $after = $this->dbCount('user');
        self::assertIsString($idUser);
        self::assertEquals(1, $after - $before);
    }

    /**
     * test l'action avec des données non valide
     */
    public function testActionWithBadData(): void
    {
        $this->expectException(Exception::class);
        /** @noinspection JsonEncodingApiUsageInspection */
        $newUser = json_encode([
            'name' => 'Jean',
            'email' => 'jean.r@mail.net',
            'role' => 'gest',
            'pass' => 'pass',
            'confirm' => 'pass'
        ]);
        $action = new CreateUser($this->getPdo());
        $action->hydrateClient($this->getClient());
        $before = $this->dbCount('user');
        $idUser = $action($newUser);
        $after = $this->dbCount('user');
    }

    /**
     * test l'action avec des données un problème de mot de passe
     */
    public function testActionWithBadPass(): void
    {
        $this->expectException(Exception::class);
        /** @noinspection JsonEncodingApiUsageInspection */
        $newUser = json_encode([
            'name' => 'Jean',
            'email' => 'jean.r@mail.net',
            'roles' => ['gest'],
            'pass' => 'pass',
            'confirm' => 'pax'
        ]);
        $action = new CreateUser($this->getPdo());
        $action->hydrateClient($this->getClient());
        $before = $this->dbCount('user');
        $idUser = $action($newUser);
        $after = $this->dbCount('user');
    }

}
