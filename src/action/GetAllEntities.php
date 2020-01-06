<?php


namespace App\action;


class GetAllEntities extends EntityAccessRead
{

    public function __invoke()
    {
        $stm = $this->pdo->prepare(<<<SQL
SELECT ref, data 
FROM entity 
WHERE owner = ? 
  AND ressource = ?
ORDER BY created
SQL
        );
        $owner = $this->owner->getRef();
        $res = $this->ressourceName;
        $stm->execute([$owner, $res]);
        return array_map(
            [$this, 'format'],
            $stm->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

}