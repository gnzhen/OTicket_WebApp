<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\MobileUser;
use App\Branch;
use App\Service;
use App\Queue;
use App\Ticket;
use App\BranchService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use App\Events\NewQueueEvent;
use App\Traits\QueueManager;
use App\Traits\TicketManager;
use Carbon\Carbon;
use DB;

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

	private $client;

    public function __construct(){

		$this->client = Client::find(1);
	}

	public function test(Request $request){

		return response()->json(['test' => 'test']);
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
	        $mobileUser->password = bcrypt('password');

        	$mobileUser->save();

    		return response()->json(["success" => "Register Success!"]);
        }
    	
    }

    public function login(Request $request) {

    	$validator = Validator::make($request->all(), [
    		'email' => 'required|email',
    		'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } 
        else {
        	$mobileUser = MobileUser::where('email', '=' ,$request->email)->firstOrFail();

    		return response()->json(["success" => "Login Success!"]);
        }
    	
    }

    public function getUserCurrentTicket(Request $request){

    	$validator = Validator::make($request->all(), [
    		'id' => 'required'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } 
        else {
        	$tickets = Ticket::where('mobile_user_id', '=' ,$request->id)->whereIn('status', ["waiting", "serving"])->get();

    		return response()->json($tickets);
        }
    }

    public function getBranches(){

    	$branches = Branch::get();

    	return response()->json($branches);
    }

    public function getServices(){

    	$services = Service::get();

    	return response()->json($services);
    }

    public function getBranchServices(){

    	$branchServices = BranchService::get();

    	return response()->json($branchServices);
    }

    public function getBranchServicesByBranchId(Request $request){

    	$validator = Validator::make($request->all(), [
    		'id' => 'required'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } 
        else {

        	$branch = Branch::findOrFail($request->id);

        	$branchServices = $branch->branchServices;

    		return response()->json($branchServices);
        }
    }

    public function getQueuesByBranchId(Request $request){

    	$validator = Validator::make($request->all(), [
    		'id' => 'required'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());
        } 
        else {
        	$branch = Branch::findOrFail($request->id);

        	$branchServices = $branch->branchServices;

        	$queues = []; 

        	foreach($branchServices as $branchService){

                $queue = $branchService->active_queue->first();

        		if($queue != null)
        			array_push($queues, $queue);
        	}

    		return response()->json($queues);
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
    	
    	/* Check multi ticket */
    	$branchService = BranchService::findOrFail($request->branchServiceId);

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
    		}
    	}
        	
		 DB::beginTransaction();

        try {

            $branchService = BranchService::findOrFail($request->branchServiceId);

            $queue = $branchService->active_queue->first();

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
                'ppl_ahead' => $queue->total_ticket,
                'mobile_user_id' => $request->mobileUserId,
                'postponed' => 0,
                'status' => 'waiting'
            ]);

            $ticket = $this->storeTicket($request);

            //Update Queue
            $total_ticket = $this->refreshQueue($queue);

            DB::commit();
            
			return response()->json($ticket);

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
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
