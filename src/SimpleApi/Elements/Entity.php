<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

/**
 * Objet représantant une Entity / App cliente
 */
class Entity
{

    /**
     * @param string $uuid
     * @param string $title
     * @param array<Ressource> $ressources
     */
    public function __construct(
        readonly public string $uuid,
        readonly public string $title,
        readonly public array $ressources,
    )
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