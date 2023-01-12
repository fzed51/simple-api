<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

use SimpleApi\Validators\UseValideStructure;

/**
 * Objet représantant une Entity / App cliente
 */
class Entity
{

    use UseValideStructure;

    /**
     * @param string $uuid
     * @param string $title
     * @param array<Ressource> $ressources
     */
    public function __construct(
        readonly public string $uuid,
        readonly public string $title,
        readonly public array  $ressources,
    ) {
    }

    /**
     * @param array<string,mixed> $structure
     * @return self
     */
    public static function fromArray(array $structure): self
    {
        self::isArrayOf($structure, "la config");
        return new self(
            $structure['uid'],
            $structure['title'],
            $structure['ressources']
        );
    }
}
