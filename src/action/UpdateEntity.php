<?php


namespace App\action;


class UpdateEntity extends EntityAccess
{

    public function __invoke(string $ref, $data): string
    {
        $stm = $this->pdo->prepare(<<<SQL
UPDATE entity SET
    updated = current_timestamp,
    data = ?
WHERE owner = ?
    AND ressource = ?
    AND ref = ?    
SQL
        );
        $owner = $this->owner->getRef();
        $res = $this->ressourceName;
        $stm->execute([$data, $owner, $res, $ref]);
        return $ref;
    }

}