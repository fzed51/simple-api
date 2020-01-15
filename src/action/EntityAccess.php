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

    public function hydrateOwnerAndRessource(Owner $owner, string $ressourceName)
    {
        $this->owner = $owner;
        $this->ressourceName = $ressourceName;
    }

}