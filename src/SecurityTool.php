<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 14:51
 */

namespace App;

interface SecurityTool
{
    public function getUid(): string;
    public function hashPassWord(string $pass): string;
    public function testPassWord(string $pass, string $hash): bool;
}
