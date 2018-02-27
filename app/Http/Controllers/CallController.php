<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use App\Branch;
use App\Service;
use App\Counter;
use App\BranchCounter;
use App\BranchService;
use App\Ticket;
use App\Queue;
use App\Serving;
use App\Calling;
use App\User;
use Carbon\Carbon;
use Session;
use App\Traits\QueueManager;
use App\Traits\TicketManager;

class CallController extends Controller
{
    use QueueManager, TicketManager;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appController = new AppController;
        $user = Auth::user();
        $branch = Branch::where('id', '=', $user->branch_id)->get();
        $branchServices = BranchService::where('branch_id', '=', $user->branch_id)->get();
        $branchCounters = BranchCounter::where('branch_id', '=', $user->branch_id)->get();
        $tickets = Ticket::where('status','=','waiting')->get();
        $queues = Queue::where('active','=', 1)->with('branchService')->get();
        $calling = null;
        $timer = null;

        if($user->branchCounter != null){
            $calling = $user->branchCounter->callings->where('active','=', 1)->first();

            //Handle timer
            $callTime = Carbon::parse($calling->call_time);
            $now = Carbon::now();
            $timer = $now->diffInSeconds($callTime);
        }

        return view('call')->withUser($user)->withTickets($tickets)->withQueues($queues)->withBranch($branch)->withBranchServices($branchServices)->withBranchCounters($branchCounters)->withAppController($appController)->withCalling($calling)->withTimer($timer);
    }

    public function call(Request $request){

        $queue = Queue::findOrFail($request->queue_id);
        $branchCounter = BranchCounter::findOrFail($request->branch_counter_id);
        $branchService = BranchService::findOrFail($queue->branchService->id);

        $ticket = $queue->tickets->where('status', '=', 'waiting')->first();

        if($ticket != null){
            //Manage Ticket
            $this->serveTicket($ticket);

            //Manage Calling
            $calling = new Calling();
            $calling->ticket_id = $ticket->id;
            $calling->branch_counter_id = $request->branch_counter_id;
            $calling->call_time = Carbon::now();
            $calling->active = 1;
            $calling->save();

            //Manage Queue
            $this->updateQueue($queue, $ticket->id);

            //Manage Branch Counter
            $branchCounter->serving_queue = $queue->id;
            $branchCounter->save();  
        }
        else{
            Session::flash('fail', 'No more ticket in queue.');

            return redirect()->route('call.index');
        }

        echo $request->branch_counter_id;
    
        return redirect()->route('call.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function openCounter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branchCounter' => 'required|integer',
        ]);

        if ($validator->fails()) {

            return back()->withErrors($validator)->withInput();
        }
        else {
            $branchCounter = BranchCounter::findOrFail($request->branchCounter);

            if($branchCounter->staff_id == null){

                $branchCounter->staff_id = $request->user_id;
                $branchCounter->save();
                        
                Session::flash('success', 'Counter opened.');
            }
            else {

                Session::flash('fail', 'The counter is not available now.');
            }

            return redirect()->route('call.index');
        }
    }

    public function closeCounter($id)
    {
        $branchCounter = BranchCounter::findOrFail($id);

        $branchCounter->staff_id = null;
        $branchCounter->status = null;

        $branchCounter->save();

        Session::flash('success', 'Counter closed.');

        return redirect()->route('call.index');
    }
}
