<?php


namespace App\action;

/**
 * Class GetEntity
 * @package App\action
 */
class GetEntity extends EntityAccessRead
{

    /**
     * @param string $ref
     * @return array{ref:string,owner:string,created:string,updated:string,data:string}|null
     */
    public function __invoke(string $ref): ?array
    {
        $stm = $this->pdo->prepare(<<<SQL
SELECT ref, created, updated, data 
FROM entity
WHERE owner = ? 
  AND ressource = ?
  AND ref = ?
SQL
        );
        $owner = $this->owner->getRef();
        $res = $this->ressourceName;
        $stm->execute([$owner, $res, $ref]);
        $fetch = $stm->fetch(\PDO::FETCH_ASSOC);
        if ($fetch === false) {
            return null;
        }
        return $this->format($fetch);
    }
}
