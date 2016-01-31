<?php

use App\User;
use Illuminate\Database\Seeder;
use Recca0120\Rbac\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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

        User::where('email', '=', 'recca0120@gmail.com')
            ->first()
            ->roles()
            ->sync([1, 2]);
    }
}
