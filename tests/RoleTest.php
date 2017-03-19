<?php

namespace Recca0120\Rbac\Tests;

use Mockery as m;
use Recca0120\Rbac\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testUsers()
    {
        $user = new Role();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->users());
    }

    public function testPermissions()
    {
        $user = new Role();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->permissions());
    }
}
