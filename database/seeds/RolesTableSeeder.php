<?php

use Illuminate\Database\Seeder;
use App\Role; 

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['id' => 0, 'name' => 'super admin']);
        Role::create(['id' => 1, 'name' => 'admin']);
        Role::create(['id' => 2, 'name' => 'counter staff']);
    }
}
