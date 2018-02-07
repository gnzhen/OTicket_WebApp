<?php

use Illuminate\Database\Seeder;
use App\BranchCounter; 

class BranchCountersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BranchCounter::create([
        	'branch_id' => 1,
            'counter_id' => 1,
            'staff_username' => null,
        ]);

        BranchCounter::create([
        	'branch_id' => 1,
            'counter_id' => 2,
            'staff_username' => null,
        ]);

        BranchCounter::create([
        	'branch_id' => 2,
            'counter_id' => 1,
            'staff_username' => null,
        ]);

        BranchCounter::create([
        	'branch_id' => 2,
            'counter_id' => 2,
            'staff_username' => null,
        ]);
    }
}
