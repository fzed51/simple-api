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

    /**
     * @param \App\Owner $owner
     * @param string $ressourceName
     */
    public function hydrateOwnerAndRessource(Owner $owner, string $ressourceName): void
    {
        $this->owner = $owner;
        $this->ressourceName = $ressourceName;
    }

    /**
     * @param string $json
     * @return bool
     */
    public function isValidJson(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
