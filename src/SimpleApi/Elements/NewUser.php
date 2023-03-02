<?php
declare(strict_types=1);

namespace SimpleApi\Elements;

/**
 * Class pour les information de création d'un User
 */
class NewUser
{
    /**
     * Constructeur de NewUser
     * @param string $entity 10car
     * @param string $email 320car
     * @param string $pass 256car
     * @param string $salt 36car
     * @param bool $enable
     * @param string $enableToken 36car
     * @param string $enableLimit au format yyyy-mm-dd
     */
    public function __construct(
        readonly public string $entity,
        readonly public string $email,
        readonly public string $pass,
        readonly public string $salt,
        readonly public bool   $enable,
        readonly public string $enableToken,
        readonly public string $enableLimit
    ) {
    }

    /**
     * @param \SimpleApi\Elements\Entity $entity
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(Entity $entity, array $data)
    {
        return new self(
            $entity->uuid,
            $data['email'],
            $data['pass'],
            $data['salt'],
            $data['enable'],
            $data['enable_token'],
            $data['enable_limit']
        );
    }
}