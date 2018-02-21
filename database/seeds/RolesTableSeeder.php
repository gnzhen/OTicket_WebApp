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
        Role::create(['code' => 'R1', 'name' => 'superAdmin']);
        Role::create(['code' => 'R2', 'name' => 'admin']);
        Role::create(['code' => 'R3', 'name' => 'counterStaff']);
    }
}
