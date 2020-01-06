<?php


namespace Tests\Functional;


use App\ApiSecurity;
use App\Owner;

class ActionTestCase extends PdoTestCase
{
    protected function getOwners(): array
    {
        return [
            [
                'ref' => '104a9144-8a98-4eea-b6a3-677e93cfb6f6',
                'description' => 'test',
                'ressources' => ['item']
            ]
        ];
    }

    protected function getOwner(): Owner
    {
        return new Owner([
            'ref' => '104a9144-8a98-4eea-b6a3-677e93cfb6f6',
            'description' => 'test',
            'ressources' => ['item']
        ]);
    }

    /**
     * @param string $owner
     * @param string $ressource
     * @param mixed $data converti en json pour ecrire en base
     * @return string
     */
    protected function addEntity(string $owner, string $ressource, $data)
    {
        $security = new ApiSecurity();
        $ref = $security->getUid();
        $pdo = $this->getPdo();
        $stm = $pdo->prepare(<<<SQL
INSERT INTO entity 
    (ref, owner, ressource, data) 
    values (?,?,?,?)
SQL
        );
        $json = json_encode($data);
        $stm->execute([$ref, $owner, $ressource, $json]);
        return $ref;
    }


    /**
     * @param string $owner
     * @param string $ressource
     * @param string $ref
     * @return array|null
     */
    protected function getEntity(string $owner, string $ressource, string $ref): ?array
    {
        $pdo = $this->getPdo();
        $stm = $pdo->prepare(<<<SQL
SELECT * 
FROM entity 
WHERE owner = ?
    AND ressource = ?
    AND ref = ?
SQL
        );
        $stm->execute([$owner, $ressource, $ref]);
        $fetch = $stm->fetch(\PDO::FETCH_ASSOC);
        return $fetch !== false ? $fetch : null;
    }
}