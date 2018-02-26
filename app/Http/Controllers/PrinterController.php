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
    use QueueManager, TicketManager;

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

            $branchService = BranchService::findOrFail($request->branch_service_id)->first();

            $queue = $branchService->active_queue->first();

            // $branchServiceWithQueue = BranchService::findOrFail($request->branch_service_id)->with('active_queues')->get();


            // $queues = Queue::where('active','=', 1)->get();

            if($queue == null){
                
                //create new queue
                $queue = $this->storeQueue($branchService->id);
            }

            //create new ticket
            $request->replace([
                'queue_id' => $queue->id, 
                'ticket_no' => $branchService->id,
                'issue_time' => Carbon::now(),
                'wait_time' => $queue->wait_time,
                'ppl_ahead' => $queue->total_ticket,
                'postponed' => 0,
                'status' => 'waiting'
            ]);

            $ticket = $this->storeTicket($request);
            
            Session::flash('success', 'Ticket issued!');

            return redirect()->route('printer.index');
        }
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
