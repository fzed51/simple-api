<?php


namespace App\action;

class DbAccess
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * DbAccess constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
