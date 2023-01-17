<?php

namespace Test\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Contrainte de validation
 */
class NotValidation extends Constraint
{
    public function __construct(private readonly string $expected)
    {
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return "is not a validated data";
    }

    /**
     * @inheritDoc
     * @param mixed $other
     * @return bool
     */
    protected function matches(mixed $other): bool
    {
        if (is_string($other)) {
            return $other === $this->expected;
        } elseif ($other === true) {
            return false;
        }
        $this->fail($other, "is not a result of validation");
    }
}