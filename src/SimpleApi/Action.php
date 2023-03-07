<?php
declare(strict_types=1);

namespace SimpleApi;

use LogicException;

/**
 * Class abstraite de base pour les actions
 */
abstract class Action
{

    public bool $initializedValue = false;
    public mixed $value;

    /**
     * Méthode de base permettant d'exécuter cette action
     * @return void
     */
    abstract public function run(): void;

    public function getValue(): mixed
    {
        if (!$this->initializedValue) {
            throw new LogicException("La valeur de " . static::class . " n'a pas été initialisée");
        }
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return void
     */
    protected function setValue(mixed $value): void
    {
        $this->initializedValue = true;
        $this->value = $value;
    }

    protected function clearValue(): void
    {
        $this->initializedValue = false;
        $this->value = null;
    }
}