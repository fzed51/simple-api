<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 03/06/2020
 * Time: 21:16
 */

namespace App\Entity;


class NewUser extends BaseUser
{

    /**
     * @param $data
     * @throws \Exception
     */
    protected function hydrate($data): void
    {
        parent::hydrate($data);
        $this->controlNewUser($data);
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
        if (!array_key_exists('confirm', $data) || !self::isValidString($data['confirm'])) {
            throw new \Exception("La confirmation du mot de passe n'a pas de format valide");
        }
        if ($data['confirm'] !== $data['pass']) {
            throw new \Exception("Le mot de passe n'est pas confirmé");
        }
    }

}