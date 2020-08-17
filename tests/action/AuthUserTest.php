<?php

namespace Tests\action;

use App\action\AuthUser;
use Slim\Http\Environment;
use Slim\Http\Request;
use Tests\Functional\ActionTestCase;

class AuthUserTest extends ActionTestCase
{
    public function test__construct(): void
    {
        $this->expectNotToPerformAssertions();
        $action = new AuthUser($this->getPdo());
        $action->hydrateOwner($this->getOwner());
    }

    public function test_actionWithBadToken(): void
    {
        $token = '446c7128214547e7bc060a5df86f121bf68c12928afc4bc4b458151b5b556e5d';
        $req = Request::createFromEnvironment(Environment::mock(['HTTP_AUTHORIZATION' => 'Bearer ' . $token]));
        $action = new AuthUser($this->getPdo());
        $action->hydrateOwner($this->getOwner());
        $ref = $action($req);
        self::assertNull($ref);
    }

    public function test_actionWithoutToken(): void
    {

        $req = Request::createFromEnvironment(Environment::mock([]));
        $action = new AuthUser($this->getPdo());
        $action->hydrateOwner($this->getOwner());
        $ref = $action($req);
        self::assertNull($ref);
    }

    public function test_actionWithGoodToken(): void
    {
        $_SERVER['REMOTE_ADDR'] = '92.68.125.32';
        $_SERVER['HTTP_USER_AGENT'] = 'USER AGENT';
        $refUser = $this->addUser(
            $this->getOwner()->getRef(),
            'Jhon Doe',
            'john.doe@mail.com',
            'aze1234',
            ['ADMIN'],
            true
        );
        $user = $this->getDbUser($this->getOwner()->getRef(), $refUser);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $user['session_public_token'];
        $request = Request::createFromEnvironment(Environment::mock($_SERVER));
        $action = new AuthUser($this->getPdo());
        $action->hydrateOwner($this->getOwner());
        $ref = $action($request);
        self::assertIsString($ref);
    }

    public function test_actionWithExpiratedTokenToken(): void
    {
        $_SERVER['REMOTE_ADDR'] = '92.68.125.33';
        $_SERVER['HTTP_USER_AGENT'] = 'USER AGENT';
        $refUser = $this->addUser(
            $this->getOwner()->getRef(),
            'Ervin Howell',
            'ervin.howell@mail.com',
            'aze2345',
            [],
            true
        );
        $this->dbUpdate('user', [
            'session_expiration' => (new \DateTime())
                ->sub(new \DateInterval('PT30M'))
                ->format(DATE_ATOM)
        ], "ref = '$refUser'");
        $user = $this->getDbUser($this->getOwner()->getRef(), $refUser);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $user['session_public_token'];
        $request = Request::createFromEnvironment(Environment::mock($_SERVER));
        $action = new AuthUser($this->getPdo());
        $action->hydrateOwner($this->getOwner());
        $ref = $action($request);
        self::assertNull($ref);
    }
}
