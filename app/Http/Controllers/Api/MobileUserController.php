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

		return 'test';
	}

    public function registerMobileUser(Request $request) {

    	$this->validate($request, [
            'username' => 'required|unique:mobile_users',
    		'email' => 'required|email|unique:mobile_users',
    		'password' => 'required|min:6|confirmed'
        ]);

        $mobileUser = new MobileUser;

        $mobileUser->username = $request->username;
        $mobileUser->email = $request->email;
        $mobileUser->password = bcrypt('password');
        
        // $mobileUser->save();

        $params = [
    		'grant_type' => 'password',
    		'client_id' => $this->client->id,
    		'client_secret' => $this->client->secret,
    		'username' => request('username'),
    		'email' => request('email'),
    		'password' => request('password'),
    		'scope' => '*'
    	];

    	$request->request->add($params);

    	$proxy = Request::create('oauth/token', 'POST');

    	return Route::dispatch($proxy);
    	// return $proxy;
    	
    }
}
