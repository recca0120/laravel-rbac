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
            'username' => 'recca0120',
            'name'     => '蔡佳良',
            'email'    => 'recca0120@gmail.com',
            'password' => '$2y$10$oYNyx7Berl60uYTgxeWckeFYUWSQISLj4cMfpxVNQkUVPgm87t9sG',
        ]);

        User::create([
            'username' => 'admin',
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
    }
}
