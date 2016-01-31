<?php

use Illuminate\Database\Seeder;
use Recca0120\Rbac\Node;

class NodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
}
