<?php


namespace App\action;

use App\ApiSecurity;

class CreateEntity extends EntityAccess
{
    public function __invoke(string $json): string
    {
        if (!$this->isValidJson($json)) {
            throw new \InvalidArgumentException('Le JSON passé en paramètre à ' . __CLASS__ . ' n\'est pas  valide', 400);
        }
        $security = new ApiSecurity();
        $stm = $this->pdo->prepare(<<<SQL
INSERT INTO entity 
    (ref, client, ressource, data) 
    values (?,?,?,?)
SQL
        );
        $ref = $security->getUid();
        $client = $this->client->getRef();
        $res = $this->ressourceName;
        $data = $json;
        $stm->execute([$ref, $client, $res, $data]);
        return $ref;
    }
}
