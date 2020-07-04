<?php


namespace App\action;


use App\Entity\Owner;

class UserAccess extends DbAccess
{

    /**
     * @var \App\Entity\Owner
     */
    protected $owner;

    public function hydrateOwner(Owner $owner)
    {
        $this->owner = $owner;
    }

}