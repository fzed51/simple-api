<?php
declare(strict_types=1);

namespace SimpleApi\Utilities;

use PHPUnit\Framework\TestCase;

/**
 * Test de Generator
 */
class GeneratorTest extends TestCase
{

    /** test de Uuid */
    public function testUuid(): void
    {
        $g = new Generator();
        $uuid = $g->uuid();
        self::assertMatchesRegularExpression(
            "/[\da-z]{8}-[\da-z]{4}-[\da-z]{4}-[\da-z]{4}-[\da-z]{12}/",
            $uuid
        );
    }

    /** test de Token */
    public function testToken(): void
    {
        $g = new Generator();
        $t = $g->token(32);
        self::assertIsString($t);
        self::assertMatchesRegularExpression('/[0-9a-z]{32}/', $t);
    }

}
