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
create table entity (
    ref text primary key , 
    owner text not null,     
    ressource text not null,
    created   text not null default current_timestamp,
    updated   text,
    data      text not null)
SQL
            );
            $pdo->exec(<<<SQL
create table user (
    ref                   text not null,
    owner                 text not null,
    name                  text not null,
    email                 text not null,
    pass                  text not null,
    role                  text not null,
    created               text not null default current_timestamp,
    updated               text,
    session_private_token text,                  
    session_public_token  text,                  
    session_expiration     text                 
)
SQL
            );
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }


    /**
     * @param string $table
     * @param string $where
     * @param int $limit
     * @return array|array[]|null
     */
    protected function dbSelectEtoile(string $table, string $where = '', int $limit = 0):?array
    {
        return $this->dbSelect(['*'], $table, $where, $limit);
    }

    /**
     * @param array $fields
     * @param string $table
     * @param string $where
     * @param int $limit
     * @return array|array[]|null
     */
    protected function dbSelect(array $fields, string $table, string $where = '', int $limit = 0):?array
    {
        $fieldsStr = implode(', ', $fields);
        if (!empty($where)) {
            $where = 'where ' . $where;
        }
        $limitstr = '';
        if ($limit > 0) {
            $limitstr = 'limit ' . $limit;
        }
        $rqSql = "select $fieldsStr from $table $where order by 1 $limitstr";
        $stm = $this->getPdo()->prepare($rqSql);
        if ($stm === false) {
            throw new \RuntimeException("Requete non valide : $rqSql");
        }
        if (!$stm->execute()) {
            throw new \RuntimeException("Un problème est survenu lors de l'execution de la requete : $rqSql");
        }
        if ($limit === 1) {
            $result = $stm->fetch(\PDO::FETCH_ASSOC);
            return false !== $result ? $result : null;
        } else {
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    /**
     * @param string $table
     * @param array $data
     */
    protected function dbInsert(string $table, array $data): void
    {
        $columns = [];
        $values = [];
        foreach ($data as $column => $value) {
            $columns[] = $column;
            $values[] = $value;
        }
        $columnsStr = implode(', ', $columns);
        $patternStr = implode(', ', array_fill(0, count($values), '?'));
        $rqSql = "insert into $table ($columnsStr) values ($patternStr)";
        $stm = $this->getPdo()->prepare($rqSql);
        if ($stm === false) {
            throw new \RuntimeException("Requete non valide : $rqSql");
        }
        if (!$stm->execute($values)) {
            throw new \RuntimeException("Un problème est survenu lors de l'execution de la requete : $rqSql");
        }
    }

    /**
     * @param string $table
     * @param array $data
     * @param string $where
     */
    protected function dbUpdate(string $table, array $data, string $where): void
    {
        $set = [];
        $values = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            $values[] = $value;
        }
        $set = implode(', ', $set);
        $rqSql = "update $table set $set where $where";
        $stm = $this->getPdo()->prepare($rqSql);
        if ($stm === false) {
            throw new \RuntimeException("Requete non valide : $rqSql");
        }
        if (!$stm->execute($values)) {
            throw new \RuntimeException("Un problème est survenu lors de l'execution de la requete : $rqSql");
        }
    }

    /**
     * compte le nombre d'enregistrement dans une table
     * @param string $table
     * @param string $where
     * @return int
     */
    protected function dbCount(string $table, string $where = ''): int
    {
        $rep = $this->dbSelect(['count(*) as nb'], $table, $where, 1);
        return (int)($rep['nb'] ?? 0);
    }
}