<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 29/05/2020
 * Time: 13:04
 */

namespace Tests;

use App\Role;
use Exception;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{

    public function testGetName(): void
    {
        $role = new Role("test");
        $this->assertEquals("test", $role->getName());
    }

    public function test__construct(): void
    {
        $role = new Role("test");
        $this->assertInstanceOf(Role::class, $role);
    }

    public function test__construct_with_bad_init(): void
    {
        $this->expectException(Exception::class);
        $role = new Role('');
        $this->assertInstanceOf(Role::class, $role);
    }

    public function testIs_isTrue(): void
    {
        $role = new Role('test');
        $this->assertTrue($role->is('test'));
    }

    public function testIs_isFalse(): void
    {
        $role = new Role('test');
        $this->assertFalse($role->is('admin'));
    }
}
