<?php
declare(strict_types=1);

namespace SimpleApi\Validators;

use PHPUnit\Framework\TestCase;
use Test\Stubs\Validators;

/**
 * Test de UseValideStructure
 */
class UseValidStructureTest extends TestCase
{
    /**
     * test de IsArrayOf
     * @return void
     */
    public function testIsArrayOf(): void
    {
        self::assertTrue(Validators::isArrayOf([], "test"));
        self::assertTrue(Validators::isArrayOf([1, 2, 3], "test"));
        self::assertTrue(Validators::isArrayOf(
            ["a", "b", "c"],
            "test",
            (static fn($a, $b) => Validators::isText($a, $b, 10))(...)
        ));
        self::assertEquals("test n'est pas un tableau", Validators::isArrayOf("array", "test"));
        self::assertEquals("test n'est pas valide : l'élément 1 n'est pas du text", Validators::isArrayOf(
            ["a", 2],
            "test",
            (static fn($a, $b) => Validators::isText($a, $b, 10))(...)
        ));
    }

    /**
     * test de IsText
     * @return void
     */
    public function testIsText(): void
    {
        self::assertTrue(Validators::isText("lorem", "test", 10));
        self::assertEquals("test n'est pas du text", Validators::isText(10, "test", 10));
        self::assertEquals("test n'est pas valide car trop court (< 10 car)", Validators::isText("lorem", "test", 10, 20));
        self::assertEquals("test n'est pas valide car trop long (> 4 car)", Validators::isText("lorem", "test", 4));
    }

    /**
     * test de IsArrayWithKey
     * @return void
     */
    public function testIsArrayWithKey(): void
    {
        self::assertTrue(Validators::isArrayWithKey(['a' => 1, 'd' => 2], "test", ['a']));
        self::assertTrue(Validators::isArrayWithKey(['a' => 1, 'd' => 2], "test", ['a', 'd']));
        self::assertEquals(
            "test n'est pas valide, la clé b n'est pas définie.",
            Validators::isArrayWithKey(['a' => 1, 'd' => 2], "test", ['b'])
        );
    }

    /**
     * test de IsEntity
     * @return void
     */
    public function testIsEntity(): void
    {
        $entity1 = [
            'uid' => "15aecaac-52ec-463a-896a-8fd2e4ac5421",
            'title' => "entity",
            'ressources' => []
        ];
        self::assertTrue(Validators::isEntity($entity1, 'entity de test 1'));

        $entity2 = [
            'uid' => 123456789,
            'title' => "entity",
            'ressources' => []
        ];
        self::assertEquals(
            "entity de test 2 n'est pas valide : la clé uid n'est pas une UID",
            Validators::isEntity($entity2, 'entity de test 2')
        );

        $entity3 = [
            'uid' => "15aecaac-52ec-463a-896a-8fd2e4ac5421",
            'ressources' => []
        ];
        self::assertEquals(
            "entity de test 3 n'est pas valide, la clé title n'est pas définie.",
            Validators::isEntity($entity3, 'entity de test 3')
        );

        $entity4 = [
            'uid' => 123456789,
            'title' => "entity",
            'ressources' => "bad ressource"
        ];
        self::assertEquals(
            "entity de test 4 n'est pas valide : la clé uid n'est pas une UID, la clé ressources n'est pas un tableau",
            Validators::isEntity($entity4, 'entity de test 4')
        );
    }

    /**
     * test de IsUid
     * @return void
     */
    public function testIsUid(): void
    {
        self::assertTrue(Validators::isUid("15aecaac-52ec-463a-896a-8fd2e4ac5421", "test"));
        self::assertEquals("test n'est pas une UID", Validators::isUid("15ae-caac-52ec-463a-896a-8fd2-e4ac-5421", "test"));
        self::assertEquals("test n'est pas une UID", Validators::isUid(123456789, "test"));
    }
}
