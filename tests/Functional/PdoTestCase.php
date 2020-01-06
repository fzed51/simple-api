<?php


namespace Tests\Functional;


use Helper\PDOFactory;
use PHPUnit\Framework\TestCase;

class PdoTestCase extends TestCase
{
    /** @var \PDO */
    private $pdo;

    protected function getPdo(): \PDO
    {
        if (null === $this->pdo) {
            PDOFactory::$case = \PDO::CASE_LOWER;
            PDOFactory::$fetchMode = \PDO::FETCH_ASSOC;
            $pdo = PDOFactory::sqlite();
            $pdo->exec(<<<SQL
CREATE TABLE entity (
    ref text primary key , 
    owner text not null,     
    ressource text not null,
    created   text not null default current_timestamp,
    updated   text,
    data      text not null)
SQL
            );
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }
}