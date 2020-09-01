<?php


namespace App\action;

use App\Entity\Owner;

/**
 * Class EntityAccess
 * @package App\action
 */
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
     * @param \App\Entity\Owner $owner
     * @param string $ressourceName
     */
    public function hydrateOwnerAndRessource(Owner $owner, string $ressourceName): void
    {
        $this->owner = $owner;
        $this->ressourceName = $ressourceName;
    }
}
