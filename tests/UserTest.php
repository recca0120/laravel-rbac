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
    protected function setUp()
    {
        // (new CreateRolesTable())->up();
        // (new CreateRoleUserTable())->up();
        // (new CreatePermissionsTable())->up();
        // (new CreatePermissionRoleTable())->up();
    }

    protected function tearDown()
    {
        // (new CreateRolesTable())->down();
        // (new CreateRoleUserTable())->down();
        // (new CreatePermissionsTable())->down();
        // (new CreatePermissionRoleTable())->down();
        m::close();
    }

    public function testRoles()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->roles());
    }

    public function testBootUserTrait()
    {
        User::setEventDispatcher(
            $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher')
        );

        $event = 'saved';
        $name = User::class;
        $dispatcher->shouldReceive('listen')->once()->with("eloquent.{$event}: {$name}", m::type('Closure'));

        User::bootUserTrait();
    }
}
