<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Ticket;
use Carbon\Carbon;

trait TicketManager {

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
    }

    public function serveTicket($ticket){
        $ticket->status = 'serving';
        $ticket->save();
    }

    public function skipTicket($ticket){
        $ticket->status = 'skipped';
        $ticket->disposed_time = Carbon::now();
        $ticket->save();
    }

    public function doneTicket($ticket){
        $ticket->status = 'done';
        $ticket->disposed_time = Carbon::now();
        $ticket->save();
    }


    public function ticketNoGenerator($serviceId, $totalTicket){

        if($serviceId < 10)
            $serviceId = 0 . $serviceId;
        if($totalTicket < 10)
            $totalTicket = 0 . $totalTicket;

        return $serviceId . $totalTicket;
    }

}