<?php
declare(strict_types=1);

namespace SimpleApi;

/**
 * Class abstraite de base pour les actions
 */
abstract class Action
{
    /**
     * Méthode de base permettant d'exécuter cette action
     * @return void
     */
    abstract public function execute():void;
}