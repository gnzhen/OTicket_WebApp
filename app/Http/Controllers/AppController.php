<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Session;
use JavaScript;

class AppController extends Controller
{
	public function __construct()
    {
    	// $user = Auth::user();

    	// JavaScript::put([
	    //     'branchId' => $user->branch_id
	    // ]);
    }

    public function setBranchId(){
    	$user = Auth::user();

    	JavaScript::put([
	        'branchId' => $user->branch_id
	    ]);
    }

	public function checkAuth(){
		
		return (string)Auth::check();
	}

    public function getSidebarSession() {

		// return Session()->get('sidebar');
		return session('sidebar');
	}

	public function setSidebarSession(Request $request) {

		session(['sidebar' => $request->sidebar]);

		// Session()->put('sidebar', $request->sidebar);

		return $this->getSidebarSession();
		// return $request->session()->all();
	}

	public function getTabSession() {

		return session('tab');
	}

	public function testFunction(){

		 return "test";
	}

	public function setTabSession(Request $request) {

		session(['tab' => $request->tab]);

		return $this->getTabSession();
	}

	public function getAllSession(Request $request){

		return $request->session()->all();
	}

	/** 
	 * Convert wait time in seconds
	 * to a proper string for display purpose.
	 */

	public static function secToString($seconds) {
		$hours = floor($seconds / 3600);
  		$minutes = floor(($seconds / 60) % 60);
  		$seconds = $seconds % 60;
  
  		return $hours > 0 ? "$hours hr $minutes min" : ($minutes > 0 ? "$minutes min $seconds sec" : "$seconds sec");
	}

	public static function secToTime($seconds) {
		$hours = floor($seconds / 3600);
  		$minutes = floor(($seconds / 60) % 60);
  		$seconds = $seconds % 60;
  
  		return ("$hours,$minutes,$seconds");
	}

	public static function timeToSec($hr, $min, $sec){
		return $sec + ($min * 60) + ($hr * 3600);
	}

	public static function timeToArray($branchServices){
		$wait_time_array = array();

        foreach ($branchServices as $branchService){
            $default_wait_time_string = self::secToTime($branchService->default_wait_time);
            $default_wait_time_exploded = explode(",", $default_wait_time_string);

            $system_wait_time_string = self::secToTime($branchService->system_wait_time);
            $system_wait_time_exploded = explode(",", $system_wait_time_string);
            
            $wait_time_array[$branchService->id]['default_wait_time_hr'] = $default_wait_time_exploded[0];
            $wait_time_array[$branchService->id]['default_wait_time_min'] = $default_wait_time_exploded[1];
            $wait_time_array[$branchService->id]['default_wait_time_sec'] = $default_wait_time_exploded[2];
            $wait_time_array[$branchService->id]['system_wait_time_hr'] = $system_wait_time_exploded[0];
            $wait_time_array[$branchService->id]['system_wait_time_min'] = $system_wait_time_exploded[1];
            $wait_time_array[$branchService->id]['system_wait_time_sec'] = $system_wait_time_exploded[2];
        }

        return $wait_time_array;
	}

}
