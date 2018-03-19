<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppController;
use Carbon\Carbon;
use App\Ticket;
use App\BranchService;
use App\Traits\WaitTimeManager;
use Session;

class HomeController extends Controller
{
    use WaitTimeManager;

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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appController = new AppController;
        $totalTickets = Ticket::count();
        $totalTicketsYtd = $this->getTicketsYesterday()->count();
        $avgWaitTimeString = $appController->secToString($this->calGlobalAvgWaitTime());
        $branchServices = BranchService::with('service')->with('branch')->get();

        return view('home')->withTotalTickets($totalTickets)->withTotalTicketsYtd($totalTicketsYtd)->withAvgWaitTimeString($avgWaitTimeString)->withBranchServices($branchServices)->withAppController($appController);
    }

    public function getTicketsYesterday(){

        $ticketes = null;

        $start = Carbon::yesterday()->startOfDay();
        $end = Carbon::yesterday()->endOfDay();

        $tickets = Ticket::whereDate('issue_time', '>=', $start)->whereDate('issue_time', '<=', $end)->get();

        return $tickets;
    }

    public function calGlobalAvgWaitTime(){

        $branchServices = BranchService::get();
        $totalBranchService = 0;
        $totalAvgWaitTime = 0;
        $globalAvgWaitTime = 0;

        foreach($branchServices as $branchService){
            if($branchService->system_wait_time != null || $branchService->system_wait_time > 0){
                $totalAvgWaitTime += $branchService->system_wait_time;
                $totalBranchService++;
            }
            // else
            //     $totalAvgWaitTime += $branchService->default_wait_time;
        }

        $globalAvgWaitTime = $this->calAvgWaitTime($totalAvgWaitTime, $totalBranchService);

        return $globalAvgWaitTime;
    }
}
