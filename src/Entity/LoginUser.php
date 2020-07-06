<?php


namespace App\Entity;

/**
 * Class LoginUser
 * @package App\Entity
 */
class LoginUser
{
    /** @var string email */
    protected $email;
    /** @var string mot de passe */
    protected $pass;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    public function __construct($data)
    {
        $this->hydrate($data);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected function hydrate($data): void
    {
        $this::controlLoginUser($data);
        $this->email = $data['email'];
        $this->pass = $data['pass'];
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public static function controlLoginUser($data): void
    {
        if (!is_array($data)) {
            throw new \Exception('Un user ne peut pas être initialisé avec un ' . gettype($data));
        }
        if (!array_key_exists('email', $data) || !self::isValidString($data['email'])) {
            throw new \Exception("L'e-mail' du user n'est pas valide");
        }
        if (!array_key_exists('pass', $data) || !self::isValidString($data['pass'])) {
            throw new \Exception("Le mot de passe du user n'est pas valide");
        }
    }

    /**
     * @param $str
     * @return bool
     */
    protected static function isValidString($str): bool
    {
        return is_string($str) && !empty($str);
    }
}