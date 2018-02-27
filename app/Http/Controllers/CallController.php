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
use App\Traits\CallingManager;

class CallController extends Controller
{
    use QueueManager, TicketManager, CallingManager;

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

        //get current calling of branch counter
        if($user->branchCounter != null){

            $calling = $user->branchCounter->callings->where('active','=', 1)->sortByDesc('id')->first();

            if($calling != null){

                //Calculate timer
                $callTime = Carbon::parse($calling->call_time);
                $now = Carbon::now();
                $timer = $now->diffInSeconds($callTime);
            }
        }

        // foreach($queuestickets as $ticket)
        //     $this->waitTicket($ticket);

        return view('call')->withUser($user)->withTickets($tickets)->withQueues($queues)->withBranch($branch)->withBranchServices($branchServices)->withBranchCounters($branchCounters)->withAppController($appController)->withCalling($calling)->withTimer($timer);
    }

     /**
     * Manage ticket calling
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function call(Request $request){

        $queue = Queue::findOrFail($request->queue_id);
        $branchCounter = BranchCounter::findOrFail($request->branch_counter_id);
        $branchService = BranchService::findOrFail($queue->branchService->id);

        $ticket = $queue->tickets->where('status', '=', 'waiting')->first();

        if($ticket == null){

            return redirect()->route('call.index')->with('fail', 'No more ticket in queue.');
        }

        //Update Ticket
        $this->serveTicket($ticket);

        //Create Calling
        $request->replace([
            'ticket_id' => $ticket->id, 
            'branch_counter_id' => $request->branch_counter_id,
            'call_time' => Carbon::now(),
            'active' => 1,
        ]);
        $calling = $this->storeCalling($request);

        //Update Queue
        $queue = $this->updateQueueServingNow($queue, $ticket->id);

        //Update Branch Counter
        $branchCounter->serving_queue = $queue->id;
        $branchCounter->save();  
    
        return redirect()->route('call.index');
    }

    /**
     * Manage done of a serving
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function done(Request $request){

        $calling = Calling::findOrFail($request->calling_id);
        $queue = Queue::findOrFail($request->queue_id);
        $branchCounter = BranchCounter::findOrFail($request->branch_counter_id);

        //Update Ticket
        $ticket = Ticket::findOrFail($calling->ticket_id);
        $ticket = $this->doneTicket($ticket);

        //Update Queue
        $queue = $this->refreshQueue($queue);

        //Update Calling
        $calling = $this->stopCalling($calling);

        //Create Serving
        $serving = new Serving();

        $serving->ticket_id = $calling->ticket_id;
        $serving->staff_id = $request->user_id;
        $serving->branch_counter_id = $calling->branch_counter_id;
        $serving->serve_time = $calling->call_time;
        $serving->done_time = Carbon::now();

        $serving->save();

        //Update Branch Counter
        $branchCounter->serving_queue = null;
        $branchCounter->save(); 

        return redirect()->route('call.index');
    }

    /**
     * Manage ticket recall
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function recall(Request $request){

        //
    }

    /**
     * Manage ticket skip
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function skip(Request $request){

        // 
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
