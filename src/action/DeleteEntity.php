<?php


namespace App\action;

class DeleteEntity extends EntityAccess
{

    public function __invoke(string $ref): bool
    {
        $client = $this->client->getRef();
        $res = $this->ressourceName;
        $count = $this->pdo->prepare(<<<SQL
SELECT count(*) 
FROM entity 
where client = ?
    AND ressource = ?
    AND ref = ?
SQL
        );
        $count->execute([$client, $res, $ref]);
        $before = (int)$count->fetchColumn();
        if ($before === 0) {
            return false;
        }
        $stm = $this->pdo->prepare(<<<SQL
DELETE FROM entity 
WHERE client = ?
    AND ressource = ?
    AND ref = ?
SQL
        );
        $stm->execute([$client, $res, $ref]);
        $count->execute([$ref]);
        $after = (int)$count->fetchColumn();
        return $after === 0;
    }
}
