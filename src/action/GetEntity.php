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
     * @return array<string,mixed>|null
     */
    public function __invoke(string $ref): ?array
    {
        $stm = $this->pdo->prepare(<<<SQL
SELECT ref, created, updated, data 
FROM entity
WHERE client = ? 
  AND ressource = ?
  AND ref = ?
SQL
        );
        $client = $this->client->getRef();
        $res = $this->ressourceName;
        $stm->execute([$client, $res, $ref]);
        $fetch = $stm->fetch(\PDO::FETCH_ASSOC);
        if ($fetch === false) {
            return null;
        }
        return $this->format($fetch);
    }
}
