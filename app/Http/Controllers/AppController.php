<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Session;

class AppController extends Controller
{
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

}
