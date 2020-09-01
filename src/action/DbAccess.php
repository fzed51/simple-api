<?php


namespace App\action;


use PDO;

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
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function isValidJson(string $json): bool
    {
        json_decode($json, false);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
