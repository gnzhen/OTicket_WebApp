<?php

use Illuminate\Database\Seeder;
use App\Queue;
use Carbon\Carbon;

class QueuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $table->bigIncrements('id')->unique();
        // $table->unsignedInteger('branch_service_id');
        // $table->integer('ticket_serving_now')->nullable();
        // $table->integer('wait_time');
        // $table->integer('total_ticket');
        // $table->datetime('start_time');
        // $table->datetime('end_time')->nullable();
        // $table->integer('active');
        // $table->text('ticket_ids')->nullable();

    	Queue::create([
        	'branch_service_id' => 1,
        	'ticket_serving_now' => 1,
            'wait_time' => 800,
            'total_ticket' => 4,
            'start_time' => Carbon::now(),
            'active' => 1,
        ]);

        Queue::create([
            'branch_service_id' => 2,
            'ticket_serving_now' => 5,
            'wait_time' => 800,
            'total_ticket' => 4,
            'start_time' => Carbon::now(),
            'active' => 1,
        ]);
        
    }
}
