<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use App\Branch;
use App\BranchService;
use App\Queue;
use App\User;
use Carbon\Carbon;
use Session;
use App\Traits\QueueManager;
use App\Traits\TicketManager;

class PrinterController extends Controller
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
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
        $branchServices = BranchService::where('branch_id', '=', $user->branch_id)->with('service')->get();
        $queues = Queue::where('active','=', 1)->with('branchService')->get();

        return view('printer')->withUser($user)->withQueues($queues)->withBranch($branch)->withBranchServices($branchServices)->withAppController($appController);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_service_id' => 'required|integer',
        ]);
        
        if ($validator->fails()) {

            Session::flash('fail', 'Issue ticket fail.');

            return back();
        }
        else {

            $branchService = BranchService::findOrFail($request->branch_service_id);

            $queue = $branchService->active_queue->first();

            if($queue == null){
                
                //Create Queue
                $queue = $this->storeQueue($branchService->id);
            }

            $request->replace([
                'ticket_no' => $this->ticketNoGenerator($queue),
                'issue_time' => Carbon::now(),
                'queue_id' => $queue->id, 
                'wait_time' => $queue->wait_time,
                'ppl_ahead' => $queue->total_ticket,
                'postponed' => 0,
                'status' => 'waiting'
            ]);

            $ticket = $this->storeTicket($request);

            //Update Queue
            $total_ticket = $this->refreshQueue($queue);

            Session::flash('success', 'Ticket issued!');

            return redirect()->route('printer.index');
        }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
