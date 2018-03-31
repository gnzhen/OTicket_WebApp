<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\MobileUser;
use App\Branch;
use App\Service;
use App\Queue;
use App\Ticket;
use App\Serving;
use App\Calling;
use App\BranchService;
use App\BranchCounter;
use App\FCMToken;
use App\Http\Controllers\Controller;
use PushNotification;
use App\Traits\FCMManager;

class FCMController extends Controller
{
	use FCMManager;

    public function test(){
        // $this->test();
        echo "test";
    }

    public function notifyCall($mobileUserId, $calling){

        return $this->notifyCall($mobileUserId, $calling);
    }

    public function notifyRecall($mobileUserId, $calling){

        return $this->notifyRecall($mobileUserId, $calling);
    }

    public function notifySkip($mobileUserId, $ticket){

		return $this->notifySkip($mobileUserId, $ticket);
    }

    public function notifyNext($mobileUserId, $ticket) {

        return $this->notifyNext($mobileUserId, $ticket);
    }

    public function notifyNear($mobileUserId, $ticket){

        return $this->notifyNear($mobileUserId, $ticket);
    }

    public function notifyChange($mobileUserId, $ticket, $change){

        return $this->notifyChange($mobileUserId, $ticket, $change);
    }
}
