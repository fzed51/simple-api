<?php


namespace App\action;

class UpdateEntity extends EntityAccess
{

    public function __invoke(string $ref, string $json): string
    {
        if (!$this->isValidJson($json)) {
            throw new \InvalidArgumentException('Le JSON passé en paramètre à ' . __CLASS__ . ' n\'est pas  valide', 400);
        }
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
        $stm->execute([$json, $owner, $res, $ref]);
        return $ref;
    }
}
