<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 15:44
 */

namespace Tests;

use App\ApiSecurity;
use App\SecurityTool;
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
}
