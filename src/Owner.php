<?php


namespace App;


class Owner
{
    /** @var string */
    private $ref;
    /** @var string */
    private $description;
    /** @var array  */
    private $ressources;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    private function hydrate(array $data)
    {
        if (!array_key_exists('ref', $data)) {
            throw new \Exception('Données du owner corompues', 500);
        }
        $this->ref = $data['ref'];
        $this->description = $data['description'] ?? '';
        $this->hydrateRessources($data['ressources'] ?? []);
    }

    private function hydrateRessources(array $ressources)
    {
        $this->ressources = [];
        foreach ($ressources as $ressource){
            $this->ressources[] = new Ressource($ressource);
        }
    }
}