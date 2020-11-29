<?php

namespace Tests\Entity;

use App\Entity\Client;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Test Client
 * @package Tests
 */
class ClientTest extends TestCase
{
    /**
     * test de __construct
     * @throws \Exception
     */
    public function test__construct(): void
    {
        $dataClient = ['ref' => 'azeaze'];
        $client = new Client($dataClient);
        self::assertInstanceOf(Client::class, $client);
    }

    /**
     * test de __construct with bad data
     */
    public function test__constructWithBadData(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        $dataClient = [];
        new Client($dataClient);// @phpstan-ignore-line
    }

    /**
     * test de GetRef
     * @throws \Exception
     */
    public function testGetRef(): void
    {
        $dataClient = ['ref' => 'azeaze'];
        $client = new Client($dataClient);
        self::assertEquals($dataClient['ref'], $client->getRef());
    }

    /**
     * test de GetDescription
     * @throws \Exception
     */
    public function testGetDescription(): void
    {
        $dataClient = ['ref' => 'azeaze', 'description' => 'bar'];
        $client = new Client($dataClient);
        self::assertEquals($dataClient['description'], $client->getDescription());
    }

    /**
     * test de HasRessource
     * @throws \Exception
     */
    public function testHasRessource(): void
    {
        $dataClient = [
            'ref' => 'azeaze',
            'description' => 'bar',
            'ressources' => ['ressource1']
        ];
        $client = new Client($dataClient);
        self::assertTrue($client->hasRessource($dataClient['ressources'][0]));
    }

    /**
     * test de HasRessourceIsFail
     * @throws \Exception
     */
    public function testHasRessourceIsFail(): void
    {
        $dataClient = [
            'ref' => 'azeaze',
            'description' => 'bar',
            'ressources' => ['ressource1']
        ];
        $client = new Client($dataClient);
        self::assertFalse($client->hasRessource('wxcvbn'));
    }
}
