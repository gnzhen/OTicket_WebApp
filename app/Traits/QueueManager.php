<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Queue;
use Carbon\Carbon;

trait QueueManager {

    public function storeQueue($branchServiceId){
    	$queue = new Queue();

        $queue->branch_service_id = $branchServiceId;
        $queue->wait_time = 0;
        $queue->total_ticket = 1;
        $queue->start_time = Carbon::now();
        $queue->active = 1;

        $queue->save();

        return $queue;
    }

    public function updateQueueServingNow($queue, $ticket_id){
        
        $queue->ticket_serving_now = $ticket_id;

        $queue->save();

        return $queue;
    }

    public function refreshQueue($queue){
        //Calculate total ticket

        //Close queue if total ticket = 0;

        //Else Calculate wait time

        //Update affected ticket

    }

    public function closeQueue($queue){

        //set active = 0

        //set end_time

        //calculate avg_wait_time of queue

    }

}