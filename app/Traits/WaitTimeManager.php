<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\BranchService;
use App\Queue;
use Carbon\Carbon;

trait WaitTimeManager {

    public function calAvgWaitTime($totalTime, $totalTicket){

        if($totalTicket == 0)
            return 0;

        return round($totalTime / $totalTicket);
    }

    public function calTotalWaitTime($avgWaitTime, $totalTicket){

        return $avgWaitTime * $totalTicket;
    }

    public function getAvgWaitTime($queue){
        $branchService = $queue->branchService;
        $avgWaitTime = $branchService->system_wait_time;

        if($avgWaitTime < 1){
            $avgWaitTime = $branchService->default_wait_time;
        }

        return $avgWaitTime;
    }
}