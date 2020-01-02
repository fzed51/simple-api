<?php


namespace App;


class Ressource
{
    /** @var string */
    private $name;

    /**
     * Ressource constructor.
     * @param mixed $ressource
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
     * @return string
     */
    public function is($name): bool
    {
        return $this->name === $name;
    }
}