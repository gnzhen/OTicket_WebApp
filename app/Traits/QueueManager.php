<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Session;
use App\Queue;
use Carbon\Carbon;
use App\Http\Controllers\AppController;
use App\Traits\WaitTimeManager;
use App\Events\CancelTicketEvent;
use App\Events\NewQueueEvent;

trait QueueManager {

    use WaitTimeManager;

    public function storeQueue($branchServiceId){
    	$queue = new Queue();

        $queue->branch_service_id = $branchServiceId;
        $queue->wait_time = 0;
        $queue->total_ticket = 0;
        $queue->pending_ticket = 0;
        $queue->start_time = Carbon::now('Asia/Kuala_Lumpur');
        $queue->active = 1;

        $queue->save();

        return $queue;
    }

    public function refreshQueue($queue){

        //Update total ticket
        $queue->total_ticket = $queue->tickets->count();

        //Update pending ticket
        $queue->pending_ticket = $this->calPendingTicketNo($queue);;

        //Update queue wait time
        $queue->wait_time = $this->calQueueWaitTime($queue);

        //Update ticket serving now
        $queue->ticket_serving_now = $this->getTicketServingNow($queue);

        $queue->save();


        //Check whether to close 

        if($this->calPendingTicketNo($queue) < 1){
            $this->closeQueue($queue);

            Session::forget('tab');
        }

        //Update ticket in queue
        foreach($queue->tickets as $ticket){

            if($ticket->status == "serving" || $ticket->status == "waiting"){

                $ticket = $this->refreshTicket($queue, $ticket);
            }
        }
        
        return $queue;
    }

    public function closeQueue($queue){

        $queue->active = 0;
        $queue->end_time = Carbon::now('Asia/Kuala_Lumpur');

        $queue->save();

        $queue->avg_wait_time = $this->calQueueAvgWaitTime($queue);

        $queue->save();
        
        return $queue;
    }

    public function getTicketServingNow($queue){
        
        $ticketServingNow = $queue->tickets
                            ->where('status', 'serving')
                            ->sortByDesc('id')
                            ->first();

        return $ticketServingNow != null ? $ticketServingNow->id : null;
    }

    public function getWaitingTicket($queue){

        return $queue->tickets->where('status','=','waiting');
    }

    public function getServingTicket($queue){

        return $queue->tickets->where('status','serving');
    }

    public function calPendingTicketNo($queue){

        $waitingTicketNo = $this->getWaitingTicket($queue)->count();
        $servingTicketNo = $this->getServingTicket($queue)->count();

        return $waitingTicketNo + $servingTicketNo;
    }

    public function calQueueWaitTime($queue){

        $avgWaitTime = $this->getCurrentAvgWaitTimeQueue($queue);
        $waitingTicketNo = $this->getWaitingTicket($queue)->count();
        $servingTicketNo = $this->getServingTicket($queue)->count();
        $totalWaitTime = $this->calCurrentTotalWaitTimeQueue($avgWaitTime, $waitingTicketNo);

        if($servingTicketNo > 0){

            $totalWaitTime = $totalWaitTime + $this->calServingTicketWaitTime($queue, $avgWaitTime);
        }

        return $totalWaitTime;
    }

    public function calQueueAvgWaitTime($queue){

        $start = Carbon::parse($queue->start_time, 'Asia/Kuala_Lumpur');
        $end = Carbon::parse($queue->end_time, 'Asia/Kuala_Lumpur');
        $totalTime = $end->diffInSeconds($start);

        $totalTicket = $queue->tickets->count();

        $avgWaitTime = $this->calAvgWaitTimeQueue($totalTime, $totalTicket);

        return $avgWaitTime;
    }

    public function calQueuesAvgWaitTime($queues){
        
        $totalAvgWaitTimeOfQueue = 0;
        $numberOfQueue = 0;

        foreach($queues as $queue){

            $avgWaitTimeOfQueue = $queue->avg_wait_time;

            if($avgWaitTimeOfQueue > 0){

                $totalAvgWaitTimeOfQueue += $avgWaitTimeOfQueue;
                $numberOfQueue++;

            }
        }

        return $this->calAvgWaitTimeQueue($totalAvgWaitTimeOfQueue, $numberOfQueue);
    }
}