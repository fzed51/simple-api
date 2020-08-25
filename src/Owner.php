<?php


namespace App;

class Owner
{
    /** @var string */
    private $ref;
    /** @var string */
    private $description;
    /** @var array */
    private $ressources;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    private function hydrate(array $data)
    {
        if (!array_key_exists('ref', $data)) {
            error_log('data : ' . json_encode($data));
            throw new \Exception('Données du owner corompues', 500);
        }
        $this->ref = $data['ref'];
        $this->description = $data['description'] ?? '';
        try {
            $this->hydrateRessources($data['ressources'] ?? []);
        } catch (\Throwable $t) {
            error_log('data.ressources : ' . json_encode($data['ressources']));
            throw new \Exception('Ressources du owner corompues', 500);
        }
    }

    private function hydrateRessources(array $ressources)
    {
        $this->ressources = [];
        foreach ($ressources as $ressource) {
            $this->ressources[] = new Ressource($ressource);
        }
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function hasRessource(string $ressourceName)
    {
        /** @var \App\Ressource $ressource */
        foreach ($this->ressources as $ressource) {
            if ($ressource->is($ressourceName)) {
                return true;
            }
        }
        return false;
    }
}
