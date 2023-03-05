<?php

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
        $bytes = openssl_random_pseudo_bytes(16, $safe);
        if (!$safe) {
            throw new RuntimeException("Impossible de generer une UUID");
        }
        $hex = bin2hex($bytes);
        $sub = str_split($hex, 4);
        return
            $sub[0] . $sub[1]
            . "-" . $sub[2]
            . "-" . $sub[3]
            . "-" . $sub[4]
            . "-" . $sub[5] . $sub[6]. $sub[7];
    }
}