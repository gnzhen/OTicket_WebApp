<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\MobileUser;
use App\Branch;
use App\Service;
use App\Queue;
use App\Ticket;
use App\Serving;
use App\Calling;
use App\BranchService;
use App\BranchCounter;
use App\FCMToken;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use App\Events\NewQueueEvent;
use App\Events\CancelTicketEvent;
use App\Traits\QueueManager;
use App\Traits\TicketManager;
use App\Traits\CallingManager;
use Carbon\Carbon;
use DB;
use Hash;
use PushNotification;

class MobileUserController extends Controller
{
	use QueueManager { 
        calAvgWaitTime as protected calAvgWaitTimeQueue; 
        calCurrentTotalWaitTime as protected calCurrentTotalWaitTimeQueue;
        getCurrentAvgWaitTime as protected getCurrentAvgWaitTimeQueue;
    } 
    use TicketManager { 
        calAvgWaitTime as protected calAvgWaitTimeTicket; 
        calCurrentTotalWaitTime as protected calCurrentTotalWaitTimeTicket;
        getCurrentAvgWaitTime as protected getCurrentAvgWaitTimeTicket;
    }

    use CallingManager;

	private $client;

    public function __construct(){

		$this->client = Client::find(1);
	}

	public function test(Request $request){

		return response()->json(['test' => 'test']);
	}

    public function saveToken(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'mobileUserId' => 'required|integer',
            'token' => 'required',
        ]);
        
        if ($validator->fails()) {

            return response()->json($validator->messages());
        }
        
        
        DB::beginTransaction();

        try {

            $FCMToken = FCMToken::where('user_id', $request->mobileUserId)->first();

            if($FCMToken == null){
                
                $FCMToken = new FCMToken;
            }

            $FCMToken->user_id = $request->mobileUserId;
            $FCMToken->token = $request->token;
            $FCMToken->save();

            DB::commit();

            return response()->json(['success' => $FCMToken]);

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
            
            return response()->json(["fail" => "Opps! We've some trouble!"]);
        }
    }

    public function register(Request $request) {

    	$validator = Validator::make($request->all(), [
            'name' => 'required',
    		'email' => 'required|email|unique:mobile_users',
    		'phone' => 'required|regex:/[0-9]{9,12}/',
    		'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } else {
        	$mobileUser = new MobileUser;

	        $mobileUser->name = $request->name;
	        $mobileUser->email = $request->email;
	        $mobileUser->phone = $request->phone;
	        $mobileUser->password = Hash::make($request->password);

        	$mobileUser->save();

    		return response()->json(["success" => "Register Success!", "user" => $mobileUser]);
        }
    }

    public function login(Request $request) {

    	$validator = Validator::make($request->all(), [
    		'email' => 'required|email',
    		'password' => 'required'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } 
        else {
        	$mobileUser = MobileUser::where('email', $request->email)->first();
        	
        	if(!$mobileUser)
        		return response()->json(["fail" => "Email not registered"]);

    		if(!Hash::check($request->password, $mobileUser->password)){
			    
				return response()->json(["fail" => "Wrong password"]);
			}


			return response()->json(["success" => "Login Success!", "user" => $mobileUser]);
        }
    }

    public function issueTicket(Request $request){

        $validator = Validator::make($request->all(), [
            'branchServiceId' => 'required|integer',
            'mobileUserId' => 'required|integer'
        ]);
        
        if ($validator->fails()) {

            return response()->json($validator->messages());
        }
        
    	
    	DB::beginTransaction();

        try {

	        /* Check multi ticket */
	    	$branchService = BranchService::find($request->branchServiceId);
	    	$mobileUser = MobileUser::find($request->mobileUserId);

            if(!$branchService || !$mobileUser){
                return response()->json(["fail" => "Opps! We've some trouble!"]);
            }

	    	$currentTickets = Ticket::where('mobile_user_id', $request->mobileUserId)->whereIn('status', ["waiting", "serving"])->get();

	    	if($currentTickets != null){
	    		// Check max ticket
	    		if($currentTickets->count() > 2){
	    			return response()->json(["fail" => "You only can have maximum 3 tickets."]);
	    		}

	    		// Check Ticket from other branch 
	    		foreach($currentTickets as $currentTicket){
	    			if($currentTicket->queue->branchService->branch_id != $branchService->branch_id){
	    				return response()->json(["fail" => "You cannot have tickets from different branches."]);
	    			}
	    			if($currentTicket->queue->branchService->id == $branchService->id){
	    				return response()->json(["fail" => "You already have this ticket."]);
	    			}
	    		}
	    	}

            $branchService = BranchService::findOrFail($request->branchServiceId);

            $queue = Queue::where('branch_service_id', $branchService->id)->where('active','=', 1)->lockForUpdate()->first();

            if($queue == null){
                
                //Create Queue
                $queue = $this->storeQueue($branchService->id);

                //Infrom staff
                event(new NewQueueEvent($branchService->branch->id, $branchService->service->name));
            }

            $request->replace([
                'ticket_no' => $this->ticketNoGenerator($queue),
                'issue_time' => Carbon::now('Asia/Kuala_Lumpur'),
                'queue_id' => $queue->id, 
                'wait_time' => $queue->wait_time,
                'serve_time' => Carbon::now('Asia/Kuala_Lumpur')->addSeconds($queue->wait_time),
                'ppl_ahead' => $queue->total_ticket,
                'mobile_user_id' => $request->mobileUserId,
                'postponed' => 0,
                'status' => 'waiting'
            ]);

            $ticket = $this->storeTicket($request);

            //Update Queue
            $queue = $this->refreshQueue($queue);

            DB::commit();

			return response()->json(['success' => 'Ticket issued!']);

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
            
            return response()->json(["fail" => "Opps! We've some trouble!"]);
        }
    }

    public function postponeUserTicket(Request $request) {

    	$validator = Validator::make($request->all(), [
            'ticketId' => 'required',
            'postponeTime' => 'required|integer'
        ]);
        
        if ($validator->fails()) {

            return response()->json($validator->messages());
        }

        try {

            DB::beginTransaction();
            
            $ticket = Ticket::lockForUpdate()->find($request->ticketId);
            $queue = Queue::lockForUpdate()->find($ticket->queue_id);

            if(!$ticket || !$queue){

                DB::commit();

                return response()->json(["fail" => "Opps! We've some trouble!"]);
            }

            //check ticket is waiting 
            if($ticket->status != "waiting"){
                
                DB::commit();

                return response()->json(["fail" => "Opps! You've been served."]);
            }

            //check anyone behind 
            $ticketsBehind = $this->getTicketBehind($ticket);

            $ticketBehindNo = $ticketsBehind->count();

            if($ticketBehindNo < 1) {

                DB::commit();

                return response()->json(["fail" => "Opps! There's no one behind you."]);
            }

            //return variety of postpone time for customer to choose
            $avgWaitTime = $this->getCurrentAvgWaitTimeTicket($queue);
            $toSkip = ceil($request->postponeTime / $avgWaitTime);

            if($ticketBehindNo < $toSkip){

                DB::commit();

                return response()->json(["fail" => "Opps! Not enough person behind you."]);
            }

            /* Calculate issueTime */
            //find the issue time of toSkip-th person behind him
            $thatPerson = $ticketsBehind->values()[$toSkip - 1];
            $thatPersonIssueTime = $thatPerson->issue_time;

            //new issue time = that person issue time + 1 sec;
            $newIssueTime = Carbon::parse($thatPersonIssueTime, 'Asia/Kuala_Lumpur')->addSeconds(1);

            $ticket = $this->postponeTicketTo($ticket, $newIssueTime);

            DB::commit();

            DB::beginTransaction();

            $queue = Queue::lockForUpdate()->findOrFail($ticket->queue_id);

            //Update Queue
            $queue = $this->refreshQueue($queue);

            DB::commit();
            
            return response()->json(["success" => "Your ticket has been postponed!"]);

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
            
            return response()->json(["fail" => "Opps! We've some trouble!"]);
        }
    }

    public function cancelUserTicket(Request $request) {

    	$validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        
        if ($validator->fails()) {

            return response()->json($validator->messages());
        }

        try {

	    	DB::beginTransaction();

            //Update Ticket
	    	$ticket = Ticket::lockForUpdate()->find($request->id);

            if(!$ticket){

                DB::commit();
                
                return response()->json(["fail" => "Opps! We've some trouble!"]);
            }

            $ticket = $this->cancelTicket($ticket);

            //Update Calling
            $calling = Calling::where('ticket_id', $ticket->id)->where('active', 1)->lockForUpdate()->first();
            if($calling) {

                $calling = $this->stopCalling($calling);

                //Update Branch Counter
                $branchCounter = BranchCounter::lockForUpdate()->findOrFail($calling->branchCounter->id);
                $branchCounter->serving_queue = null;
                $branchCounter->save(); 
                $branchCounter = $this->branchCounterStopCalling($branchCounter);

                //inform the staff 
                event(new CancelTicketEvent($calling->id));
            }

            //Update Queue
            $queue = Queue::lockForUpdate()->findOrFail($ticket->queue_id);
            $queue = $this->refreshQueue($queue);

            DB::commit();

            return response()->json(["success" => "Ticket had been cancelled!"]);

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
            
            return response()->json(["fail" => "Opps! We've some trouble!"]);
        }
    }

    public function getUserCurrentTicketsAndDetails(Request $request){

    	$validator = Validator::make($request->all(), [
    		'id' => 'required'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } 
        else {

            DB::beginTransaction();

            try {

            	$tickets = Ticket::where('mobile_user_id', $request->id)->whereIn('status', ["waiting", "serving"])->get();

            	$ticketsWithDetails = [];

            	foreach($tickets as $ticket){

                    $queue = Queue::lockForUpdate()->findOrFail($ticket->queue_id);
                    $queue = $this->refreshQueue($queue);

                    DB::commit();

            		$ticketServingNow = Ticket::find($ticket->queue->ticket_serving_now);

            		$ticketWithDetails['id'] = $ticket->id;
            		$ticketWithDetails['ticket_no'] = $ticket->ticket_no;
            		$ticketWithDetails['issue_time'] = $ticket->issue_time;
            		$ticketWithDetails['queue_id'] = $ticket->queue_id;
            		$ticketWithDetails['wait_time'] = $ticket->wait_time;
            		$ticketWithDetails['mobile_user_id'] = $ticket->mobile_user_id;
            		$ticketWithDetails['ppl_ahead'] = $ticket->ppl_ahead;
            		$ticketWithDetails['postponed'] = $ticket->postponed;
            		$ticketWithDetails['status'] = $ticket->status;
            		$ticketWithDetails['branch_name'] = $ticket->queue->branchService->branch->name;
            		$ticketWithDetails['service_name'] = $ticket->queue->branchService->service->name;
            		$ticketWithDetails['serve_time'] = Carbon::now('Asia/Kuala_Lumpur')->addSeconds($ticket->wait_time)->format('h:i A');
    	    		$ticketWithDetails['disposed_time'] = $ticket->disposed_time;

    	        	if($ticketServingNow){
    	        		$ticketWithDetails['ticket_serving_now'] = $ticketServingNow->ticket_no;
    	        	}
    	        	else{
    	        		$ticketWithDetails['ticket_serving_now'] = $ticketServingNow;
    	        	}


            		array_push($ticketsWithDetails, $ticketWithDetails);
            	}

        		return response()->json($ticketsWithDetails);

            } catch (\Exception $e) {

                DB::rollback();

                throw $e;
            
                return response()->json(["fail" => "Opps! We've some trouble!"]);
            }
        }
    }

    public function getPostponeDetails(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            
            return response()->json($validator->messages());

        } 
        else {

            DB::beginTransaction();

            try {

                $ticket = Ticket::find($request->id);

                if($ticket){

                    $queue = Queue::lockForUpdate()->find($ticket->queue_id);

                    if(!$queue){

                        DB::commit();
                    
                        return response()->json(["fail" => "Opps! We've some trouble!"]);
                    }
                }
                else{
                    
                    DB::commit();
                    
                    return response()->json(["fail" => "Opps! We've some trouble!"]);
                }

                $calling = Calling::where('ticket_id', $ticket->id)->where('active', 1)->lockForUpdate()->first();

                if($calling) {

                    DB::commit();

                    return response()->json(["fail" => "It's too late to postpone. Please cancel the ticket."]);
                }

                $queue = $this->refreshQueue($queue);

                //check ticket is waiting 
                if($ticket->status != "waiting"){
                    
                    return response()->json(["fail" => "Opps! You've been served."]);
                }

                //check anyone behind 
                $ticketsBehind = $this->getTicketBehind($ticket);
                $ticketBehindNo = $ticketsBehind->count();

                if($ticketBehindNo < 1) {
                    return response()->json(["fail" => "Opps! There's no one behind you."]);
                }

                //return variety of postpone time for customer to choose
                $avgWaitTime = $this->getCurrentAvgWaitTimeTicket($queue);
                $postponeTimes = [];

                for($i = 1; $i <= $ticketBehindNo; $i++){
                    $postponeTime = $i * $avgWaitTime;

                    array_push($postponeTimes, $postponeTime);
                }

                DB::commit();
                
                if($postponeTimes)
                    return response()->json(["postpone_times" => $postponeTimes]);
                else
                    return response()->json(["fail" => "Opps! There's no available postpone time."]);

            } catch (\Exception $e) {

                DB::rollback();

                throw $e;
        
                return response()->json(["fail" => "Opps! We've some trouble!"]);
            }
        }
    }

    public function getTicketDetails(Request $request){

    	$validator = Validator::make($request->all(), [
    		'id' => 'required'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } 
        else {

            DB::beginTransaction();

            try {

            	$ticket = Ticket::find($request->id);

                $queue = Queue::lockForUpdate()->find($ticket->queue_id);

                if(!$ticket || !$queue){

                    DB::commit();
                    
                    return response()->json(["fail" => "Opps! We've some trouble!"]);
                }

                $queue = $this->refreshQueue($queue);


                DB::commit();

                if($ticket->status != "waiting" && $ticket->status != "serving"){

                    return response()->json(["fail" => "This ticket is no longer available."]);
                }

            	$ticketServingNow = Ticket::find($ticket->queue->ticket_serving_now);
                $serveTime = Carbon::parse($ticket->serve_time, 'Asia/Kuala_Lumpur');

        		$ticketWithDetails['id'] = $ticket->id;
        		$ticketWithDetails['ticket_no'] = $ticket->ticket_no;
        		$ticketWithDetails['issue_time'] = $ticket->issue_time;
        		$ticketWithDetails['queue_id'] = $ticket->queue_id;
        		$ticketWithDetails['wait_time'] = $ticket->wait_time;
        		$ticketWithDetails['mobile_user_id'] = $ticket->mobile_user_id;
        		$ticketWithDetails['ppl_ahead'] = $ticket->ppl_ahead;
        		$ticketWithDetails['postponed'] = $ticket->postponed;
        		$ticketWithDetails['status'] = $ticket->status;
        		$ticketWithDetails['branch_name'] = $ticket->queue->branchService->branch->name;
        		$ticketWithDetails['service_name'] = $ticket->queue->branchService->service->name;
        		$ticketWithDetails['serve_time'] = $serveTime->format('h:i A');
        		$ticketWithDetails['disposed_time'] = $ticket->disposed_time;

            	if($ticketServingNow){
            		$ticketWithDetails['ticket_serving_now'] = $ticketServingNow->ticket_no;
            	}
            	else{
            		$ticketWithDetails['ticket_serving_now'] = $ticketServingNow;
            	}

        		return response()->json($ticketWithDetails);
            } catch (\Exception $e) {

                DB::rollback();

                throw $e;
                
                return response()->json(["fail" => "Opps! We've some trouble!"]);
            }
        }
    }

    public function getUserHistories(Request $request){

		$validator = Validator::make($request->all(), [
    		'id' => 'required'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());
        } 
        else {
        	$appController = new AppController();

        	$tickets = Ticket::where('mobile_user_id', $request->id)->whereNotIn('status', ["waiting", "serving"])->get();

        	$ticketsWithDetails = [];

        	foreach($tickets as $ticket){

	        	$waitTimeString = '-';

	            //Calculate wait time
	            if($ticket->disposed_time != null){
		            $issueTime = Carbon::parse($ticket->issue_time, 'Asia/Kuala_Lumpur');
		            $disposedTime = Carbon::parse($ticket->disposed_time, 'Asia/Kuala_Lumpur');
		            $waitTime = $disposedTime->diffInSeconds($issueTime);
		            $waitTimeString = $appController->secToString($waitTime);
		        }

        		$ticketWithDetails['id'] = $ticket->id;
        		$ticketWithDetails['ticket_no'] = $ticket->ticket_no;
        		$ticketWithDetails['issue_time'] = $appController->formatDateTime($ticket->issue_time);
        		$ticketWithDetails['wait_time'] = $waitTimeString;
        		$ticketWithDetails['branch_name'] = $ticket->queue->branchService->branch->name;
        		$ticketWithDetails['service_name'] = $ticket->queue->branchService->service->name;
        		$ticketWithDetails['status'] = $ticket->status;

        		array_push($ticketsWithDetails, $ticketWithDetails);
        	}

    		return response()->json($ticketsWithDetails);
        }
    }

    public function getBranches(){

    	$branches = Branch::get();

    	return response()->json($branches);
    }

    public function getBranchServicesDetailsByBranchId(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            
            return response()->json($validator->messages());

        } 
        else {

            DB::beginTransaction();
            
            try {

                $branch = Branch::find($request->id);

                if(!$branch){

                    DB::commit();

                    return response()->json(["fail" => "Opps! We've some trouble!"]);
                }

                $branchServices = $branch->branchServices;

                $branchServicesWithDetails = [];

                foreach($branchServices as $branchService){

                    $queue = Queue::where('branch_service_id', $branchService->id)->where('active','=', 1)->lockForUpdate()->first();

                    $waitTime = 0;
                    $pendingTicket = 0;

                    if($queue != null){
                        $queue = $this->refreshQueue($queue);
                        $waitTime = $queue->wait_time;
                        $pendingTicket = $queue->pending_ticket;
                    }

                    $branchServiceWithDetails['id'] = $branchService->id;
                    $branchServiceWithDetails['service_name'] = $branchService->service->name;
                    $branchServiceWithDetails['wait_time'] = $waitTime;
                    $branchServiceWithDetails['pending_ticket'] = $pendingTicket;


                    array_push($branchServicesWithDetails, $branchServiceWithDetails);
                }

                DB::commit();

                return response()->json($branchServicesWithDetails);

            } catch (\Exception $e) {

                DB::rollback();

                throw $e;

                return response()->json(["fail" => "Opps! We've some trouble!"]);
            }
        }
    }

    public function calAvgWaitTime($totalTime, $totalTicket){

        $this->calAvgWaitTimeQueue($totalTime, $totalTicket);
        $this->calAvgWaitTimeTicket($totalTime, $totalTicket);
    }

    public function calCurrentTotalWaitTime($avgWaitTime, $totalTicket){

        $this->calCurrentTotalWaitTimeQueue($avgWaitTime, $totalTicket);
        $this->calCurrentTotalWaitTimeTicket($avgWaitTime, $totalTicket);
    }

    public function getCurrentAvgWaitTime($queue){

        $this->getCurrentAvgWaitTimeQueue($queue);
        $this->getCurrentAvgWaitTimeTicket($queue);
    }
}
