<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 04/07/2020
 * Time: 19:41
 */

namespace App\Entity;

use Exception;

class BaseUser
{
    /** @var string nom */
    protected $name;
    /** @var string email */
    protected $email;
    /** @var \App\Entity\Role[] liste de roles */
    protected $roles;
    /** @var string mot de passe */
    protected $pass;

    /**
     * BaseUser constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->hydrate($data);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected function hydrate($data)
    {
        $this::controleUser($data);
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->pass = $data['pass'];
        $this->hydrateRoles($data['roles']);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected static function controleUser($data)
    {
        if (!is_array($data)) {
            throw new Exception('Un user ne peut pas être initialisé avec un ' . gettype($data));
        }
        if (!array_key_exists('name', $data) || !self::isValidString($data['name'])) {
            throw new Exception("Le nom du user n'est pas valide");
        }
        if (!array_key_exists('email', $data) || !self::isValidString($data['email'])) {
            throw new Exception("L'e-mail' du user n'est pas valide");
        }
        if (!array_key_exists('pass', $data) || !self::isValidString($data['pass'])) {
            throw new \Exception("Le mot de passe du user n'est pas valide");
        }
        if (!array_key_exists('roles', $data) || !is_array($data['roles'])) {
            throw new Exception("Le mot de passe du user n'est pas valide");
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

    /**
     * @param array $dataroles
     * @throws \Exception
     */
    private function hydrateRoles(array $dataroles)
    {
        $this->roles = [];
        foreach ($dataroles as $data) {
            $this->roles[] = new Role($data);
        }
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
     * @param string $rolename
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
     * @return \App\Entity\Role[]
     */
    public function getRoles(): array
    {
        return array_map(static function (Role $role) {
            return $role->getName();
        }, $this->roles);
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }
}
