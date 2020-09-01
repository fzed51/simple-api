<?php


namespace App\action;


use App\Entity\Owner;
use App\Owner;

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

    /**
     * @param \App\Owner $owner
     * @param string $ressourceName
     */
    public function hydrateOwnerAndRessource(Owner $owner, string $ressourceName): void
    {
        $this->owner = $owner;
        $this->ressourceName = $ressourceName;
    }

}
