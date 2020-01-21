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
        $owners = $this->getOwners();
        return new Owner($owners[0]);
    }

    /**
     * @param string $owner
     * @param string $ressource
     * @param mixed $data converti en json pour ecrire en base
     * @return string
     */
    protected function addEntity(string $owner, string $ressource, $data): string
    {
        $security = new ApiSecurity();
        $ref = $security->getUid();
        $json = json_encode($data);
        $this->dbInsert('entity', [
            'ref' => $ref,
            'owner' => $owner,
            'ressource' => $ressource,
            'data' => $json
        ]);
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
        $fetch = $this->dbSelectEtoile(
            'entity',
            "owner = '$owner' AND ressource = '$ressource' AND ref = '$ref'",
            1);
        return $fetch !== false ? $fetch : null;
    }
}