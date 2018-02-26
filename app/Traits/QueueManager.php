<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Queue;
use Carbon\Carbon;

trait QueueManager {

    public function storeQueue($branchServiceId){
    	$queue = new Queue();

        $queue->branch_service_id = $branchServiceId;
        $queue->wait_time = 0;
        $queue->total_ticket = 1;
        $queue->start_time = Carbon::now();
        $queue->active = 1;

        $queue->save();

        return $queue;
    }

}