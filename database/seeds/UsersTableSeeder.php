<?php

use Illuminate\Database\Seeder;
use App\User; 

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	'username' => 'gnzhen',
        	'email' => 'mioyazhen@gmail.com',
            'password' => bcrypt('qweqwe'),
            'role_id' => 1,
            'branch_id' => 1,
        ]);

        User::create([
        	'username' => 'gnzhen1',
        	'email' => 'mioyazhen1@gmail.com',
            'password' => bcrypt('qweqwe'),
            'role_id' => 2,
            'branch_id' => null,
        ]);

        User::create([
        	'username' => 'gnzhen2',
        	'email' => 'mioyazhen2@gmail.com',
            'password' => bcrypt('qweqwe'),
            'role_id' => 3,
            'branch_id' => null,
        ]);
    }
}
