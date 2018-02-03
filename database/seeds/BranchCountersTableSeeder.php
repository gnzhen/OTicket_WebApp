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
        	'id' => 'B1_C1',
        	'branch_id' => 'B1',
            'counter_id' => 'C1',
            'staff_username' => null,
        ]);

        BranchCounter::create([
        	'id' => 'B1_C2',
        	'branch_id' => 'B1',
            'counter_id' => 'C2',
            'staff_username' => null,
        ]);

        BranchCounter::create([
        	'id' => 'B2_C1',
        	'branch_id' => 'B2',
            'counter_id' => 'C1',
            'staff_username' => null,
        ]);

        BranchCounter::create([
        	'id' => 'B2_C2',
        	'branch_id' => 'B2',
            'counter_id' => 'C2',
            'staff_username' => null,
        ]);
    }
}
