<?php
declare(strict_types=1);

namespace Test\Elements;

use SimpleApi\Elements\Resource;
use Test\TestCase;

/**
 * Test de la class Resource
 */
class ResourceTest extends TestCase
{
    /**
     * Test de FromArray
     */
    public function testFromArray(): void
    {
        $r = Resource::fromArray([
            "name" => "resource_name",
            "fields" => [
                "field1",
                "field2"
            ]
        ]);
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(Resource::class, $r);
    }

    /**
     * test de from bad name in array
     */
    public function testFromBadNameInArray(): void
    {
        $this->expectDeprecationMessage("la ressource n'est pas valide : la clé nom n'est pas du text");
        Resource::fromArray([
            "name" => [],
            "fields" => [
                "field1",
                "field2"
            ]
        ]);
    }

    /**
     * test de from bad name in array
     */
    public function testFromBadFieldsInArray(): void
    {
        $this->expectDeprecationMessage("la ressource n'est pas valide : la clé champs n'est pas un tableau");
        Resource::fromArray(["name" => "resource", "fields" => "field1 , field2"]);
    }
}
