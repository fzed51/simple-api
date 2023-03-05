<?php

namespace SimpleApi\Utilities;

use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{

    public function testUuid()
    {
        $g = new Generator();
        $uuid = $g->uuid();
        self::assertMatchesRegularExpression(
            "/[\da-z]{8}-[\da-z]{4}-[\da-z]{4}-[\da-z]{4}-[\da-z]{12}/",
            $uuid
        );
    }
}
