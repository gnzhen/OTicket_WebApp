<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use App\Calling;
use Carbon\Carbon;
use App\Events\DisplayEvent;

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

    public function branchCounterCalling($branchCounter, $queue){

        $branchCounter->serving_queue = $queue->id;
        $branchCounter->save(); 

        return $branchCounter;
    }

    public function branchCounterStopCalling($branchCounter){

        $branchCounter->serving_queue = null;
        $branchCounter->save(); 

        return $branchCounter;
    }

    public function displayCalling($messages){

        event(new DisplayEvent($messages));

        return $messages;
    }

    public function generateDisplayMessage($callings){

        $callMessages = [];
        $count = 0;
        $callingNo = 0;

        while($count < 4 && $callingNo < count($callings)){
            if($count == 0){
                $callMessages['ticket1'] = $callings[0]->ticket->ticket_no;
                $callMessages['counter1'] = $callings[0]->branchCounter->counter->name;

                $count++;
                $callingNo++;
            }
            else if($count == 1){

                do {
                    if(strcmp($callings[$callingNo]->ticket->ticket_no, $callMessages['ticket1']) != 0){

                        $callMessages['ticket2'] = $callings[$callingNo]->ticket->ticket_no;
                        $callMessages['counter2'] = $callings[$callingNo]->branchCounter->counter->name;

                        $count++;
                    }
                    else{

                        $callingNo++;
                    }

                 }while($callingNo < count($callings)
                    && $count == 1);
            }
            else if($count == 2){

                do {

                    if(strcmp($callings[$callingNo]->ticket->ticket_no, $callMessages['ticket1']) != 0 
                        && strcmp($callings[$callingNo]->ticket->ticket_no, $callMessages['ticket2']) != 0){

                        $callMessages['ticket3'] = $callings[$callingNo]->ticket->ticket_no;
                        $callMessages['counter3'] = $callings[$callingNo]->branchCounter->counter->name;

                        $count++;
                    }else{

                        $callingNo++;
                    }

                } while($callingNo < count($callings)
                    && $count == 2);
            }
            else if($count == 3){

                do {

                    if(strcmp($callings[$callingNo]->ticket->ticket_no, $callMessages['ticket1']) != 0 
                        && strcmp($callings[$callingNo]->ticket->ticket_no, $callMessages['ticket2']) != 0 
                        && strcmp($callings[$callingNo]->ticket->ticket_no, $callMessages['ticket3']) != 0){

                        $callMessages['ticket4'] = $callings[$callingNo]->ticket->ticket_no;
                        $callMessages['counter4'] = $callings[$callingNo]->branchCounter->counter->name;

                        $count++;
                    }else{

                        $callingNo++;
                    }

                } while($callingNo < count($callings)
                    && $count == 3);
            }
        }
        
        return $callMessages;
    }
}