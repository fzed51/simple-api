<?php


namespace App\action;

use App\Entity\Client;

class UserAccess extends DbAccess
{

    /**
     * @var \App\Entity\Client
     */
    protected $client;

    public function hydrateClient(Client $client)
    {
        $this->client = $client;
    }
}
