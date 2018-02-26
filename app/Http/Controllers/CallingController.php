<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Queue;
use App\BranchService;
use App\Calling;
use App\Traits\QueueManager;
use App\Traits\TicketManager;
use Session;

class CallingController extends Controller
{
    use QueueManager, TicketManager;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // $queue = Queue::findOrFail($request->queue_id);

        // //Manage Ticket
        // $ticket = $queue->tickets->where('status', '=', 'waiting')->first();

        // $this->serveTicket($ticket);

        // //Manage Calling
        // $calling = new Calling();
        // $calling->ticket_id = $ticket->id;
        // $calling->branch_counter_id = $user->branchCounter->id;
        // $calling->ticket_id = $ticket->id;
        // $calling->save();

        // //Manage Queue
        // $this->updateTicketServingNow($queue, $ticket->id);
    
        // return redirect()->route('call.index');
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
