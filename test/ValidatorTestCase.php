<?php

namespace Test;

class ValidatorTestCase extends TestCase
{
    /**
     * methode d'assertion d'un Validator pour une donnée valide
     * @param mixed $current
     * @param string $message
     * @return void
     */
    protected static function assertIsValid(mixed $current, string $message = "")
    {
        if ($current === true) {
            self::assertTrue($data);
        }
    }

    /**
     * methode d'assertion d'un Validator pour une donnée non valide
     * @param mixed $current
     * @param string $message
     * @return void
     */
    protected static function assertIsNotValid(mixed $current, string $message = "")
    {

    }
}