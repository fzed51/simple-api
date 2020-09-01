<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 02/06/2020
 * Time: 11:29
 */

namespace Tests\App\Action;

use App\action\CreateUser;
use Tests\Functional\ActionTestCase;

class CreateUserTest extends ActionTestCase
{

    public function test_constructUneActionCreationUser(): void
    {
        $action = new CreateUser($this->getPdo());
        $this->assertInstanceOf(CreateUser::class, $action);
    }

    /*

        if (!array_key_exists('name', $data) || !self::isValidString($data['name'])) {
            throw new \Exception("Le nom du user n'est pas valide");
        }
        if (!array_key_exists('email', $data) || !self::isValidString($data['email'])) {
            throw new \Exception("L'e-mail' du user n'est pas valide");
        }
        if (!array_key_exists('pass', $data) || !self::isValidString($data['pass'])) {
            throw new \Exception("Le mot de passe du user n'est pas valide");
        }
        if (!array_key_exists('roles', $data) || !is_array($data['roles'])) {
            throw new \Exception("Le mot de passe du user n'est pas valide");
     */

    public function test_creationDUnUser(): void
    {
        $pdo = $this->getPdo();
        $nbUser = $this->dbCount('user');
        $createUser = new CreateUser($this->getPdo());
        $createUser->hydrateOwner($this->getOwner());
        $newRef = $createUser(json_encode([
            'pass' => 'AZE123',
            'confirm' => 'AZE123',
            'name' => 'jean',
            'email' => 'jean@mail.net',
            'roles' => ['admin']
        ]));
        $newNbUser = $this->dbCount('user');
        $this->assertTrue($nbUser < $newNbUser, "$nbUser < $newNbUser");
        $this->assertIsString($newRef);
        $this->assertTrue(strlen($newRef) > 10);
    }
}
