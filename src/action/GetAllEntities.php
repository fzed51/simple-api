<?php


namespace App\action;

class GetAllEntities extends EntityAccessRead
{

    /**
     * limite de la requête
     * @var int
     */
    protected $limit;
    /**
     * itération de la limite
     * @var int
     */
    protected $offset;

    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        $this->limit = 0;
        $this->offset = 0;
    }

    /**
     * @param int $limit
     * @param int $offset
     */
    public function setLimit(int $limit, int $offset = 0): void
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return  array<string,mixed>[]
     */
    public function __invoke(): array
    {
        if ($this->limit <= 0) {
            $stm = $this->statementWithoutLimit();
        } else {
            $stm = $this->statementWithLimit();
        }
        $client = $this->client->getRef();
        $res = $this->ressourceName;
        $stm->execute([$client, $res]);
        return array_map(
            [$this, 'format'],
            $stm->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    /**
     * @return \PDOStatement
     */
    private function statementWithoutLimit(): \PDOStatement
    {
        return $this->pdo->prepare(<<<SQL
SELECT ref, data 
FROM entity 
WHERE client = ? 
  AND ressource = ?
ORDER BY created
SQL
        );
    }

    /**
     * @return \PDOStatement
     */
    private function statementWithLimit(): \PDOStatement
    {
        $offset = max(0, $this->offset);
        $limit = max(1, $this->limit);
        $offset *= $limit;
        return $this->pdo->prepare(<<<SQL
SELECT ref, data 
FROM entity 
WHERE client = ? 
  AND ressource = ?
ORDER BY created
LIMIT $offset, $limit
SQL
        );
    }
}
