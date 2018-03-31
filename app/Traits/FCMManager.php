<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppController;
use App\BranchCounter;
use App\Calling;
use App\FCMToken;
use Carbon\Carbon;
use App\Events\DisplayEvent;
use PushNotification;

trait FCMManager {

    public function notifyCall($mobileUserId, $calling){

        $appController = new AppController();

        $deviceToken = FCMToken::where('user_id', $mobileUserId)->first();

        if($deviceToken == null){
            return null;
        }
        else {
            $call['ticket_id'] = $calling->ticket_id;
            $call['ticket_no'] = $calling->ticket->ticket_no;
            $call['ticket_wait_time'] = $appController->secToString($calling->ticket->wait_time);
            $call['branch_name'] = $calling->ticket->queue->branchService->branch->name;
            $call['service_name'] = $calling->ticket->queue->branchService->service->name;
            $call['counter_name'] = $calling->branchCounter->counter->name;

            $data['title'] = "Calling ticket ".$calling->ticket->ticket_no;
            $data['body'] = "Counter: ".$calling->branchCounter->counter->name;
            $data['type'] = "call";
            $data['data'] = $call;

            $noti = PushNotification::Message($data);

            PushNotification::app('oticket')->to($deviceToken->token)->send($noti);

            return $noti;
        }
    }

    public function notifyRecall($mobileUserId, $calling){

        $appController = new AppController();

        $deviceToken = FCMToken::where('user_id', $mobileUserId)->first();

        if($deviceToken == null){
            return false;
        }
        else {
            $recall['ticket_id'] = $calling->ticket_id;
            $recall['ticket_no'] = $calling->ticket->ticket_no;
            $recall['ticket_wait_time'] = $appController->secToString($calling->ticket->wait_time);
            $recall['branch_name'] = $calling->ticket->queue->branchService->branch->name;
            $recall['service_name'] = $calling->ticket->queue->branchService->service->name;
            $recall['counter_name'] = $calling->branchCounter->counter->name;

            $data['title'] = "Recalling ticket ".$calling->ticket->ticket_no;
            $data['body'] = "Counter: ".$calling->branchCounter->counter->name;
            $data['type'] = "recall";
            $data['data'] = $recall;

            $noti = PushNotification::Message($data);

            PushNotification::app('oticket')->to($deviceToken->token)->send($noti);

            return true;
        }
    }

    public function notifySkip($mobileUserId, $ticket){

        $appController = new AppController();

        $deviceToken = FCMToken::where('user_id', $mobileUserId)->first();

        if($deviceToken == null){
            return false;
        }
        else {
            $skip['ticket_id'] = $ticket->id;
            $skip['ticket_no'] = $ticket->ticket_no;
            $skip['ticket_wait_time'] = $appController->secToString($ticket->wait_time);
            $skip['branch_name'] = $ticket->queue->branchService->branch->name;
            $skip['service_name'] = $ticket->queue->branchService->service->name;

            $data['title'] = "Opps! you've been skipped!";
            $data['body'] = "Ticket: ".$ticket->ticket_no;
            $data['type'] = "skip";
            $data['data'] = $skip;

            $noti = PushNotification::Message($data);

            PushNotification::app('oticket')->to($deviceToken->token)->send($noti);

            return true;
        }
    }

    public function notifyNext($mobileUserId, $ticket) {

        $appController = new AppController();

        $deviceToken = FCMToken::where('user_id', $mobileUserId)->first();

        if($deviceToken == null){
            return false;
        }
        else {
            $next['ticket_id'] = $ticket->id;
            $next['ticket_no'] = $ticket->ticket_no;
            $next['ticket_wait_time'] = $appController->secToString($ticket->wait_time);
            $next['branch_name'] = $ticket->queue->branchService->branch->name;
            $next['service_name'] = $ticket->queue->branchService->service->name;

            $data['title'] = "You're next after this!";
            $data['body'] = "Ticket: ".$ticket->ticket_no;
            $data['type'] = "next";
            $data['data'] = $next;

            $noti = PushNotification::Message($data);

            PushNotification::app('oticket')->to($deviceToken->token)->send($noti);

            return true;
        }
    }

    public function notifyNear($mobileUserId, $ticket){

        $deviceToken = FCMToken::where('user_id', $mobileUserId)->first();
        $appController = new AppController();

        if($deviceToken == null){
            return false;
        }
        else {
            $near['ticket_id'] = $ticket->id;
            $near['ticket_no'] = $ticket->ticket_no;
            $near['ticket_wait_time'] = $appController->secToString($ticket->wait_time);
            $near['branch_name'] = $ticket->queue->branchService->branch->name;
            $near['service_name'] = $ticket->queue->branchService->service->name;

            $data['title'] = "Your turn in ".$appController->secToString($ticket->wait_time);
            $data['body'] = "Ticket: ".$ticket->ticket_no;
            $data['type'] = "near";
            $data['data'] = $near;

            $noti = PushNotification::Message($data);

            PushNotification::app('oticket')->to($deviceToken->token)->send($noti);

            return true;
        }
    }

    public function notifyChange($mobileUserId, $ticket, $change){

        $appController = new AppController();
        $deviceToken = FCMToken::where('user_id', $mobileUserId)->first();

        if($deviceToken == null){
            return false;
        }
        else {

            $title = "";

            if($change->change == "delay"){
                $title = $appController->secToString($change->time)." delay.";
            }
            else{
                $title = $appController->secToString($change->time)." earlier.";
            }

            $chg['ticket_id'] = $change->ticket_id;
            $chg['ticket_no'] = $ticket->ticket_no;
            $chg['ticket_wait_time'] = $appController->secToString($ticket->wait_time);
            $chg['branch_name'] = $ticket->queue->branchService->branch->name;
            $chg['service_name'] = $ticket->queue->branchService->service->name;
            $chg['change_type'] = $change->change;
            $chg['change_time'] = $appController->secToString($change->time);

            $data['title'] = $title;
            $data['body'] = "Ticket: ".$ticket->ticket_no;
            $data['type'] = "change";
            $data['data'] = $chg;

            $noti = PushNotification::Message($data);

            PushNotification::app('oticket')->to($deviceToken->token)->send($noti);

            return true;
        }
    }
}