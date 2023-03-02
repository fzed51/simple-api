<?php

namespace SimpleApi\Domaines\User;

use PDO;
use RuntimeException;
use SimpleApi\Action;
use SimpleApi\Elements\Entity;
use SimpleApi\Elements\NewUser;

/**
 * Action de création de nouveau user
 */
class CreateUser extends Action
{
    protected null|NewUser $newUser = null;

    /**
     * Paramètres static de l'action introduit par le constructeur
     * @param \PDO $pdo
     * @param \SimpleApi\Elements\Entity $entity
     */
    public function __construct(protected PDO $pdo, protected Entity $entity)
    {
    }

    /**
     * Initialise les information du nouveau user
     * @param \SimpleApi\Elements\NewUser $newUser
     * @return void
     */
    public function setNewUser(NewUser $newUser): void
    {
        $this->newUser = $newUser;
    }

    /**
     * retourne la requete de création d'un user
     * @return string
     */
    protected function getReqSql(): string
    {
        return "insert into user (ref, entity, email, pass, salt, enable, enable_token, enable_limit) values (?,?,?,?,?,?,?,?)";
    }

    /**
     * @inheritDoc
     * @return void
     */
    public function execute(): void
    {
        if ($this->newUser === null) {
            throw new RuntimeException(__METHOD__ . " n'est pas correctement initialisé");
        }
    }
}
