<?php
declare(strict_types=1);

namespace SimpleApi;

use Helper\PdoQueryable;
use PDO;

/**
 * Class abstraite de base pour les actions avec acces à la base de donnée
 */
abstract class ActionPdo extends Action
{
    use PdoQueryable;

    /**
     * Constructeur de ActionPdo
     * @param \PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->setPdo($pdo);
    }
}