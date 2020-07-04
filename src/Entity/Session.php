<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 31/05/2020
 * Time: 19:47
 */

namespace App\Entity;


class Session extends User
{

    protected $token;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected function hydrate($data): void
    {
        $this->controlSession($data);
        parent::hydrate($data);
        $this->token = $data['token'];
    }

    /**
     * @param $data
     * @throws \Exception
     */
    private function controlSession($data): void
    {
        if (!is_array($data)) {
            throw new \Exception('Une session ne peut pas être initialisé avec un ' . gettype($data));
        }
        if (!array_key_exists('token', $data) || !self::isValidString($data['token'])) {
            throw new \Exception("Le token de session n'a pas de format valide");
        }
    }


}