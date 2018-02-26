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

    public function serveTicket($ticket){
        $ticket->status = 'serving';
        $ticket->save();
    }

}