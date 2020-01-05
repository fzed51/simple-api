<?php


namespace App\action;


use App\Owner;

class EntityAccess extends DbAccess
{

    /**
     * @var \App\Owner
     */
    protected $owner;
    /**
     * @var string
     */
    protected $ressourceName;

    public function __construct(\PDO $pdo, Owner $owner, string $ressourceName)
    {
        parent::__construct($pdo);
        $this->owner = $owner;
        $this->ressourceName = $ressourceName;
    }

}