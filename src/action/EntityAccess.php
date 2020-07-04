<?php


namespace App\action;


use App\Entity\Owner;

class EntityAccess extends DbAccess
{

    /**
     * @var \App\Entity\Owner
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