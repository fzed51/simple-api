<?php


namespace App\action;


use App\Owner;

class UserAccess extends DbAccess
{

    /**
     * @var \App\Owner
     */
    protected $owner;

    public function hydrateOwner(Owner $owner)
    {
        $this->owner = $owner;
    }

}