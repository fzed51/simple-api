<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

/**
 * Class pour les information de création d'un User
 */
class NewUser
{
    /**
     * @param string $email email du user
     * @param string $pass mot de passe du user
     */
    public function __construct(
        readonly protected string $entity,
        readonly protected string $email,
        readonly protected string $pass,
    ) {
    }

    /**
     * @param \SimpleApi\Elements\Entity $entity
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(Entity $entity, array $data)
    {
        return new self($entity->uuid, "", "");
    }
}