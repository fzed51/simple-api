<?php


namespace App;


class ApiSecurity implements SecurityTool
{
    public function getUid(): string
    {
        do {
            $bytes = openssl_random_pseudo_bytes(24, $strong);
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
}