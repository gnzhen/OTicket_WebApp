<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Queue;
use Carbon\Carbon;
use App\Traits\WaitTimeManager;

trait QueueManager {

    use WaitTimeManager;

    public function storeQueue($branchServiceId){
    	$queue = new Queue();

        $queue->branch_service_id = $branchServiceId;
        $queue->wait_time = 0;
        $queue->total_ticket = 1;
        $queue->waiting_ticket = 1;
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

    public function getTicketServingNow($queue){
        
        $ticket_serving_now = $queue->tickets->where('status','=','serving')->sortByDesc('id')->first();

        return $ticket_serving_now;
    }

    public function refreshQueue($queue){

        $waitingTicket = $queue->tickets->where('status','=','waiting')->count();

        //Update waiting ticket
        $queue->waiting_ticket = $waitingTicket;

        //Update queue wait time
        $waitTime = $this->calQueueWaitTime($queue);
        $queue->wait_time = $waitTime;

        //Update ticket serving now
        $queue->ticket_serving_now = $this->getTicketServingNow($queue);

        $queue->save();


        //Check whether to close 
        $servingTicket = $queue->tickets->where('status','serving')->count();

        if(($waitingTicket + $servingTicket) < 1){
            $this->closeQueue($queue);
        }

        //Update affected ticket
        //


    }

    public function closeQueue($queue){

        $queue->active = 0;
        $queue->end_time = Carbon::now();

        $queue->save();

    }

}