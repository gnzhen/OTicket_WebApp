<?php

use Illuminate\Database\Seeder;
use App\Counter; 

class CountersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Counter::create(['code' => 'C1', 'name' => 'Counter 1']);
        Counter::create(['code' => 'C2', 'name' => 'Counter 2']);
        Counter::create(['code' => 'C3', 'name' => 'Counter 3']);
    }
}
