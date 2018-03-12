<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Ticket;
use Carbon\Carbon;
use App\Traits\WaitTimeManager;

trait TicketManager {

    use WaitTimeManager;

    public function storeTicket(Request $request){
    	$ticket = new Ticket();

        $ticket->queue_id = $request->queue_id;
        $ticket->ticket_no = $request->ticket_no;
        $ticket->issue_time = $request->issue_time;
        $ticket->wait_time = $request->wait_time;
        $ticket->ppl_ahead = $request->ppl_ahead;
        $ticket->postponed = $request->postponed;
        $ticket->status = $request->status;

        $ticket->save();

        return $ticket;
    }

    public function waitTicket($ticket){
        $ticket->status = 'waiting';
        $ticket->save();

        return $ticket;
    }

    public function serveTicket($ticket){
        $ticket->status = 'serving';
        $ticket->ppl_ahead = 0;
        $ticket->save();

        return $ticket;
    }

    public function skipTicket($ticket){
        $ticket->status = 'skipped';
        $ticket->ppl_ahead = 0;
        $ticket->disposed_time = Carbon::now('Asia/Kuala_Lumpur');
        $ticket->save();

        return $ticket;
    }

    public function doneTicket($ticket){
        $ticket->status = 'done';
        $ticket->disposed_time = Carbon::now('Asia/Kuala_Lumpur');
        $ticket->save();

        return $ticket;
    }

    public function refreshTicket($queue, $ticket){

        $ticket->ppl_ahead = $this->calPplAhead($queue, $ticket);
        $ticket->wait_time = $this->calTicketWaitTime($queue, $ticket);  

        $ticket->save();  

        return $ticket;
    }

    public function ticketNoGenerator($queue){

        $serviceId = $queue->branchService->service_id;
        $totalTicket = ($queue->total_ticket) + 1;

        if($serviceId < 10)
            $serviceId = 0 . $serviceId;
        if($totalTicket < 10)
            $totalTicket = 0 . $totalTicket;

        return $serviceId . $totalTicket;
    }

    public function calPplAhead($queue, $ticket){

        //there are no pplAhead if the ticket is serving
        if($ticket->status == 'serving'){
            return 0;
        }

        $waitingTicketInfrontNo = $queue->tickets->where('status','=','waiting')->where('wait_time', '<', $ticket->wait_time)->count();

        $servingTicketInfrontNo = $queue->tickets->where('status','=','serving')->where('wait_time', '<', $ticket->wait_time)->count();

        //ppl ahead including all waiting ticket + 1 serving ticket infront the ticket
        $pplAhead = $servingTicketInfrontNo < 1 ? $waitingTicketInfrontNo : ($waitingTicketInfrontNo + 1);


        return $pplAhead;
    }

    public function calTicketWaitTime($queue, $ticket){

        $avgWaitTime = $this->getCurrentAvgWaitTimeTicket($queue);
        
        $pplAhead = $this->calPplAhead($queue, $ticket);

        return $this->calCurrentTotalWaitTimeTicket($avgWaitTime, $pplAhead);
    }

}