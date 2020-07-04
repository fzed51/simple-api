<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 22:51
 */

namespace App\Entity;


class Role
{

    protected $name;

    public function __construct($data)
    {
        $this->hydrate($data);
    }

    private function hydrate($data)
    {
        if (!is_string($data) || empty($data)) {
            $datastr = var_export($data, true);
            throw new \Exception("le role {$datastr} n'est pas valide");
        }
        $this->name = $data;
    }

    public function is(string $rolename): bool
    {
        return $this->name === $rolename;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}