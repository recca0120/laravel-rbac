<?php

use Illuminate\Auth\Access\Gate as GateAccess;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Mockery as m;
use Recca0120\Rbac\Authenticate;
use Recca0120\Rbac\GateRegister;
use Recca0120\Rbac\Node;
use Recca0120\Rbac\Role;

class AuthTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $app = App::getInstance();

        $app->migrate('up');

        $this->setupUsers();
        $this->setupRoles();
        $this->setupNodes();
        $this->setupMembers();

        Role::first()
            ->nodes()
            ->sync(Node::all());

        $user = User::first()
            ->roles()
            ->sync([1]);

        Member::first()
            ->roles()
            ->sync([2]);

        $app[GateContract::class] = new GateAccess($app, function () use ($user) {
            return $user;
        });

        (new GateRegister($app[GateContract::class]))->sync();
    }

    public function tearDown()
    {
        // m::close();
        $app = App::getInstance();
        $app->migrate('down');
    }

    public function setupUsers()
    {
        User::truncate();
        User::create([
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
    }

    public function setupMembers()
    {
        Member::truncate();
        Member::create([
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
    }

    public function setupRoles()
    {
        Role::truncate();
        $roles = [
            [
                'name'        => '超級管理者',
                'slug'        => 'SuperAdmin',
                'description' => '超級管理者',
            ],
            [
                'name'        => '管理者',
                'slug'        => 'Adminstrator',
                'description' => '管理者',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }

    public function setupNodes()
    {
        Node::truncate();
        $perms = [
            [
                'name'  => '瀏覽',
                'slug'  => 'index',
                'level' => 3,
            ],
            [
                'name'  => '新增',
                'slug'  => 'create',
                'level' => 3,
            ],
            [
                'name'  => '編輯',
                'slug'  => 'edit',
                'level' => 3,
            ],
            [
                'name'  => '刪除',
                'slug'  => 'destroy',
                'level' => 3,
            ],
        ];
        Node::create([
            'name'  => 'ROOT',
            'level' => 1,
        ])->makeTree([
            [
                'name'     => '後台管理',
                'slug'     => 'backend',
                'level'    => 1,
                'children' => [
                    [
                        'name'     => 'Dashboard',
                        'slug'     => 'dashboard',
                        'icon'     => 'fa fa-fw fa-dashboard',
                        'action'   => '\Recca0120\Backend\Http\Controllers\DashboardController@index',
                        'level'    => 2,
                    ],
                    [
                        'name'     => '帳號管理',
                        'icon'     => 'fa fa-fw fa-user',
                        'level'    => 1,
                        'children' => [
                            [
                                'name'     => '使用者管理',
                                'slug'     => 'user',
                                'icon'     => 'fa fa-fw fa-user',
                                'action'   => '\Recca0120\Backend\Http\Controllers\UserController@index',
                                'level'    => 2,
                                'children' => $perms,
                            ],
                            [
                                'name'     => '角色管理',
                                'slug'     => 'role',
                                'level'    => 2,
                                'icon'     => 'fa fa-fw fa-user-secret',
                                'action'   => '\Recca0120\Backend\Http\Controllers\RoleController@index',
                                'children' => $perms,
                            ],
                            [
                                'name'     => '節點管理',
                                'slug'     => 'node',
                                'level'    => 2,
                                'icon'     => 'fa fa-fw fa-sitemap',
                                'action'   => '\Recca0120\Backend\Http\Controllers\NodeController@index',
                                'children' => $perms,
                            ],
                        ],
                    ],
                    [
                        'name'     => '系統設定',
                        'icon'     => 'fa fa-fw fa-cogs',
                        'level'    => 1,
                        'children' => [
                            [
                                'name'     => '網站相關',
                                'slug'     => 'config',
                                'icon'     => 'fa fa-fw fa-cog',
                                'action'   => '\Recca0120\Backend\Http\Controllers\ConfigController@index',
                                'level'    => 2,
                                'children' => $perms,
                            ],
                            [
                                'name'     => '資料庫備份',
                                'slug'     => 'db-backup',
                                'icon'     => 'fa fa-fw fa-database',
                                'action'   => '\Recca0120\Backend\Http\Controllers\DbBackupController@index',
                                'level'    => 2,
                                'children' => $perms,
                            ],
                            [
                                'name'     => 'Email樣板',
                                'slug'     => 'email-template',
                                'icon'     => 'fa fa-fw fa-envelope',
                                'action'   => '\Recca0120\Backend\Http\Controllers\EmailTemplateController@index',
                                'level'    => 2,
                                'children' => $perms,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_role_morphes()
    {
        $this->assertEquals([
            'users'   => User::class,
            'members' => Member::class,
        ], Role::getMorphes());
    }

    public function test_role_has_user()
    {
        $this->assertEquals(
            array_except(Role::first()->users()->first()->toArray(), 'pivot'),
            User::first()->toArray()
        );
    }

    public function test_user_is_superadmin()
    {
        $user = User::first();
        $this->assertTrue($user->isSuperAdmin());
    }

    public function test_user_is_administrator()
    {
        $user = User::first();
        $this->assertFalse($user->isAdminstrator());
    }

    public function test_member_is_superadmin()
    {
        $member = Member::first();
        $this->assertFalse($member->isSuperAdmin());
    }

    public function test_member_is_administrator()
    {
        $member = Member::first();
        $this->assertTrue($member->isAdminstrator());
    }

    public function test_member_has_others()
    {
        $member = Member::first();
        $this->assertFalse(Gate::forUser($member)->has('others'));
    }

    public function test_member_gate_has_user_index()
    {
        $member = Member::first();
        $this->assertFalse(Gate::forUser($member)->has('user-index'));
    }

    public function test_member_gate_has_user()
    {
        $member = Member::first();
        $this->assertFalse(Gate::forUser($member)->has('user'));
    }
}

class User extends Model
{
    use Authenticate;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'group_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}

class Member extends Model
{
    use Authenticate;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'group_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
