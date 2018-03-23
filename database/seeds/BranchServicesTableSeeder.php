<?php

use Illuminate\Database\Seeder;
use App\BranchService; 

class BranchServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BranchService::create([
        	'branch_id' => 1,
            'service_id' => 1,
            'system_wait_time' => null,
            'default_wait_time' => 600,
        ]);

        BranchService::create([
        	'branch_id' => 1,
            'service_id' => 2,
            'system_wait_time' => null,
            'default_wait_time' => 600,
        ]);

        BranchService::create([
        	'branch_id' => 2,
            'service_id' => 1,
            'system_wait_time' => null,
            'default_wait_time' => 50,
        ]);

        BranchService::create([
        	'branch_id' => 2,
            'service_id' => 2,
            'system_wait_time' => null,
            'default_wait_time' => 700,
        ]);
    }
}
