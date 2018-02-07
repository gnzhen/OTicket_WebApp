<?php

use Illuminate\Database\Seeder;
use App\Branch; 

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
        	'code' => 'B1',
        	'name' => 'Kepong',
            'desc' => '20, JLN Kepong 4/7, Kepong Ulu',
            'service_ids' => null,
            'counter_ids' => null,
        ]);

        Branch::create([
        	'code' => 'B2',
        	'name' => 'Ampang',
            'desc' => '20, JLN Ampang 4/7, Ampang',
            'service_ids' => null,
            'counter_ids' => null,
        ]);

        Branch::create([
        	'code' => 'B3',
        	'name' => 'Damansara',
            'desc' => '20, JLN Damansara 4/7, Damansara',
            'service_ids' => null,
            'counter_ids' => null,
        ]);

        Branch::create([
        	'code' => 'B4',
        	'name' => 'PJ',
            'desc' => '20, JLN PJ 4/7, PJ',
            'service_ids' => null,
            'counter_ids' => null,
        ]);
    }
}
