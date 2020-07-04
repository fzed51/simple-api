<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 03/06/2020
 * Time: 21:16
 */

namespace App\Entity;


class NewUser extends User
{

    /**
     * @param $data
     * @throws \Exception
     */
    protected function hydrate($data): void
    {
        $this->controlNewUser($data);
        parent::hydrate($data);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected function controlNewUser($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Un nouveau user ne peut pas être initialisé avec un ' . gettype($data));
        }
        if (!array_key_exists('pass', $data) || !self::isValidString($data['pass'])) {
            throw new \Exception("Le mot de passe n'a pas de format valide");
        }
        if (!array_key_exists('confirm', $data) || !self::isValidString($data['confirm'])) {
            throw new \Exception("La confirmation du mot de passe n'a pas de format valide");
        }
        if ($data['confirm'] !== $data['pass']) {
            throw new \Exception("Le mot de passe n'est pas confirmé");
        }
    }

}