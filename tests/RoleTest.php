<?php

use Mockery as m;
use Recca0120\RBAC\Node;
use Recca0120\RBAC\Role;
use Recca0120\RBAC\User;

class RoleTest extends PHPUnit_Framework_TestCase
{
    use Laravel;

    public function setUp()
    {
        $app = $this->createApplication();
        $this->migrate('up');
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function tearDown()
    {
        m::close();
        $this->migrate('down');
        Schema::drop('users');
    }

    public function test_node_permission()
    {
        $parentNode = $this->mockNode('parent', 2);
        $childNode = $this->mockNode('child', 3);
        $childNode->makeChildOf($parentNode);
        $childNode->fresh();
        $this->assertEquals($childNode->permission, 'parent-child');
        $node = $this->mockNode('c-a', 3);
        $this->assertEquals($node->permission, 'c-a');
    }

    public function test_role_permissions()
    {
        $parentNode = $this->mockNode('parent', 2);
        $childNode = $this->mockNode('child', 3);
        $childNode->makeChildOf($parentNode);
        $childNode->fresh();
        $this->assertEquals($childNode->permission, 'parent-child');
        $node = $this->mockNode('c-a', 3);
        $this->assertEquals($node->permission, 'c-a');

        $role = $this->mockRole(__FUNCTION__);
        $role->attachNode($parentNode);
        $role->attachNode($childNode);
        $role->attachNode($node);
        $this->assertEquals($role->nodes->count(), 3);
        $this->assertTrue($role->permissions->contains('permission', 'parent-child'));
        $this->assertTrue($role->permissions->contains('permission', 'c-a'));
    }

    public function test_user_permissions()
    {
        $user = $this->mockUser('recca0120@gmail.com');
        $role = $this->mockRole(__FUNCTION__);
        $role2 = $this->mockRole(__FUNCTION__.'2');
        $parentNode = $this->mockNode('parent', 2);
        $childNode = $this->mockNode('child', 3);
        $childNode->makeChildOf($parentNode);
        $childNode->fresh();
        $this->assertEquals($childNode->permission, 'parent-child');
        $node = $this->mockNode('c-a', 3);
        $this->assertEquals($node->permission, 'c-a');

        $role->attachNode($parentNode);
        $role->attachNode($childNode);

        $user->attachRole($role);
        $this->assertTrue($user->permissions->contains('permission', 'parent-child'));
        $this->assertFalse($user->permissions->contains('permission', 'c-a'));

        $role2->attachNode($node);
        $user->attachRole($role2);
        $this->assertTrue($user->permissions->contains('permission', 'parent-child'));
        $this->assertTrue($user->permissions->contains('permission', 'c-a'));

        $role->detachNode($childNode);
        $this->assertFalse($user->permissions->contains('permission', 'parent-child'));
        $this->assertTrue($user->permissions->contains('permission', 'c-a'));
    }

    public function test_morphed_users()
    {
        $user = $this->mockUser('recca0120@gmail.com');
        $member = $this->mockUser('test@test.com', Member::class);
        $role = $this->mockRole(__FUNCTION__);
        $user->attachRole($role);
        $member->attachRole($role);
        $this->assertTrue($role->users->contains('email', $user->email));
        $this->assertFalse($role->users->contains('email', $member->email));

        $this->assertTrue($role->members->contains('email', $member->email));
        $this->assertFalse($role->members->contains('email', $user->email));
    }

    public function test_morphed_users_permissions()
    {
        $user = $this->mockUser('recca0120@gmail.com');
        $member = $this->mockUser('test@test.com', Member::class);

        $role = $this->mockRole(__FUNCTION__);
        $role2 = $this->mockRole(__FUNCTION__.'2');
        $parentNode = $this->mockNode('parent', 2);
        $childNode = $this->mockNode('child', 3);
        $childNode->makeChildOf($parentNode);
        $childNode->fresh();
        $this->assertEquals($childNode->permission, 'parent-child');
        $node = $this->mockNode('c-a', 3);
        $this->assertEquals($node->permission, 'c-a');

        $role->attachNode($parentNode);
        $role->attachNode($childNode);

        $user->attachRole($role);
        $this->assertTrue($user->permissions->contains('permission', 'parent-child'));
        $this->assertFalse($user->permissions->contains('permission', 'c-a'));

        $role2->attachNode($node);
        $user->attachRole($role2);
        $this->assertTrue($user->permissions->contains('permission', 'parent-child'));
        $this->assertTrue($user->permissions->contains('permission', 'c-a'));

        $member->attachRole($role);
        $this->assertTrue($member->permissions->contains('permission', 'parent-child'));
        $this->assertFalse($member->permissions->contains('permission', 'c-a'));

        $member->attachRole($role2);
        $this->assertTrue($member->permissions->contains('permission', 'parent-child'));
        $this->assertTrue($member->permissions->contains('permission', 'c-a'));

        $role->detachNode($childNode);
        $this->assertFalse($user->permissions->contains('permission', 'parent-child'));
        $this->assertTrue($user->permissions->contains('permission', 'c-a'));
        $this->assertFalse($member->permissions->contains('permission', 'parent-child'));
        $this->assertTrue($member->permissions->contains('permission', 'c-a'));

        $role->syncNodes([]);
        $role2->syncNodes([]);

        $this->assertFalse($user->permissions->contains('permission', 'parent-child'));
        $this->assertFalse($user->permissions->contains('permission', 'c-a'));
        $this->assertFalse($member->permissions->contains('permission', 'parent-child'));
        $this->assertFalse($member->permissions->contains('permission', 'c-a'));
    }

    public function mockUser($email, $className = User::class)
    {
        $user = (new $className())->firstOrCreate([
            'email'    => $email,
            'name'     => $email,
            'password' => $email,
        ]);

        return $user;
    }

    public function mockRole($name)
    {
        $role = Role::create([
            'name' => $name,
            'slug' => $name,
        ]);

        return $role;
    }

    public function mockNode($name, $level = null, $slug = null)
    {
        if (is_null($level)) {
            $level = 3;
        }

        if (is_null($slug)) {
            $slug = $name;
        }

        $root = Node::firstOrCreate([
            'name'   => 'Root',
            'slug'   => 'root',
            'icon'   => '',
            'action' => '',
            'level'  => '',
        ]);

        $node = Node::create([
            'name'   => $name,
            'slug'   => $slug,
            'icon'   => $slug,
            'action' => $slug,
            'level'  => $level,
        ]);

        return $node;
    }
}

class Member extends User
{
    protected $table = 'users';
}
