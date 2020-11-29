<?php


namespace App\action;

use App\Entity\Client;

/**
 * Class EntityAccess
 * @package App\action
 */
class EntityAccess extends DbAccess
{

    /**
     * @var \App\Entity\Client
     */
    protected $client;
    /**
     * @var string
     */
    protected $ressourceName;

    /**
     * @param \App\Entity\Client $client
     * @param string $ressourceName
     */
    public function hydrateClientAndRessource(Client $client, string $ressourceName): void
    {
        $this->client = $client;
        $this->ressourceName = $ressourceName;
    }
}
