<?php
declare(strict_types=1);

namespace SimpleApi\Validators;

use Test\Stubs\Validators;
use Test\ValidatorTestCase;

/**
 * Test de UseValideStructure
 */
class UseValidStructureTest extends ValidatorTestCase
{
    /**
     * test de IsArrayOf
     * @return void
     */
    public function testIsArrayOf(): void
    {
        self::assertIsValid(Validators::isArrayOf([], "test"));
        self::assertIsValid(Validators::isArrayOf([1, 2, 3], "test"));
        self::assertIsValid(Validators::isArrayOf(
            ["a", "b", "c"],
            "test",
            (static fn($a, $b) => Validators::isText($a, $b, 10))(...)
        ));
        self::assertIsNotValid("test n'est pas un tableau", Validators::isArrayOf("array", "test"));
        self::assertIsNotValid("test n'est pas valide : l'élément 1 n'est pas du text", Validators::isArrayOf(
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
        self::assertIsValid(Validators::isText("lorem", "test", 10));
        self::assertIsNotValid("test n'est pas du text", Validators::isText(10, "test", 10));
        self::assertIsNotValid("test n'est pas valide car trop court (< 10 car)", Validators::isText("lorem", "test", 10, 20));
        self::assertIsNotValid("test n'est pas valide car trop long (> 4 car)", Validators::isText("lorem", "test", 4));
    }

    /**
     * test de IsArrayWithKey
     * @return void
     */
    public function testIsArrayWithKey(): void
    {
        self::assertIsValid(Validators::isArrayWithKey(['a' => 1, 'd' => 2], "test", ['a']));
        self::assertIsValid(Validators::isArrayWithKey(['a' => 1, 'd' => 2], "test", ['a', 'd']));
        self::assertIsNotValid(
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
            'resources' => []
        ];
        self::assertIsValid(Validators::isEntity($entity1, 'entity de test 1'));

        $entity2 = [
            'uid' => 123456789,
            'title' => "entity",
            'resources' => []
        ];
        self::assertIsNotValid(
            "entity de test 2 n'est pas valide : la clé uid n'est pas une UID",
            Validators::isEntity($entity2, 'entity de test 2')
        );

        $entity3 = [
            'uid' => "15aecaac-52ec-463a-896a-8fd2e4ac5421",
            'resources' => []
        ];
        self::assertIsNotValid(
            "entity de test 3 n'est pas valide, la clé title n'est pas définie.",
            Validators::isEntity($entity3, 'entity de test 3')
        );

        $entity4 = [
            'uid' => 123456789,
            'title' => "entity",
            'resources' => "bad ressource"
        ];
        self::assertIsNotValid(
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
        self::assertIsValid(Validators::isUid("15aecaac-52ec-463a-896a-8fd2e4ac5421", "test"));
        self::assertIsNotValid("test n'est pas une UID", Validators::isUid("15ae-caac-52ec-463a-896a-8fd2-e4ac-5421", "test"));
        self::assertIsNotValid("test n'est pas une UID", Validators::isUid(123456789, "test"));
    }
}
