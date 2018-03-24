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
use DB;
use App\Traits\QueueManager;
use App\Traits\TicketManager;
use App\Events\NewQueueEvent;

class PrinterController extends Controller
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
            'branchServiceId' => 'required|integer',
        ]);
        
        if ($validator->fails()) {

            Session::flash('fail', 'Issue ticket fail.');

            return back();
        }
        else {

            DB::beginTransaction();

            try {

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
                    'serve_time' => Carbon::now('Asia/Kuala_Lumpur')->addSeconds($queue->wait_time),
                    'queue_id' => $queue->id, 
                    'wait_time' => $queue->wait_time,
                    'ppl_ahead' => $queue->total_ticket,
                    'mobile_user_id' => $request->mobileUserId,
                    'postponed' => 0,
                    'status' => 'waiting'
                ]);

                $ticket = $this->storeTicket($request);

                //Update Queue & tickets
                $queue = $this->refreshQueue($queue);

                DB::commit();

                return redirect()->route('printer.index')->with('success', 'Ticket issued!');

            } catch (\Exception $e) {

                DB::rollback();

                throw $e;
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
