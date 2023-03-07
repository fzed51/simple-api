<?php

namespace SimpleApi\Domaines\User;

use DI\Definition\ValueDefinition;
use Helper\DbQuickUse;
use SimpleApi\Action;
use SimpleApi\Elements\Entity;
use SimpleApi\Elements\NewUser;
use Test\ActionTestCase;
use Test\DataCleaner;

/**
 * Test de CreateUser
 */
class CreateUserTest extends ActionTestCase
{
    /**
     * test de Construct
     */
    public function testConstruct(): void
    {
        $a = $this->getAction();
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(CreateUser::class, $a);
        self::assertInstanceOf(Action::class, $a);
    }

    /**
     * test de Action
     */
    public function testAction(): void
    {
        $query = new DbQuickUse($this->getPdo());
        $cleaner = new DataCleaner($this->getPdo());
        $entity = $this->getEntity()->uuid;
        /** @var \SimpleApi\Domaines\User\CreateUser $a */
        $action = $this->resolve(CreateUser::class);
        $newUser = new NewUser(
            'user@mail.net',
            'azerty123456'
        );
        $action->setNewUser($newUser);
        $avant = $query->countElement('user');
        $action->run();
        $apres = $query->countElement('user');
        $ref = $action->getValue();
        self::assertEquals(1, $apres - $avant);
        $cleaner->user($entity, $ref);
    }

    /**
     * @return \SimpleApi\Domaines\User\CreateUser
     */
    private function getAction(): CreateUser
    {
        /** @var \SimpleApi\Domaines\User\CreateUser $a */
        $a = $this->resolve(CreateUser::class);
        return $a;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->getContainer()->set(Entity::class, new ValueDefinition($this->getEntity()));
    }


}
