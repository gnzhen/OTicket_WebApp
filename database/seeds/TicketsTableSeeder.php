<?php

use Illuminate\Database\Seeder;
use App\Ticket; 
use Carbon\Carbon;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // $table->bigIncrements('id')->unique();
         //    $table->string('ticket_no');
         //    $table->datetime('issue_time');
         //    $table->unsignedBigInteger('queue_id')->index();
         //    $table->integer('wait_time');
         //    $table->integer('ppl_ahead');
         //    $table->string('mobile_user_id');
         //    $table->integer('postponed');
         //    $table->string('status');

    	Ticket::create([
        	'ticket_no' => 'A001',
            'issue_time' => Carbon::now(),
            'queue_id' => 1,
            'wait_time' => 0,
            'ppl_ahead' => 0,
            'mobile_user_id' => 1,
            'postponed' => 0,
            'status' => 'serving',
        ]);

        Ticket::create([
        	'ticket_no' => 'A002',
            'issue_time' => Carbon::now()->addMinutes(2),
            'queue_id' => 1,
            'wait_time' => 200,
            'ppl_ahead' => 0,
            'mobile_user_id' => 2,
            'postponed' => 0,
            'status' => 'waiting',
        ]);

        Ticket::create([
        	'ticket_no' => 'A003',
            'issue_time' => Carbon::now()->addMinutes(4),
            'queue_id' => 1,
            'wait_time' => 400,
            'ppl_ahead' => 1,
            'mobile_user_id' => 3,
            'postponed' => 0,
            'status' => 'waiting',
        ]);

        Ticket::create([
        	'ticket_no' => 'A004',
            'issue_time' => Carbon::now()->addMinutes(6),
            'queue_id' => 1,
            'wait_time' => 600,
            'ppl_ahead' => 2,
            'mobile_user_id' => 4,
            'postponed' => 0,
            'status' => 'waiting',
        ]);

        Ticket::create([
        	'ticket_no' => '0001',
            'issue_time' => Carbon::now(),
            'queue_id' => 2,
            'wait_time' => 0,
            'ppl_ahead' => 0,
            'mobile_user_id' => 1,
            'postponed' => 0,
            'status' => 'serving',
        ]);

        Ticket::create([
        	'ticket_no' => '0002',
            'issue_time' => Carbon::now()->addMinutes(2),
            'queue_id' => 2,
            'wait_time' => 200,
            'ppl_ahead' => 0,
            'mobile_user_id' => 2,
            'postponed' => 0,
            'status' => 'waiting',
        ]);

        Ticket::create([
        	'ticket_no' => '0003',
            'issue_time' => Carbon::now()->addMinutes(4),
            'queue_id' => 2,
            'wait_time' => 400,
            'ppl_ahead' => 1,
            'mobile_user_id' => 3,
            'postponed' => 0,
            'status' => 'waiting',
        ]);

        Ticket::create([
        	'ticket_no' => '0004',
            'issue_time' => Carbon::now()->addMinutes(6),
            'queue_id' => 2,
            'wait_time' => 600,
            'ppl_ahead' => 2,
            'mobile_user_id' => 4,
            'postponed' => 0,
            'status' => 'waiting',
        ]);
    }
}
