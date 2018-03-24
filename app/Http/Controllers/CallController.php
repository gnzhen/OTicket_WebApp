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
use JavaScript;
use App\Traits\QueueManager;
use App\Traits\TicketManager;
use App\Traits\CallingManager;
use App\Events\DisplayEvent;
use App\Events\CancelTicketEvent;

class CallController extends Controller
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
        $tickets = Ticket::whereIn('status', ['waiting', 'serving'])->orderBy('issue_time')->get();

        $branchServicesId = $branchServices->pluck('id');

        $queues = null;
        $calling = null;
        $timer = null;

        if(!$branchServicesId->isEmpty()){
            $queues = Queue::where('active', 1)->whereIn('branch_service_id', $branchServicesId)->with('branchService')->with('tickets')->get();
        }

        //get current calling of branch counter
        if($user->branchCounter != null){

            $calling = $user->branchCounter->active_callings->first();
            $branchCounter = $user->branchCounter;

            if($calling != null){

                $branchCounter->serving_queue = $calling->ticket->queue->id;
                $branchCounter->save();

                //Calculate timer
                $callTime = Carbon::parse($calling->call_time, 'Asia/Kuala_Lumpur');
                $now = Carbon::now('Asia/Kuala_Lumpur');
                $timer = $now->diffInSeconds($callTime);

                JavaScript::put([
                    'callingId' => $calling->id
                ]);
            }
            else {

                $branchCounter = $this->branchCounterStopCalling($branchCounter);

                JavaScript::put([
                    'callingId' => null
                ]);
            }
        }

        JavaScript::put([
            'branchId' => $user->branch_id
        ]);


        return view('call')->withUser($user)->withTickets($tickets)->withQueues($queues)->withBranch($branch)->withBranchServices($branchServices)->withBranchCounters($branchCounters)->withAppController($appController)->withCalling($calling)->withTimer($timer);
    }

     /**
     * Manage ticket calling
     */

    public function call(Request $request){

        //Roll back db if something fail
        DB::beginTransaction();

        try {

            $message = $this->callTicket($request);

            DB::commit();


            if($message == null) {

                $user = Auth::user();
                $branchCounter = $user->branchCounter;

                if($branchCounter != null) {

                    $message = $branchCounter->active_callings->first()->ticket->ticket_no;
                }

                return redirect()->route('call.index')->with('success', 'Previous customer busy. Calling next number '.$message.'.');
            }
            else if($message == "serving"){

                return redirect()->route('call.index')->with('fail', 'You are serving another ticket.');
            }
            else if($message == "no ticket"){
                
                return redirect()->route('call.index')->with('fail', 'No more ticket in queue.');
            }
            else if($message == "user busy"){
                
                return redirect()->route('call.index')->with('fail', 'Customer is busy now. No other number in queue.');
            }
            else {

                return redirect()->route('call.index')->with('success', 'Calling '.$message .'.');
            }

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }

    }

    public function callTicket(Request $request) {

        $queue = Queue::lockForUpdate()->findOrFail($request->queue_id);

        $branchCounter = BranchCounter::lockForUpdate()->findOrFail($request->branch_counter_id);

        $calling = $branchCounter->active_callings->first();

        //check staff is at another calling
        if($calling != null){

            return "serving";
        }
        else {

            //Update Branch Counter
            $branchCounter = $this->branchCounterCalling($branchCounter, $queue);

            //if no more ticket to call
            $theTicket = $queue->tickets->where('status', 'waiting')->sortBy('issue_time')->first();

            if($theTicket == null){

                return "no ticket";
            }
            else{

                $ticket = Ticket::lockForUpdate()->findOrFail($theTicket->id);

                //Postpone if user serving on another ticket
                if($this->ticketUserServing($ticket) != null){

                    //postpone ticket
                    $ticket = $this->postponeTicketAuto($ticket);

                    //Update Queue & tickets
                    $queue = $this->refreshQueue($queue);

                    //call next ticket if postpone ticket success
                    if($ticket != null){

                        $this->callTicket($request);
                    }
                    else {
                        //postpone fail 
                        return "user busy";
                    }
                    
                }
                else {

                    //Update Ticket
                    $ticket = $this->serveTicket($ticket);

                    //Create Calling
                    $request->replace([
                        'ticket_id' => $ticket->id, 
                        'branch_counter_id' => $request->branch_counter_id,
                        'call_time' => Carbon::now('Asia/Kuala_Lumpur'),
                        'active' => 1,
                    ]);

                    $calling = $this->storeCalling($request);

                    // //Postpone clashing ticket of current user if neccessary
                    $this->postponeOtherTicket($ticket);
                    
                    // //Update Queue & tickets
                    $queue = $this->refreshQueue($queue);
                    $queue->ticket_serving_now = $ticket->id;
                    $queue->save();

                    // //Trigger display
                    $this->triggerDisplay();
                }
            }
        }
        if($calling != null){

            return $calling->ticket->ticket_no;
        }
        else{
            return null;
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

                DB::commit();

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

            return redirect()->route('call.index')->with('fail', 'Fail to recall ' . $calling->ticket->ticket_no . '.');
        }
        
    }

    /**
     * Manage ticket skip
     */

    public function skip(Request $request){

        DB::beginTransaction();

        try {
            $calling = Calling::lockForUpdate()->findOrFail($request->calling_id);
            $queue = Queue::lockForUpdate()->findOrFail($request->queue_id);
            $branchCounter = BranchCounter::lockForUpdate()->findOrFail($request->branch_counter_id);
            $ticket = Ticket::lockForUpdate()->findOrFail($calling->ticket_id);

            //Update Ticket
            $ticket = $this->skipTicket($ticket);

            //Update Queue & tickets
            $queue = $this->refreshQueue($queue);

            //Update Calling
            $calling = $this->stopCalling($calling);

            //Update Branch Counter
            $branchCounter = $this->branchCounterStopCalling($branchCounter);

            DB::commit();
        
            return redirect()->route('call.index')->with('success', 'Skipped ' . $calling->ticket->ticket_no . '.');

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
            return redirect()->route('call.index')->with('fail', 'Fail to skip ' . $calling->ticket->ticket_no . '.');
        }
    }

    /**
     * Manage done of a serving
     */

    public function done(Request $request){

        DB::beginTransaction();

        try {
            $calling = Calling::lockForUpdate()->findOrFail($request->calling_id);
            $queue = Queue::lockForUpdate()->findOrFail($request->queue_id);
            $branchCounter = BranchCounter::lockForUpdate()->findOrFail($request->branch_counter_id);
            $ticket = Ticket::lockForUpdate()->findOrFail($calling->ticket_id);

            //Update Ticket
            $ticket = $this->doneTicket($ticket);

            //Update Queue & tickets
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

            DB::commit();

            if($queue->active == 0)
                return redirect()->route('call.index')->with('success', 'Done! Queue close!');

            return redirect()->route('call.index')->with('success', 'Done serving ' . $serving->ticket->ticket_no . '.');

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
            return redirect()->route('call.index')->with('fail', 'Fail to done serving ' . $serving->ticket->ticket_no . '.');
        }
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
        $branchCounter = BranchCounter::lockForUpdate()->findOrFail($id);

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

    public function calCurrentTotalWaitTime($avgWaitTime, $totalTicket){

        $this->calCurrentTotalWaitTimeQueue($avgWaitTime, $totalTicket);
        $this->calCurrentTotalWaitTimeTicket($avgWaitTime, $totalTicket);
    }

    public function getCurrentAvgWaitTime($queue){

        $this->getCurrentAvgWaitTimeQueue($queue);
        $this->getCurrentAvgWaitTimeTicket($queue);
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
