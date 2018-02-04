<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use App\Branch;
use App\Service;
use App\Counter;
use App\BranchCounter;
use App\BranchService;

class ConfigController extends Controller
{
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
        $branches = Branch::select('id as branch_id', 'name', 'desc')->get();
        $services = Service::select('id as service_id', 'name')->get();
        $counters = Counter::select('id as counter_id', 'name')->get();
        $waitTimeStr = AppController::secToString(1150);
        $branchCounters = BranchCounter::join('branches', 'branches.id', '=', 'branch_counters.branch_id')
            ->join('counters', 'counters.id', '=', 'branch_counters.counter_id')
            ->select(
                'branch_counters.id as branch_counter_id', 
                'branch_counters.branch_id', 
                'branches.name as branch_name',
                'branch_counters.counter_id', 
                'counters.name as counter_name',
                'branch_counters.staff_username')
            ->get();
        $branchServices = BranchService::join('branches', 'branches.id', '=', 'branch_services.branch_id')
            ->join('services', 'services.id', '=', 'branch_services.service_id')
            ->select(
                'branch_services.id as branch_services_id', 
                'branch_services.branch_id',  
                'branches.name as branch_name', 
                'branch_services.service_id', 
                'services.name as service_name',
                'branch_services.avg_wait_time', 
                'branch_services.default_avg_wait_time')
            ->get();

        return view('configuration')->withBranches($branches)->withServices($services)->withCounters($counters)->withBranchCounters($branchCounters)->withBranchServices($branchServices)->withAppController(new AppController);
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
        //
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
