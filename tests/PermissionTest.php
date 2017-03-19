<?php

namespace Recca0120\Rbac\Tests;

use Mockery as m;
use Recca0120\Rbac\Permission;
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testRoles()
    {
        $user = new Permission();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->roles());
    }
}
