<?php


namespace App\Entity;

class Owner
{
    /** @var string */
    private $ref;
    /** @var string */
    private $description;
    /** @var \App\Ressource[] */
    private $ressources;

    /**
     * Owner constructor.
     * @param array{ref:string,description?:string,ressources?:string[]} $data
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * @param array{ref:string,description?:string,ressources?:string[]} $data
     * @throws \Exception
     */
    private function hydrate(array $data): void
    {
        if (!array_key_exists('ref', $data)) {
            error_log('Owner->hydrate, il n\'y as pas de ref dans data : ' . json_encode($data));
            throw new \Exception('Données du owner corompues', 500);
        }
        $this->ref = $data['ref'];
        $this->description = $data['description'] ?? '';
        try {
            $this->hydrateRessources($data['ressources'] ?? []);
        } catch (\Throwable $t) {
            error_log('Owner->hydrate, inpossible d\'instancier une ressource avec data.ressources : ' . json_encode($data['ressources']));
            throw new \Exception('Ressources du owner corompues', 500);
        }
    }

    /**
     * @param string[] $ressources
     */
    private function hydrateRessources(array $ressources): void
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

    /**
     * @param string $ressourceName
     * @return bool
     */
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
