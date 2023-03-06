<?php
declare(strict_types=1);

namespace SimpleApi\Utilities;

/**
 * Class conteant les méthode de sécurité
 */
class Security
{
    /**
     * Hash un mot de pass
     * @param string $clearPass
     * @param string $salt
     * @return string
     */
    public function hashPass(string $clearPass, string $salt): string
    {
        return password_hash(
            hash(
                "sha512",
                $clearPass . $salt
            ),
            PASSWORD_BCRYPT
        );
    }

    /**
     * Vérifi un mot de passe
     * @param string $testPass
     * @param string $salt
     * @param string $hashPass
     * @return bool
     */
    public function testPass(string $testPass, string $salt, string $hashPass): bool
    {
        return password_verify(
            hash(
                "sha512",
                $testPass . $salt
            ),
            $hashPass
        );
    }
}