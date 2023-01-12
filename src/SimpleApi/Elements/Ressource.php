<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

/**
 * Ressource pour les entity
 */
class Ressource
{
    /**
     * Constructeur
     */
    public function __construct()
    {
    }

    /**
     * @param array<string,mixed> $structure
     * @return self
     */
    public static function fromArray(array $structure): self
    {
        return new self();
    }
}
