<?php


namespace App\Entity;

/**
 * Class Ressource
 * @package App
 */
class Ressource
{
    /** @var string */
    private $name;

    /**
     * Ressource constructor.
     * @param string $ressource
     */
    public function __construct(string $ressource)
    {
        $this->name = $ressource;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function is(string $name): bool
    {
        return $this->name === $name;
    }
}
