<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

use SimpleApi\Validators\UseValidStructure;

/**
 * Ressource pour les entity
 */
class Resource
{

    use UseValidStructure;

    /**
     * Constructeur
     * @param string $name
     * @param array<string> $fields
     */
    public function __construct(
        readonly public string $name,
        readonly public array  $fields,
    ) {
    }

    /**
     * @param array<string,mixed> $structure
     * @return self
     */
    public static function fromArray(array $structure): self
    {
        $err = self::isResource($structure, "la ressource");
        if ($err !== true) {
            throw new \RuntimeException($err);
        }
        return new self(
            $structure['name'],
            $structure['fields']
        );
    }
}
