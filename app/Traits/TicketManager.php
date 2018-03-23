<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Ticket;
use App\Change;
use App\MobileUser;
use Carbon\Carbon;
use App\Traits\WaitTimeManager;

trait TicketManager {

    use WaitTimeManager;

    public function storeTicket(Request $request){
    	$ticket = new Ticket();

        $ticket->queue_id = $request->queue_id;
        $ticket->ticket_no = $request->ticket_no;
        $ticket->issue_time = $request->issue_time;
        $ticket->serve_time = $request->serve_time;
        $ticket->wait_time = $request->wait_time;
        $ticket->ppl_ahead = $request->ppl_ahead;
        $ticket->mobile_user_id = $request->mobile_user_id;
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
        $issueTime = Carbon::parse($ticket->issue_time, 'Asia/Kuala_Lumpur');
        $serveTime = Carbon::parse($ticket->serve_time, 'Asia/Kuala_Lumpur');
        $ticket->wait_time = $serveTime->diffInSeconds($issueTime);

        $ticket->save();

        return $ticket;
    }
    public function refreshTicket($queue, $ticket){

        $ticket->ppl_ahead = $this->getWaitingTicketInfrontNo($queue, $ticket);
        $ticket->wait_time = $this->calTicketWaitTime($queue, $ticket);  

        $ticket->save();  

        //check for serve_time change
        $ticket->serve_time = $this->calServeTime($ticket);
        $change = $this->checkChange($ticket);

        $ticket->save();

        if($change != null){
            //notify this ticket holder
        }

        return $ticket;
    }

    /* return other ticket of user that is serving */
    public function ticketUserServing($ticket){

        if($ticket->mobile_user_id != null){

            $mobileUser = MobileUser::findOrFail($ticket->mobile_user_id);

            $mobileTickets = $mobileUser->tickets;

            foreach($mobileTickets as $mobileTicket){
                if($mobileTicket->status == 'serving'){
                    return $mobileTicket;
                }
            }
        }

        return null;
    }

    /* return other tickets of user that is waiting */
    public function ticketUserWaiting($ticket) {

        if($ticket->mobile_user_id != null){

            $mobileUser = MobileUser::findOrFail($ticket->mobile_user_id);

            $mobileTickets = $mobileUser->tickets->where('status', 'waiting');

            return $mobileTickets;
        }

        return null;
    }

    public function ticketClash($servingTicket, $waitingTicket) {

        $servingDoneTime = $this->getEstimatedDoneTime($servingTicket);
        $waitingIssueTime = $waitingTicket->issue_time;

        return $servingDoneTime > $waitingIssueTime;
    }

    public function getEstimatedDoneTime($ticket){

        $timeNeeded = $this->getCurrentAvgWaitTimeTicket($ticket->queue);
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $estimatedDoneTime = $now->addSeconds($timeNeeded);

        return $estimatedDoneTime;
    }

    public function postponeOtherTicket($ticket) {
        echo "serving ticket id: ".$ticket->id;

        $postponedTickets = [];

        $waitingTickets = $this->ticketUserWaiting($ticket);

        if($waitingTickets){
            foreach($waitingTickets as $waitingTicket) {

                echo ", waiting ticketid: ".$waitingTicket->id;

                //check if clash with serving ticket
                if($this->ticketClash($ticket, $waitingTicket)){

                    echo ", clash";

                    //postpone only if got ppl behind
                    $ticketsBehind = $this->getTicketBehind($waitingTicket);
                    $ticketBehindNo = $ticketsBehind->count();

                    echo ", ticketBehindNo: ".$ticketBehindNo;

                    if($ticketBehindNo > 0) {

                        echo ", ticketsBehind: ".$ticketsBehind;

                        //calculate issue time
                        $servingDoneTime = $this->getEstimatedDoneTime($ticket);
                        $thatPerson = $ticketsBehind->where('serve_time', '>', $servingDoneTime)->first();

                        //skip to the last if not enought ppl behind
                        if($thatPerson == null){

                            $thatPerson = $ticketsBehind->values()[$ticketBehindNo - 1];

                        }

                        $thatPersonIssueTime = $thatPerson->issue_time;
                        $newIssueTime = Carbon::parse($thatPersonIssueTime, 'Asia/Kuala_Lumpur')->addSeconds(1);

                        echo ", servingDoneTime: ".$servingDoneTime.", thatPerson: ".$thatPerson.", newIssueTime: ".$newIssueTime.", postponedTicketid: ".$ticket->id.", originalIssueTime: ".$ticket->issue_time;

                        $postponedTicket = $this->postponeTicketTo($ticket, $newIssueTime);

                        echo ", postponedTicketid: ".$postponedTicket->id.", newIssueTime: ".$postponedTicket->issue_time;

                        if($postponedTicket != null) {
                            array_push($postponedTickets, $postponedTicket);
                        }
                    }
                }
            }
        }

        return $postponedTickets;
    }

    public function postponeTicketAuto($ticket){

        $ticketsBehind = $this->getTicketBehind($ticket);
        $ticketBehindNo = $ticketsBehind->count();
        $ticketServing = $this->ticketUserServing($ticket);

        //cancel postpone if no one behind 
        if($ticketBehindNo < 1 || $ticketServing == null) {

            return null;
        }
        else {

            //calculate how many person to skip 
            $avgWaitTimeServing = $this->getCurrentAvgWaitTimeTicket($ticketServing->queue);
            $avgWaitTimeThis = $this->getCurrentAvgWaitTimeTicket($ticket->queue);
            $toSkip = ceil($avgWaitTimeServing / $avgWaitTimeThis);

            //skip to the last if not enough ppl behind
            if($ticketBehindNo < $toSkip) {
                $toSkip = $ticketBehindNo; 
            }

            /* Calculate issueTime */
            //find the issue time of toSkip-th person behind him
            $thatPerson = $ticketsBehind->values()[$toSkip - 1];
            $thatPersonIssueTime = $thatPerson->issue_time;

            //new issue time = that person issue time + 1 sec;
            $newIssueTime = Carbon::parse($thatPersonIssueTime, 'Asia/Kuala_Lumpur')->addSeconds(1);

            return $this->postponeTicketTo($ticket, $newIssueTime);
        }
    }

    public function postponeTicketByUser($ticket, $postponeTime){

        //find number toSkip
            //toskip = ceil(postponeTime / avgwaittime)

        //check number of ticket behind < toSkip
        //if false, return null
        //if true, renew the issue time
            //calculate issue time (toSkip * awt)
            //update ticket issue time & postpone
    }

    public function postponeTicketTo($ticket, $issueTime) {

        $ticket->issue_time = $issueTime;
        $ticket->postponed = $ticket->postponed + 1;

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

    public function getWaitingTicketInfrontNo($queue, $ticket){

        return $queue->tickets->where('status','=','waiting')->where('issue_time', '<', $ticket->issue_time)->count();
    }

    public function getServingTicketInfrontNo($queue, $ticket){
        
        return $queue->tickets->where('status','=','serving')->where('issue_time', '<', $ticket->issue_time)->count();
    }

    public function getTicketBehind($ticket) {

        return $ticket->queue->tickets->where('issue_time', '>', $ticket->issue_time);
    }

    public function calTicketWaitTime($queue, $ticket){

        $avgWaitTime = $this->getCurrentAvgWaitTimeTicket($queue);

        $waitingInfrontNo = $this->getWaitingTicketInfrontNo($queue, $ticket);
        $servingInfrontNo = $this->getServingTicketInfrontNo($queue, $ticket);

        $totalWaitTime = $this->calCurrentTotalWaitTimeTicket($avgWaitTime, $waitingInfrontNo);

        $servingWaitTime = 0;

        if($servingInfrontNo > 0){

            $servingWaitTime = $this->calServingTicketWaitTime($queue, $avgWaitTime);

            $totalWaitTime = $totalWaitTime + $servingWaitTime;
        }

        return $totalWaitTime;
    }


    public function calServingTicketWaitTime($queue, $avgWaitTime){

        //get the earliest serving person in queue
        $ticket = $queue->tickets->where('status', 'serving')->sortBy('id')->first();
        //get last call of that person
        $calling = $ticket->calling->sortByDesc('id')->first();

        //calculate how much time left to serve that person
        $callTime = Carbon::parse($calling->call_time, 'Asia/Kuala_Lumpur');
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $servedTime = $now->diffInSeconds($callTime);
        $waitTime = $avgWaitTime - $servedTime;

        if($waitTime < 0){
            return 0;
        }

        return $waitTime;
    }

    public function calServeTime($ticket){

        return Carbon::now('Asia/Kuala_Lumpur')->addSeconds($ticket->wait_time);
    }

    public function checkChange($ticket){
        $newServeTime = Carbon::now('Asia/Kuala_Lumpur')->addSeconds($ticket->wait_time);
        $oldServeTime = Carbon::parse($ticket->serve_time, 'Asia/Kuala_Lumpur');
        
        if($newServeTime->gte($oldServeTime)){
            $condition = "delay";
            $serveTimeDiff = $newServeTime->diffInSeconds($oldServeTime);
        }
        else{
            $condition = "earlier";
            $serveTimeDiff = $oldServeTime->diffInSeconds($newServeTime);
        }

        // Create change if serve time change is more than 5 minutes
        if($serveTimeDiff > 300){
            $change = new Change();
            $change->change = $condition;
            $change->time = $serveTimeDiff;
            $change->save();

            return $change;
        }

        return null;
    }

}
