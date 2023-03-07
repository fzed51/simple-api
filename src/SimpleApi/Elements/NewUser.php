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
     * @param string $email 320car
     * @param string $pass 256car
     */
    public function __construct(
        readonly public string $email,
        readonly public string $pass,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        return new self($data['email'], $data['pass']);
    }
}