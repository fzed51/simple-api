<?php
declare(strict_types=1);

namespace Test;

use Test\Constraint\NotValidation;
use Test\Constraint\Validation;

/**
 * Class de test de base pour les Validators
 */
class ValidatorTestCase extends TestCase
{
    /**
     * methode d'assertion d'un Validator pour une donnée valide
     * @param mixed $current
     * @param string $message
     * @return void
     */
    protected static function assertIsValid(mixed $current, string $message = ""): void
    {
        self::assertThat($current, new Validation(), $message);
    }

    /**
     * methode d'assertion d'un Validator pour une donnée non valide
     * @param string $expected
     * @param mixed $current
     * @param string $message
     * @return void
     */
    protected static function assertIsNotValid(string $expected, mixed $current, string $message = ""): void
    {
        self::assertThat($current, new NotValidation($expected), $message);
    }
}
