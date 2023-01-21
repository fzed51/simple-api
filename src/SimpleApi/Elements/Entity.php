<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

use RuntimeException;
use SimpleApi\Validators\UseValidStructure;

/**
 * Objet représantant une Entity / App cliente
 */
class Entity
{

    use UseValidStructure;

    /**
     * @param string $uuid
     * @param string $title
     * @param array<Resource> $resources
     */
    public function __construct(
        readonly public string $uuid,
        readonly public string $title,
        readonly public array  $resources,
    ) {
    }

    /**
     * @param array<string,mixed> $structure
     * @return self
     */
    public static function fromArray(array $structure): self
    {
        $err = self::isArrayOf($structure, "la config", self::isEntity(...));
        if ($err !== true) {
            throw new RuntimeException($err);
        }
        $resources = array_map(
            static fn($r) => Resource::fromArray($r),
            $structure['resources']
        );
        return new self(
            $structure['uid'],
            $structure['title'],
            $resources
        );
    }
}
