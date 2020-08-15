<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 15:44
 */

namespace Tests;

use App\ApiSecurity;
use PHPUnit\Framework\TestCase;

class ApiSecurityTest extends TestCase
{

    /**
     * @var \App\SecurityTool
     */
    protected $security;

    public function setUp(): void
    {
        parent::setUp();
        $this->security = new ApiSecurity();
    }

    public function testGetUid()
    {
        $this->assertIsString($this->security->getUid());
        $Uid1 = $this->security->getUid();
        $Uid2 = $this->security->getUid();
        $this->assertNotEquals($Uid1, $Uid2);
    }

    public function testHashPassWord()
    {
        $mdp = "qsdfghjklm";
        $this->assertNotEquals($mdp, $this->security->hashPassWord($mdp));
    }

    public function testTestPassWord()
    {
        $mdp = "az65qsXCRF7GBVYu754jolp";
        $hash = $this->security->hashPassWord($mdp);
        $this->assertTrue($this->security->testPassWord($mdp, $hash));
    }

    public function testSha256(): void
    {
        $x = hash('sha256', $this->security->getUid());
        self::assertEquals(64, strlen($x));
    }

    public function testGetIdClient(): void
    {
        $_SERVER['REMOTE_ADDR'] = '92.168.1.56';
        $_SERVER['HTTP_USER_AGENT'] = 'user agent';
        $preId = $this->security->getIdClient();
        self::assertEquals('f241ceed482753e2f422a5c79bb9fdf0c69864fdfc8c9a3427f838c2bd475ca1', $preId);
        $_SERVER['REMOTE_ADDR'] = '92.168.1.57';
        $_SERVER['HTTP_USER_AGENT'] = 'user agent';
        $secId = $this->security->getIdClient();
        $_SERVER['REMOTE_ADDR'] = '92.168.1.56';
        $_SERVER['HTTP_USER_AGENT'] = 'user agent X';
        $triId = $this->security->getIdClient();
        self::assertNotEquals($secId, $preId);
        self::assertNotEquals($secId, $triId);
        self::assertNotEquals($preId, $triId);
    }

    public function test_getPublicToken(): void
    {
        $public = $this->security->getPublicToken('aze', 'aze');
        self::assertEquals('d1ed2c2b5e9e9cc059be55b29420c4c5e6855a76f3225ff97edcbf4b210ff18b', $public);
    }

    public function test_testPublicToken(): void
    {
        $public = $this->security->getPublicToken('aze', 'aze');
        self::assertTrue($this->security->testPublicToken($public, 'aze', 'aze'));
        self::assertFalse($this->security->testPublicToken($public, 'azex', 'aze'));
        self::assertFalse($this->security->testPublicToken($public, 'aze', 'azex'));
    }
}
