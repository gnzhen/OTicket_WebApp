<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BranchService;
use App\Queue;
use App\Traits\WaitTimeManager;
use DB;

class CalSysWaitTime extends Command
{
    use WaitTimeManager;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CalSysWaitTime:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate System Wait Time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Calculate average wait time of each branchService
        $branchServices = BranchService::get();
        
        foreach($branchServices as $branchService){

            DB::beginTransaction();

            try {

                $queues = $branchService->inactive_queue;
                $totalWaitTime = $queues->sum('avg_wait_time');
                $noOfQueue = $queues->count();
                $sysWaitTime = $this->calAvgWaitTime($totalWaitTime, $noOfQueue);

                $branchService->system_wait_time = $sysWaitTime;
                $branchService->save();

                DB::commit();

                echo $branchService->id.": ".$sysWaitTime.", ";

            } catch (\Exception $e) {

                DB::rollback();

                throw $e;
            }
        }
    }
}
