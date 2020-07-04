<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 11:23
 */

namespace App\Entity;

/*
 * structure
 * {
 *  ref : string // uid,
 *  name : string // nom du user
 *  email : string // email du user
 *  pass : string // hash du mot de passe
 *  roles : Role[] // liste de role andossé par l'utilisateur
 * }
 */

class User
{

    /** @var string uid */
    protected $ref;
    /** @var string nom */
    protected $name;
    /** @var string email */
    protected $email;
    /** @var string hash du pass */
    protected $pass;
    /** @var \App\Role[] liste de roles */
    protected $roles;

    public function __construct($data)
    {
        $this->hydrate($data);

    }

    protected function hydrate($data)
    {
        $this->controleUser($data);
        $this->ref = $data['ref'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->pass = $data['pass'];
        $this->hydrateRoles($data['roles']);
    }

    private function hydrateRoles(array $dataroles)
    {
        $this->roles = [];
        foreach ($dataroles as $data) {
            $this->roles[] = new Role($data);
        }
    }

    protected static function isValidString($str): bool
    {
        return is_string($str) && !empty($str);
    }

    public static function controleUser($data, bool $refRequired = true)
    {
        if (!is_array($data)) {
            throw new \Exception('Un user ne peut pas être initialisé avec un ' . gettype($data));
        }
        if ($refRequired && (!array_key_exists('ref', $data) || !self::isValidString($data['ref']))) {
            throw new \Exception("La ref du user n'est pas valide");
        }
        if (!array_key_exists('name', $data) || !self::isValidString($data['name'])) {
            throw new \Exception("Le nom du user n'est pas valide");
        }
        if (!array_key_exists('email', $data) || !self::isValidString($data['email'])) {
            throw new \Exception("L'e-mail' du user n'est pas valide");
        }
        if (!array_key_exists('pass', $data) || !self::isValidString($data['pass'])) {
            throw new \Exception("Le mot de passe du user n'est pas valide");
        }
        if (!array_key_exists('roles', $data) || !is_array($data['roles'])) {
            throw new \Exception("Le mot de passe du user n'est pas valide");
        }
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return boolean
     */
    public function hasRoles(string $rolename): bool
    {
        foreach ($this->roles as $role) {
            if ($role->is($rolename)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return \App\Role[]
     */
    public function getRoles(): array
    {
        return array_map(static function (Role $role) {
            return $role->getName();
        }, $this->roles);
    }

}