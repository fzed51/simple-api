<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 31/05/2020
 * Time: 19:47
 */

namespace App\Entity;

use Exception;

class Session extends User
{

    /**
     * @var string(64)
     */
    protected $sessionPrivateToken;
    /**
     * @var string(64)
     */
    protected $sessionPublicToken;
    /**
     * @var string Date
     */
    protected $sessionExpiration;

    /**
     * token de session public
     * @return string
     */
    public function getSessionPublicToken(): string
    {
        return $this->sessionPublicToken;
    }

    /**
     * date d'expiration de la session
     * @return string
     */
    public function getSessionExpiration(): string
    {
        return $this->sessionExpiration;
    }


    /**
     * @param $data
     * @throws \Exception
     */
    protected function hydrate($data): void
    {
        $this->controlSession($data);
        parent::hydrate($data);
        $this->sessionPrivateToken = $data['session_private_token'];
        $this->sessionPublicToken = $data['session_public_token'];
        $this->sessionExpiration = $data['session_expiration'];
    }

    /**
     * @param $data
     * @throws \Exception
     */
    private function controlSession($data): void
    {
        if (!is_array($data)) {
            throw new Exception('Une session ne peut pas être initialisé avec un ' . gettype($data));
        }
        if (!array_key_exists('session_public_token', $data)
            || !self::isValidStringLength($data['session_public_token'], 64)
        ) {
            throw new Exception("Le token de session n'a pas de format valide");
        }
        if (!array_key_exists('session_expiration', $data)
            || !(self::isValidDateString($data['session_expiration'])
                || isnull($data['session_expiration']))
        ) {
            throw new Exception("Le date de validité de session n'a pas de format valide");
        }
    }

    /**
     * @param $str
     * @return bool
     */
    protected static function isValidStringLength($str, $length): bool
    {
        return is_string($str) && strlen($str) === $length;
    }

    /**
     * @param $str
     * @return bool
     */
    protected static function isValidDateString($str): bool
    {
        try {
            new \DateTime($str);
        } catch (\Exception $e) {
            return false;
        }
        return is_string($str) && !empty($str);
    }
}
