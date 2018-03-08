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
use App\MobileUser;
use Carbon\Carbon;
use Session;
use DB;
use App\Traits\QueueManager;
use App\Traits\TicketManager;
use App\Traits\CallingManager;
use App\Events\DisplayEvent;

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

        $branchServicesId = $branchServices->pluck('id');

        $queues = null;
        $calling = null;
        $timer = null;

        if(!$branchServicesId->isEmpty()){
            $queues = Queue::where('active', 1)->whereIn('branch_service_id', $branchServicesId)->with('branchService')->with('tickets')->get();
        }

        //get current calling of branch counter
        if($user->branchCounter != null){

            $calling = $user->branchCounter->callings->where('active','=', 1)->sortByDesc('id')->first();

            if($calling != null){

                //Calculate timer
                $callTime = Carbon::parse($calling->call_time, 'Asia/Kuala_Lumpur');
                $now = Carbon::now('Asia/Kuala_Lumpur');
                $timer = $now->diffInSeconds($callTime);
            }
        }

        return view('call')->withUser($user)->withTickets($tickets)->withQueues($queues)->withBranch($branch)->withBranchServices($branchServices)->withBranchCounters($branchCounters)->withAppController($appController)->withCalling($calling)->withTimer($timer);
    }

     /**
     * Manage ticket calling
     */

    public function call(Request $request){
        
        //Roll back db if something fail
        DB::beginTransaction();

        try {
            $queue = Queue::lockForUpdate()->findOrFail($request->queue_id);

            $ticket = $queue->tickets->where('status', '=', 'waiting')->first();

            if($ticket == null){

                return redirect()->route('call.index')->with('fail', 'No more ticket in queue.');
            }

            //Check ticket user serving by other ticket or not
            if($ticket->mobile_user_id != null){

                $mobileUser = MobileUser::findOrFail($ticket->mobile_user_id);

                $tickets = $mobileUser->tickets;

                foreach($tickets as $ticket){
                    if($ticket->status == 'serving'){

                        //postpone ticket

                        return redirect()->route('call.index')->with('fail', 'User is busy now.');
                    }
                }
            }

            //Update Ticket
            $ticket = $this->serveTicket($ticket);

            //Update Queue
            $queue = $this->refreshQueue($queue);

            //Create Calling
            $request->replace([
                'ticket_id' => $ticket->id, 
                'branch_counter_id' => $request->branch_counter_id,
                'call_time' => Carbon::now('Asia/Kuala_Lumpur'),
                'active' => 1,
            ]);

            $calling = $this->storeCalling($request);

            //Update Branch Counter
            $branchCounter = BranchCounter::lockForUpdate()->findOrFail($request->branch_counter_id);

            $branchCounter = $this->branchCounterCalling($branchCounter, $queue);


            //Trigger display
            $this->triggerDisplay();

            DB::commit();

            return redirect()->route('call.index')->with('success', 'Calling ' . $calling->ticket->ticket_no . '.');

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;

            return redirect()->route('call.index')->with('fail', 'Cannot call ticket');

        }
    }

    /**
     * Manage ticket recall
     */

    public function recall(Request $request){

        DB::beginTransaction();

        try {
            $calling = Calling::findOrFail($request->calling_id);

            if($calling->active == 0){

                return redirect()->route('call.index')->with('fail', 'Please call next.');
            }

            $calling = $this->stopCalling($calling);

            //Create Calling
            $request->replace([
                'ticket_id' => $calling->ticket_id, 
                'branch_counter_id' => $calling->branch_counter_id,
                'call_time' => Carbon::now('Asia/Kuala_Lumpur'),
                'active' => 1,
            ]);

            $calling = $this->storeCalling($request);

            //Trigger display
            $messages = $this->triggerDisplay();
            
            DB::commit();
        
            return redirect()->route('call.index')->with('success', 'Recalling ' . $calling->ticket->ticket_no . '.');

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }
        
    }

    /**
     * Manage ticket skip
     */

    public function skip(Request $request){

        DB::beginTransaction();

        try {
            $calling = Calling::findOrFail($request->calling_id);
            $queue = Queue::lockForUpdate()->findOrFail($request->queue_id);
            $branchCounter = BranchCounter::lockForUpdate()->findOrFail($request->branch_counter_id);

            //Update Ticket
            $ticket = Ticket::lockForUpdate()->findOrFail($calling->ticket_id);
            $ticket = $this->skipTicket($ticket);

            //Update Queue
            $queue = $this->refreshQueue($queue);

            //Update Calling
            $calling = $this->stopCalling($calling);

            //Update Branch Counter
            $branchCounter = $this->branchCounterStopCalling($branchCounter);

            DB::commit();
        
            return redirect()->route('call.index')->with('success', 'Skip ' . $calling->ticket->ticket_no . '.');

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }
    }

    /**
     * Manage done of a serving
     */

    public function done(Request $request){

        DB::beginTransaction();

        try {
            $calling = Calling::findOrFail($request->calling_id);
            $queue = Queue::lockForUpdate()->findOrFail($request->queue_id);
            $branchCounter = BranchCounter::lockForUpdate()->findOrFail($request->branch_counter_id);

            //Update Ticket
            $ticket = Ticket::lockForUpdate()->findOrFail($calling->ticket_id);
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
            $serving->done_time = Carbon::now('Asia/Kuala_Lumpur');

            $serving->save();

            //Update Branch Counter
            $branchCounter = $this->branchCounterStopCalling($branchCounter);


            //Send serving duration to index
            $serveTime = Carbon::parse($serving->serve_time, 'Asia/Kuala_Lumpur');
            $doneTime = Carbon::parse($serving->done_time, 'Asia/Kuala_Lumpur');
            $servingDuration = $doneTime->diffInSeconds($serveTime);

            DB::commit();

            return redirect()->route('call.index')->with('success', 'Done serving ' . $serving->ticket->ticket_no . '.');

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }
    }

    public function triggerDisplay(){
        $user = Auth::user();
        $branchCounters = BranchCounter::where('branch_id', '=', $user->branch_id)->get();
        $branchCountersId = $branchCounters->pluck('id');
        $callings = Calling::select('id', 'ticket_id', 'branch_counter_id', 'call_time')->whereIn('branch_counter_id', $branchCountersId)->whereDate('call_time', '>=', Carbon::today('Asia/Kuala_Lumpur'))->orderBy('call_time', 'desc')->get();

        echo $callings;

        $callMessages = $this->generateDisplayMessage($callings);

        $messages = $this->displayCalling($callMessages);

        return $messages;
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

            DB::beginTransaction();

            try {
                $branchCounter = BranchCounter::lockForUpdate()->findOrFail($request->branchCounter);

                if($branchCounter->staff_id == null){

                    $branchCounter->staff_id = $request->user_id;
                    $branchCounter->save();
                            
                    Session::flash('success', 'Counter opened.');
                }
                else {

                    Session::flash('fail', 'The counter is not available now.');
                }

                DB::commit();

                return redirect()->route('call.index');

            } catch (\Exception $e) {

                DB::rollback();

                throw $e;
            }
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

        $this->calTotalWaitTimeQueue($avgWaitTime, $totalTicket);
        $this->calTotalWaitTimeTicket($avgWaitTime, $totalTicket);
    }

    public function getAvgWaitTime($queue){

        $this->getAvgWaitTimeQueue($queue);
        $this->getAvgWaitTimeTicket($queue);
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
