<?php

namespace SimpleApi\Domaines\User;

use DateInterval;
use DateTime;
use Helper\PdoQueryable;
use PDO;
use RuntimeException;
use SimpleApi\Action;
use SimpleApi\Elements\Entity;
use SimpleApi\Elements\NewUser;
use SimpleApi\Utilities\Generator;
use SimpleApi\Utilities\Security;

/**
 * Action de création de nouveau user
 */
class CreateUser extends Action
{

    use PdoQueryable;

    protected null|NewUser $newUser = null;

    /**
     * Paramètres static de l'action introduit par le constructeur
     * @param \PDO $pdo
     * @param \SimpleApi\Elements\Entity $entity
     */
    public function __construct(
        PDO                 $pdo,
        protected Entity    $entity,
        protected Security  $security,
        protected Generator $generator
    ) {
        $this->setPdo($pdo);
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
     * @inheritDoc
     * @return void
     */
    public function run(): void
    {
        if ($this->newUser === null) {
            throw new RuntimeException(__METHOD__ . " n'est pas correctement initialisé");
        }
        $this->setReqSql($this->getReqInsertUser());
        // ref, entity, email, pass,
        // salt, enable, enable_token, enable_limit
        $ref = $this->generator->uuid();
        $salt = $this->generator->token(32);
        $this->execute([
            $ref,
            $this->entity->uuid,
            $this->newUser->email,
            $this->security->hashPass($this->newUser->pass, $salt),
            $salt,
            0,
            $this->generator->uuid(),
            (new DateTime())->add(new DateInterval("P1D"))->format(DATE_ATOM)
        ]);
        $this->setValue($ref);
    }

    /**
     * retourne la requete de création d'un user
     * @return string
     */
    protected function getReqInsertUser(): string
    {
        return <<<SQL
insert into `user` (ref, entity, email, pass, salt, `enable`, enable_token, enable_limit) 
values             (?  ,?      ,?     ,?    ,?    ,?        ,?            ,?) 
SQL;
    }
}
