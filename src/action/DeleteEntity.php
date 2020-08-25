<?php


namespace App\action;

class DeleteEntity extends EntityAccess
{

    public function __invoke(string $ref): bool
    {
        $owner = $this->owner->getRef();
        $res = $this->ressourceName;
        $count = $this->pdo->prepare(<<<SQL
SELECT count(*) 
FROM entity 
where owner = ?
    AND ressource = ?
    AND ref = ?
SQL
        );
        $count->execute([$owner, $res, $ref]);
        $before = (int)$count->fetchColumn();
        if ($before === 0) {
            return false;
        }
        $stm = $this->pdo->prepare(<<<SQL
DELETE FROM entity 
WHERE owner = ?
    AND ressource = ?
    AND ref = ?
SQL
        );
        $stm->execute([$owner, $res, $ref]);
        $count->execute([$ref]);
        $after = (int)$count->fetchColumn();
        return $after === 0;
    }
}
