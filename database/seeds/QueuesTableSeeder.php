<?php

use Illuminate\Database\Seeder;
use App\Queue;

class QueuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Queue::create([
        	'branch_service_id' => 1,
        	'ticket_ids' => null,
            'ticket_serving_now' => null,
            'wait_time' => 0,
        ]);
        
    }
}
