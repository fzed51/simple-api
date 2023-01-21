<?php
declare(strict_types=1);

namespace Test\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Contrainte de validation
 */
class Validation extends Constraint
{
    /**
     * @inheritDoc
     * @param mixed $other
     * @return bool
     */
    protected function matches(mixed $other): bool
    {
        if ($other === true) {
            return true;
        }
        if (is_string($other)) {
            return false;
        }
        $this->fail($other, "is not a result of validation");
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return "is a validated data";
    }
}
