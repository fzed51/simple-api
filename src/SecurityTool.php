<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 14:55
 */

namespace App;


class SecurityTool implements SecurityToolBox
{

    public function hashPassWord(string $pass): string
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    public function testPassWord(string $pass, string $hash): bool
    {
        return password_verify($pass, $hash);
    }
}