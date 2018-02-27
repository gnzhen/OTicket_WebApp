<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Calling;
use Carbon\Carbon;

trait CallingManager {

    public function storeCalling(Request $request){
        $calling = new Calling();

        $calling->ticket_id = $request->ticket_id;
        $calling->branch_counter_id = $request->branch_counter_id;
        $calling->call_time = $request->call_time;
        $calling->active = $request->active;

        $calling->save();

        return $calling;
    }

    public function stopCalling($calling){

        $calling->active = 0;

        $calling->save();

        return $calling;
    }

}