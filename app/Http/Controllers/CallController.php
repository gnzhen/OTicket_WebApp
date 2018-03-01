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
    use QueueManager { 
        calAvgWaitTime as protected calAvgWaitTimeQueue; 
        calTotalWaitTime as protected calTotalWaitTimeQueue;
        getAvgWaitTime as protected getAvgWaitTimeQueue;
    } 
    use TicketManager { 
        calAvgWaitTime as protected calAvgWaitTimeTicket; 
        calTotalWaitTime as protected calTotalWaitTimeTicket;
        getAvgWaitTime as protected getAvgWaitTimeTicket;
    }
    use CallingManager;

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
        $queues = Queue::where('active','=', 1)->with('branchService')->with('tickets')->get();
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

        return view('call')->withUser($user)->withTickets($tickets)->withQueues($queues)->withBranch($branch)->withBranchServices($branchServices)->withBranchCounters($branchCounters)->withAppController($appController)->withCalling($calling)->withTimer($timer);
    }

     /**
     * Manage ticket calling
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
        $ticket = $this->serveTicket($ticket);

        //Update Queue
        $queue = $this->refreshQueue($queue);

        //Create Calling
        $request->replace([
            'ticket_id' => $ticket->id, 
            'branch_counter_id' => $request->branch_counter_id,
            'call_time' => Carbon::now(),
            'active' => 1,
        ]);
        $calling = $this->storeCalling($request);

        //Update Branch Counter
        $branchCounter = $this->branchCounterCalling($branchCounter, $queue);

        Session::flash('success', 'Calling ' . $calling->ticket->ticket_no . '.');
    
        return redirect()->route('call.index');
    }

    /**
     * Manage ticket recall
     */

    public function recall(Request $request){

        $calling = Calling::findOrFail($request->calling_id);

        if($calling->active == 0){

            return redirect()->route('call.index')->with('fail', 'Please call next.');
        }

        $calling = $this->stopCalling($calling);

        //Create Calling
        $request->replace([
            'ticket_id' => $calling->ticket_id, 
            'branch_counter_id' => $calling->branch_counter_id,
            'call_time' => Carbon::now(),
            'active' => 1,
        ]);

        $calling = $this->storeCalling($request);

        Session::flash('success', 'Recalling ' . $calling->ticket->ticket_no . '.');
    
        return redirect()->route('call.index');
    }

    /**
     * Manage ticket skip
     */

    public function skip(Request $request){

        $calling = Calling::findOrFail($request->calling_id);
        $queue = Queue::findOrFail($request->queue_id);
        $branchCounter = BranchCounter::findOrFail($request->branch_counter_id);

        //Update Ticket
        $ticket = Ticket::findOrFail($calling->ticket_id);
        $ticket = $this->skipTicket($ticket);

        //Update Queue
        $queue = $this->refreshQueue($queue);

        //Update Calling
        $calling = $this->stopCalling($calling);

        //Update Branch Counter
        $branchCounter = $this->branchCounterStopCalling($branchCounter);

        Session::flash('success', 'Skip ' . $calling->ticket->ticket_no . '.');
    
        return redirect()->route('call.index');
    }

    /**
     * Manage done of a serving
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
        $branchCounter = $this->branchCounterStopCalling($branchCounter);


        //Send serving duration to index
        $serveTime = Carbon::parse($serving->serve_time);
        $doneTime = Carbon::parse($serving->done_time);
        $servingDuration = $doneTime->diffInSeconds($serveTime);

        Session::flash('success', 'Done serving ' . $serving->ticket->ticket_no . '.');

        return redirect()->route('call.index');
    }

    /**
     * Manage open of counter
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

     /**
     * Manage close of counter
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function closeCounter($id)
    {
        $branchCounter = BranchCounter::findOrFail($id);

        //Reject close counter during serving
        if($branchCounter->serving_queue != null){

            Session::flash('fail', 'Counter cannot be closed during serving.');

            return redirect()->route('call.index');
        }

        $branchCounter->staff_id = null;
        $branchCounter->serving_queue = null;

        $branchCounter->save();

        Session::flash('success', 'Counter closed.');

        return redirect()->route('call.index');
    }

    public function calAvgWaitTime($totalTime, $totalTicket){

        $this->calAvgWaitTimeQueue($totalTime, $totalTicket);
        $this->calAvgWaitTimeTicket($totalTime, $totalTicket);
    }

    public function calTotalWaitTime($avgWaitTime, $totalTicket){

        $this->calAvgWaitTimeQueue($avgWaitTime, $totalTicket);
        $this->calAvgWaitTimeTicket($avgWaitTime, $totalTicket);
    }

    public function getAvgWaitTime($queue){

        $this->calAvgWaitTimeQueue($queue);
        $this->calAvgWaitTimeTicket($queue);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
