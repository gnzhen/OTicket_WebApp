<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\MobileUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

class MobileUserController extends Controller
{
	private $client;

    public function __construct(){

		$this->client = Client::find(1);
	}

	public function test(Request $request){

		return response()->json(['test' => 'test']);
	}

    public function registerMobileUser(Request $request) {

    	$validator = Validator::make($request->all(), [
            'name' => 'required',
    		'email' => 'required|email|unique:mobile_users',
    		'phone' => 'required|regex:/[0-9]{9,12}/',
    		'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
			
            return response()->json($validator->messages());

        } else {
        	$mobileUser = new MobileUser;

	        $mobileUser->name = $request->name;
	        $mobileUser->email = $request->email;
	        $mobileUser->phone = $request->phone;
	        $mobileUser->password = bcrypt('password');

        	$mobileUser->save();

    		return response()->json(["success" => "Register Success!"]);
        }
    	
    }
}
