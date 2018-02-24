<?php

use Illuminate\Database\Seeder;
use App\MobileUser;

class MobileUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$table->string('username')->unique();
        // $table->string('email')->unique();
        // $table->string('password');

        MobileUser::create([
        	'username' => 'gnzhen',
        	'email' => 'mioyazhen@gmail.com',
            'password' => bcrypt('qweqwe'),
        ]);
        
        MobileUser::create([
        	'username' => 'gnzhen1',
        	'email' => 'mioyazhen1@gmail.com',
            'password' => bcrypt('qweqwe'),
        ]);

        MobileUser::create([
        	'username' => 'gnzhen2',
        	'email' => 'mioyazhen2@gmail.com',
            'password' => bcrypt('qweqwe'),
        ]);

        MobileUser::create([
        	'username' => 'gnzhen3',
        	'email' => 'mioyazhen3@gmail.com',
            'password' => bcrypt('qweqwe'),
        ]);

        MobileUser::create([
        	'username' => 'gnzhen4',
        	'email' => 'mioyazhen4@gmail.com',
            'password' => bcrypt('qweqwe'),
        ]);
    }
}
