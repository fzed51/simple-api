<?php


namespace Tests\Functional;


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
}