<?php
declare(strict_types=1);

namespace SimpleApi\Utilities;

use RuntimeException;

/**
 * Classe permetant de générer des chaine aléatoire
 */
class Generator
{
    /**
     * uuid donne une UUID aléatoire
     * @return string
     */
    public function uuid(): string
    {
        /** @noinspection CryptographicallySecureRandomnessInspection */
        $bytes = openssl_random_pseudo_bytes(16, $safe);
        if (!$safe) {
            throw new RuntimeException("Impossible de générer une UUID");
        }
        $hex = bin2hex($bytes);
        $sub = str_split($hex, 4);
        return
            $sub[0] . $sub[1]
            . "-" . $sub[2]
            . "-" . $sub[3]
            . "-" . $sub[4]
            . "-" . $sub[5] . $sub[6] . $sub[7];
    }

    /**
     * token donne un token constituer de 0-9 et a-z
     * @param int $length
     * @return string
     */
    public function token(int $length): string
    {
        /** @noinspection CryptographicallySecureRandomnessInspection */
        $bytes = openssl_random_pseudo_bytes($length, $safe);
        if (!$safe) {
            throw new RuntimeException("Impossible de générer un token");
        }
        $hex = bin2hex($bytes);
        $b36 = base_convert($hex, 16, 36);
        return substr($b36, 0, $length);
    }
}
