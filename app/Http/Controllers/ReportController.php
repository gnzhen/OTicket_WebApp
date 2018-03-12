<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Ticket;
use App\Queue;
use App\Branch;
use App\Service;
use App\BranchService;
use App\Http\Controllers\AppController;
use App\Traits\QueueManager;
use App\Traits\WaitTimeManager;
use Carbon\Carbon;
use Charts;
use DB;
// use PDF;

class ReportController extends Controller
{
    use QueueManager { 
        calAvgWaitTime as protected calAvgWaitTimeQueue; 
        calCurrentTotalWaitTime as protected calCurrentTotalWaitTimeQueue;
        getCurrentAvgWaitTime as protected getCurrentAvgWaitTimeQueue;
    }
    use WaitTimeManager{ 
        calAvgWaitTime as protected calAvgWaitTimeWT; 
        calCurrentTotalWaitTime as protected calCurrentTotalWaitTimeWT;
        getCurrentAvgWaitTime as protected getCurrentAvgWaitTimeWT;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appController = new AppController;
        return view('report');
    }

    public function result(Request $request){

        $validator = Validator::make($request->all(), [
            'reportOption' => 'required|digits_between:0,1',
            'groupBy' => 'required|digits_between:0,3',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {

            return back()->withErrors($validator)->withInput();
        }
        else {

            $reportOption = $request->reportOption;
            $groupBy = $request->groupBy;

            $dateFrom = $request->dateFrom;
            $dateTo = $request->dateTo;

            $chart = null;

            $queues = $this->getQueuesFromDate($dateFrom, $dateTo);
            $tickets = $this->getTicketsFromDate($dateFrom, $dateTo);


            if($queues == null || $tickets == null){

                return back()->with('fail', 'No data to generate report.');
            }

            $date = "";

            if($dateFrom != null)
                $date = $date . "From " . $dateFrom . " ";

            if($dateTo != null)
                $date = $date . "Until " . $dateTo;

            if($dateFrom == null && $dateTo == null)
                $date = "From start to end";

            //Customer Traffic
            if($reportOption == 0){

                $chart = $this->generateTraffic($tickets, $queues, $groupBy, $date);

            }
            //Avg Wait Time
            else if($reportOption == 1){

                    $chart = $this->generateAvgWaitTime($queues, $groupBy, $date);
                
            }

            if($chart == null){
                return back()->with('fail', 'Fail to generate report.');
            }

            return view('partials.report.result', ['chart' => $chart]);
        }
    }

    public function generateTraffic($tickets, $queues, $groupBy, $date) {

        //Array for chart label and values
        $labels = [];
        $values = [];

        switch ($groupBy) {

            //branch
            case 0: {

                $branches = Branch::select('id', 'code', 'name')->get();

                foreach($branches as $branch){

                    $branchServices = $branch->branchServices;
                    $value = 0;

                    foreach($branchServices as $branchService){

                        $branchServiceTotalTicket = 0;

                        // $queues = $branchService->queues;
                        $branchServiceQueues = $queues->where('branch_service_id', $branchService->id);

                        $value += $branchServiceQueues->sum('total_ticket');
                    }
                

                    $label = $branch->name . ' (' . $branch->code . ')';

                    array_push($labels, $label);
                    array_push($values, $value);
                }

                $chart = Charts::create('bar', 'highcharts')
                    ->title("Customer Traffic by Branch (" . $date . ")")
                    ->elementLabel("Total Tickets")
                    ->dimensions(0, 400)
                    ->labels($labels)
                    ->values($values);

                return $chart;
                break;
            }
            //service
            case 1: {
                
                $services = Service::get();

                foreach($services as $service){

                    $branchServices = $service->branchServices;
                    $value = 0;

                    foreach($branchServices as $branchService){

                        $branchServiceTotalTicket = 0;

                        // $queues = $branchService->queues;
                        $branchServiceQueues = $queues->where('branch_service_id', $branchService->id);

                        $value += $branchServiceQueues->sum('total_ticket');
                    }
                

                    $label = $service->name . ' (' . $service->code . ')';

                    array_push($labels, $label);
                    array_push($values, $value);
                }

                $chart = Charts::create('bar', 'highcharts')
                    ->title("Customer Traffic by Service (" . $date . ")")
                    ->elementLabel("Total Tickets")
                    ->dimensions(0, 400)
                    ->labels($labels)
                    ->values($values);

                return $chart;
                break;
            }
            //day
            case 2: {

                $chart = Charts::database($tickets, 'bar', 'highcharts')
                    ->title("Customer Traffic by Day (" . $date . ")")
                    ->elementLabel("Total Tickets")
                    ->dimensions(0, 400)
                    ->responsive(false)
                    ->groupByDay(null, null, true);

                return $chart;
                break;
            }
            //month
            case 3: {

                $chart = Charts::database($tickets, 'bar', 'highcharts')
                    ->title("Customer Traffic by Month (" . $date . ")")
                    ->elementLabel("Total Tickets")
                    ->dimensions(0, 400)
                    ->responsive(false)
                    ->groupByMonth(null, true);

                return $chart;
                break;
            }
        }
    }

    public function generateAvgWaitTime($queues, $groupBy, $date) {

        //Array for chart label and values
        $labels = [];
        $values = [];

        switch ($groupBy) {
            //branch
            case 0: {

                $branches = Branch::select('id', 'code', 'name')->get();

                foreach($branches as $branch){

                    $branchServices = $branch->branchServices;
                    $totalBranchServiceAvgWaitTime = 0;
                    $noOfBranchServices = 0;
                    $value = 0;

                    foreach($branchServices as $branchService){

                        $branchServiceAvgWaitTime = 0;

                        $branchServiceQueues = $queues->where('branch_service_id', $branchService->id);

                        if($queues != null){

                            $branchServiceAvgWaitTime = $this->calQueuesAvgWaitTime($branchServiceQueues);

                            $totalBranchServiceAvgWaitTime += $branchServiceAvgWaitTime;

                            $noOfBranchServices++;
                        }
                    }

                    $label = $branch->name . ' (' . $branch->code . ')';
                    $value = $this->calAvgWaitTimeWT($totalBranchServiceAvgWaitTime, $noOfBranchServices);

                    array_push($labels, $label);
                    array_push($values, round(($value/60), 2));
                }

                $chart = Charts::create('bar', 'highcharts')
                    ->title("Average Wait Time of Branch (" . $date . ")")
                    ->elementLabel("Wait Time (minutes)")
                    ->dimensions(0, 400)
                    ->labels($labels)
                    ->values($values);

                return $chart;
                break;
            }
            //service
            case 1: {

                $services = Service::get();

                foreach($services as $service){

                    $branchServices = $service->branchServices;
                    $totalBranchServiceAvgWaitTime = 0;
                    $noOfBranchServices = 0;
                    $value = 0;

                    foreach($branchServices as $branchService){

                        $branchServiceAvgWaitTime = 0;

                        $branchServiceQueues = $queues->where('branch_service_id', $branchService->id);

                        if($queues != null){

                            $branchServiceAvgWaitTime = $this->calQueuesAvgWaitTime($branchServiceQueues);
                            $totalBranchServiceAvgWaitTime += $branchServiceAvgWaitTime;

                            $noOfBranchServices++;
                        }
                    }
                
                    $label = $service->name . ' (' . $service->code . ')';
                    $value = $this->calAvgWaitTimeWT($totalBranchServiceAvgWaitTime, $noOfBranchServices);

                    array_push($labels, $label);
                    array_push($values, round(($value/60), 2));
                }

                $chart = Charts::create('bar', 'highcharts')
                    ->title("Average Wait Time of Service (" . $date . ")")
                    ->elementLabel("Wait Time (minutes)")
                    ->dimensions(0, 400)
                    ->labels($labels)
                    ->values($values);

                return $chart;
                break;
            }
            //day
            case 2: {
                $queuesByDays = $queues
                    ->groupBy(function($queue) {
                        return Carbon::parse($queue->start_time, 'Asia/Kuala_Lumpur')
                        ->format('d'); // grouping by days
                    });

                foreach($queuesByDays as $day => $queuesOfDay){
                    
                    $value = $this->calQueuesAvgWaitTime($queuesOfDay);

                    $dateObj   = Carbon::createFromFormat('d', $day);
                    $label = $dateObj->format('d F Y'); 

                    array_push($labels, $label);
                    array_push($values, round(($value/60), 2));
                }

                $chart = Charts::create('bar', 'highcharts')
                    ->title("Average Wait Time of Branch (" . $date . ")")
                    ->elementLabel("Wait Time (minutes)")
                    ->dimensions(0, 400)
                    ->labels($labels)
                    ->values($values);

                return $chart;
                break;
            }
            //month
            case 3: {
                $queuesByMonths = $queues
                    ->groupBy(function($queue) {
                        return Carbon::parse($queue->start_time, 'Asia/Kuala_Lumpur')
                        ->format('m'); // grouping by months
                    });

                foreach($queuesByMonths as $month => $queuesOfMonth){

                    $value = $this->calQueuesAvgWaitTime($queuesOfMonth);

                    $dateObj   = Carbon::createFromFormat('m', $month);
                    $label = $dateObj->format('F'); 

                    array_push($labels, $label);
                    array_push($values, round(($value/60), 2));
                }

                $chart = Charts::create('bar', 'highcharts')
                    ->title("Average Wait Time by Months (" . $date . ")")
                    ->elementLabel("Wait Time (minutes)")
                    ->dimensions(0, 400)
                    ->labels($labels)
                    ->values($values);

                return $chart;
                break;
            }
        }
    }

    public function getTicketsFromDate($dateFrom, $dateTo){

        $tickets = null;

        if($dateFrom != null && $dateTo != null){

            $dateFrom = Carbon::createFromFormat('d/m/Y', $dateFrom)->startOfDay();
            $dateTo = Carbon::createFromFormat('d/m/Y', $dateTo)->endOfDay();

            $tickets = Ticket::whereDate('issue_time', '>=', $dateFrom)->whereDate('issue_time', '<=', $dateTo)->get();
        }
        else if($dateFrom != null){
            $dateFrom = Carbon::createFromFormat('d/m/Y', $dateFrom)->startOfDay();

            $tickets = Ticket::whereDate('issue_time', '>=', $dateFrom)->get();
        }
        else if($dateTo != null){
            $dateTo = Carbon::createFromFormat('d/m/Y', $dateTo)->endOfDay();

            $tickets = Ticket::whereDate('issue_time', '<=', $dateTo)->get();
        }
        else{
            $tickets = Ticket::get();
        }
        
        return $tickets;
    }

    public function getQueuesFromDate($dateFrom, $dateTo){

        $queues = null;

        if($dateFrom != null && $dateTo != null){

            $dateFrom = Carbon::createFromFormat('d/m/Y', $dateFrom)->startOfDay();
            $dateTo = Carbon::createFromFormat('d/m/Y', $dateTo)->endOfDay();

            $queues = Queue::where('active', 0)->whereDate('start_time', '>=', $dateFrom)->whereDate('start_time', '<=', $dateTo)->get();
        }
        else if($dateFrom != null){
            $dateFrom = Carbon::createFromFormat('d/m/Y', $dateFrom)->startOfDay();

            $queues = Queue::where('active', 0)->whereDate('start_time', '>=', $dateFrom)->where('active', 0)->get();
        }
        else if($dateTo != null){
            $dateTo = Carbon::createFromFormat('d/m/Y', $dateTo)->endOfDay();

            $queues = Queue::where('active', 0)->whereDate('start_time', '<=', $dateTo)->where('active', 0)->get();
        }
        else{
            $queues = Queue::where('active', 0)->get();
        }
        
        return $queues;
    }


    public function back() {

        return redirect()->route('report.index');
    }

    public function calAvgWaitTime($totalTime, $totalTicket){

        $this->calAvgWaitTimeQueue($totalTime, $totalTicket);
        $this->calAvgWaitTimeWT($totalTime, $totalTicket);
    }

    public function calCurrentTotalWaitTime($avgWaitTime, $totalTicket){

        $this->calCurrentTotalWaitTimeQueue($avgWaitTime, $totalTicket);
        $this->calCurrentTotalWaitTimeWT($avgWaitTime, $totalTicket);
    }

    public function getCurrentAvgWaitTime($queue){

        $this->getCurrentAvgWaitTimeQueue($queue);
        $this->getCurrentAvgWaitTimeWT($queue);
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
