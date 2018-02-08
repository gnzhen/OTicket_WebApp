<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () { return redirect('home'); });
	Route::get('/home', 'HomeController@index')->name('home');

	Route::resources([
	    'config' => 'ConfigController',
	    'user' => 'UserController',
	    'call' => 'CallController',
	    'report' => 'ReportController',
	    'display' => 'DisplayController',
	    'printer' => 'PrinterController',
	    'branch' => 'BranchController',
	    'service' => 'ServiceController',
	    'counter' => 'CounterController',
	]);
	
	Route::get('getSidebarSession', 'AppController@getSidebarSession');
	Route::get('setSidebarSession', 'AppController@setSidebarSession');
});


Auth::routes();


/* AppController */
Route::get('authCheck', 'AppController@authCheck');
Route::get('checkTimeout', 'AppController@checkTimeout');
Route::get('getAllSession', 'AppController@getAllSession');