<?php


namespace App;

class ApiSecurity
{
    /**
     * génère un identifiant unique
     * @return string
     */
    public function getUid(): string
    {
        do {
            $bytes = openssl_random_pseudo_bytes(24, $strong);
        } while (!$strong);
        return bin2hex($bytes);
    }
}
