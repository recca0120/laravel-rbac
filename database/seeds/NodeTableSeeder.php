<?php

use Illuminate\Database\Seeder;
use Recca0120\RBAC\Node;

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
                'level' => 'permission',
            ],
            [
                'name'  => '新增',
                'slug'  => 'store',
                'level' => 'permission',
            ],
            [
                'name'  => '編輯',
                'slug'  => 'update',
                'level' => 'permission',
            ],
            [
                'name'  => '刪除',
                'slug'  => 'destroy',
                'level' => 'permission',
            ],
        ];

        Node::create([
            'name'     => 'ROOT',
            'level'    => 'directory',
            'children' => [
                [
                    'name'     => '後台管理',
                    'slug'     => 'backend',
                    'level'    => 'directory',
                    'children' => [
                        [
                            'name'     => 'Dashboard',
                            'slug'     => 'dashboard',
                            'icon'     => 'fa fa-fw fa-dashboard',
                            'action'   => '\Recca0120\Backend\Http\Controllers\DashboardController@index',
                            'level'    => 'node',
                        ],
                        [
                            'name'     => '帳號管理',
                            'icon'     => 'fa fa-fw fa-group',
                            'level'    => 'directory',
                            'children' => [
                                [
                                    'name'     => '使用者管理',
                                    'slug'     => 'user',
                                    'icon'     => 'fa fa-fw fa-user',
                                    'action'   => '\Recca0120\Backend\Http\Controllers\UserController@index',
                                    'level'    => 'node',
                                    'children' => $perms,
                                ],
                                [
                                    'name'     => '角色管理',
                                    'slug'     => 'role',
                                    'level'    => 'node',
                                    'icon'     => 'fa fa-fw fa-group',
                                    'action'   => '\Recca0120\Backend\Http\Controllers\RoleController@index',
                                    'children' => $perms,
                                ],
                                [
                                    'name'     => '節點管理',
                                    'slug'     => 'node',
                                    'level'    => 'node',
                                    'icon'     => 'fa fa-fw fa-sitemap',
                                    'action'   => '\Recca0120\Backend\Http\Controllers\NodeController@index',
                                    'children' => $perms,
                                ],
                            ],
                        ],
                        [
                            'name'     => '系統設定',
                            'icon'     => 'fa fa-fw fa-cogs',
                            'level'    => 'directory',
                            'children' => [
                                [
                                    'name'     => '網站相關',
                                    'slug'     => 'config',
                                    'icon'     => 'fa fa-fw fa-cog',
                                    'action'   => '\Recca0120\Backend\Http\Controllers\ConfigController@index',
                                    'level'    => 'node',
                                    'children' => [
                                        [
                                            'name'  => '瀏覽',
                                            'slug'  => 'index',
                                            'level' => 'permission',
                                        ],
                                        [
                                            'name'  => '編輯',
                                            'slug'  => 'update',
                                            'level' => 'permission',
                                        ],
                                    ],
                                ],
                                [
                                    'name'     => '資料庫備份',
                                    'slug'     => 'db-backup',
                                    'icon'     => 'fa fa-fw fa-database',
                                    'action'   => '\Recca0120\Backend\Http\Controllers\DbBackupController@index',
                                    'level'    => 'node',
                                    'children' => $perms,
                                ],
                                [
                                    'name'     => 'Email樣板',
                                    'slug'     => 'email-template',
                                    'icon'     => 'fa fa-fw fa-envelope',
                                    'action'   => '\Recca0120\Backend\Http\Controllers\EmailTemplateController@index',
                                    'level'    => 'node',
                                    'children' => $perms,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
