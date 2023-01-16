<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

use SimpleApi\Validators\UseValidStructure;

/**
 * Ressource pour les entity
 */
class Ressource
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
        $err = self::isArrayOf($structure, "les ressources", self::isResource(...));
        if ($err !== true) {
            throw new \RuntimeException($err);
        }
        return new self(
            $structure['name'],
            $structure['fields']
        );
    }
}
