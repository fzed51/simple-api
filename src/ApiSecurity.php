<?php


namespace App;

use Exception;

class ApiSecurity implements SecurityTool
{
    const INTERN = 'e051041e-dba4-4877-97a5-87ea18710c31';

    /**
     * génère un identifiant unique
     * @return string
     */
    public function getUid(): string
    {
        do {
            $bytes = openssl_random_pseudo_bytes(32, $strong);
        } while (!$strong);
        return bin2hex($bytes);
    }

    public function hashPassWord(string $pass): string
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    public function testPassWord(string $pass, string $hash): bool
    {
        return password_verify($pass, $hash);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getIdClient(): string
    {
        $ip = $this->getIpAddress();
        $userAgent = $this->getUserAgent();
        if ($ip === null || $userAgent === null) {
            throw new Exception('Impossible d\'identifier l\'utilisateur');
        }
        return hash('sha256', $ip . $userAgent);
    }

    public function getPublicToken(string $private, string $client = ""): string
    {
        if (empty($client)) {
            $client = $this->getIdCalient();
        }
        return hash('sha256', $private . self::INTERN . $client);
    }


    public function testPublicToken(string $public, string $private, string $client = ""): bool
    {
        if (empty($client)) {
            $client = $this->getIdClient();
        }
        return $public === hash('sha256', $private . self::INTERN . $client);
    }

    /**
     * @return string|null
     */
    protected function getIpAddress(): ?string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return null;
    }

    /**
     * @return string|null
     */
    protected function getUserAgent(): ?string
    {
        if (!array_key_exists('HTTP_USER_AGENT', $_SERVER) || trim($_SERVER['HTTP_USER_AGENT']) === '') {
            return null;
        }
        return $_SERVER['HTTP_USER_AGENT'];
    }
}
