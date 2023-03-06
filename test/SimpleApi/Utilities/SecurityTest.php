<?php
declare(strict_types=1);

namespace SimpleApi\Utilities;

use PHPUnit\Framework\TestCase;

/**
 * Test de Security
 */
class SecurityTest extends TestCase
{

    /**
     * test de TestPass
     * @return void
     */
    public function testTestPass(): void
    {
        $pass = "azerty123456";
        $salt = "azertyuiopqs";
        $s = new Security();
        $hash = $s->hashPass($pass, $salt);
        self::assertTrue($s->testPass($pass, $salt, $hash));
    }

    /**
     * test de HashPass
     * @return void
     */
    public function testHashPass(): void
    {
        $pass = "azerty123456";
        $salt = "azertyuiopqs";
        $s = new Security();
        $hash = $s->hashPass($pass, $salt);
        self::assertNotSame($pass, $hash);
        self::assertNotSame($salt, $hash);
        self::assertNotSame($pass . $salt, $hash);
        self::assertNotSame($salt . $pass, $hash);
    }
}
