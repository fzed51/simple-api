<?php


namespace App;

class ApiSecurity
{
    public function getUid()
    {
        do {
            $bytes = openssl_random_pseudo_bytes(24, $strong);
        } while (!$strong);
        return bin2hex($bytes);
    }
}
