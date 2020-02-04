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

    public function setLimit(int $limit, int $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function __invoke(): array
    {
        if ($this->limit <= 0) {
            $stm = $this->statementWithoutLimit();
        } else {
            $stm = $this->statementWithLimit();
        }
        $owner = $this->owner->getRef();
        $res = $this->ressourceName;
        $stm->execute([$owner, $res]);
        return array_map(
            [$this, 'format'],
            $stm->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    private function statementWithoutLimit(): \PDOStatement
    {
        return $this->pdo->prepare(<<<SQL
SELECT ref, data 
FROM entity 
WHERE owner = ? 
  AND ressource = ?
ORDER BY created
SQL
        );
    }

    private function statementWithLimit(): \PDOStatement
    {
        $offset = max(0, $this->offset);
        $limit = max(1, $this->limit);
        $offset *= $limit;
        return $this->pdo->prepare(<<<SQL
SELECT ref, data 
FROM entity 
WHERE owner = ? 
  AND ressource = ?
ORDER BY created
LIMIT $offset, $limit
SQL
        );
    }

}