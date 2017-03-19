<?php

namespace Recca0120\Rbac\Tests;

use Mockery as m;
use CreateRolesTable;
use CreateRoleUserTable;
use Recca0120\Rbac\User;
use CreatePermissionsTable;
use CreatePermissionRoleTable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function tearDown()
    {
        // (new CreateRolesTable())->down();
        // (new CreateRoleUserTable())->down();
        // (new CreatePermissionsTable())->down();
        // (new CreatePermissionRoleTable())->down();
        m::close();
    }

    protected function setUp()
    {
        // (new CreateRolesTable())->up();
        // (new CreateRoleUserTable())->up();
        // (new CreatePermissionsTable())->up();
        // (new CreatePermissionRoleTable())->up();
    }

    public function testRoles()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->roles());
    }
}
