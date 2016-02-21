<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'username' => 'admin',
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
    }
}
