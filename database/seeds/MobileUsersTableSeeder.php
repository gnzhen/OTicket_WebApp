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

        MobileUser::create([
        	'name' => 'gnzhen',
        	'email' => 'mioyazhen@gmail.com',
            'phone' => '0175900301',
            'password' => bcrypt('qweqwe'),
        ]);
        
        MobileUser::create([
        	'name' => 'gnzhen1',
        	'email' => 'mioyazhen1@gmail.com',
            'phone' => '0175900301',
            'password' => bcrypt('qweqwe'),
        ]);

        MobileUser::create([
        	'name' => 'gnzhen2',
        	'email' => 'mioyazhen2@gmail.com',
            'phone' => '0175900301',
            'password' => bcrypt('qweqwe'),
        ]);

        MobileUser::create([
        	'name' => 'gnzhen3',
        	'email' => 'mioyazhen3@gmail.com',
            'phone' => '0175900301',
            'password' => bcrypt('qweqwe'),
        ]);

        MobileUser::create([
        	'name' => 'gnzhen4',
        	'email' => 'mioyazhen4@gmail.com',
            'phone' => '0175900301',
            'password' => bcrypt('qweqwe'),
        ]);
    }
}
