<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Session;
use App\Queue;
use Carbon\Carbon;
use App\Traits\WaitTimeManager;

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

    public function updateQueueServingNow($queue, $ticket_id){
        
        $queue->ticket_serving_now = $ticket_id;

        $queue->save();

        return $queue;
    }

    public function refreshQueue($queue){

        //Update waiting ticket
        // $waitingTicketNo = $this->getWaitingTicket($queue)->count();
        // $queue->pending_ticket = $waitingTicketNo;

        //Update total ticket
        $totalTicket = $queue->tickets->count();
        $queue->total_ticket = $totalTicket;

        //Update pending ticket
        $pendingTicket = $this->calPendingTicketNo($queue);
        $queue->pending_ticket = $pendingTicket;

        //Update queue wait time
        $queueWaitTime = $this->calQueueWaitTime($queue);
        $queue->wait_time = $queueWaitTime;

        //Update ticket serving now
        $queue->ticket_serving_now = $this->getTicketServingNow($queue);

        $queue->save();


        //Check whether to close 

        if($this->calPendingTicketNo($queue) < 1){
            $this->closeQueue($queue);

            Session::forget('tab');
        }

        //Update affected ticket
        $tickets = $queue->tickets;

        foreach($tickets as $ticket){
            $ticket = $this->refreshTicket($queue, $ticket);
        }

        return $queue;
    }

    public function closeQueue($queue){

        $queue->active = 0;
        $queue->end_time = Carbon::now('Asia/Kuala_Lumpur');

        $queue->save();
        
        return $queue;
    }

    public function getTicketServingNow($queue){
        
        $ticketServingNow = $queue->tickets->where('status','=','serving')->sortByDesc('id')->first();

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

        $avgWaitTime = $this->getAvgWaitTimeQueue($queue);

        $waitingTicketNo = $this->getWaitingTicket($queue)->count();
        $servingTicketNo = $this->getServingTicket($queue)->count();

        $totalTicket = $servingTicketNo < 1 ? $waitingTicketNo : ($waitingTicketNo + 1);

        return $this->calTotalWaitTimeQueue($avgWaitTime, $totalTicket);
    }


    public function calQueueAvgWaitTime($queue){

        if($queue->end_time == null) 
            return 0;

        $start = Carbon::parse($queue->start_time, 'Asia/Kuala_Lumpur');
        $end = Carbon::parse($queue->end_time, 'Asia/Kuala_Lumpur');
        $totalTime = $end->diffInSeconds($start);

        $totalTicket = $queue->tickets->count();

        return $this->calAvgWaitTime($totalTime, $totalTicket);
    }
}