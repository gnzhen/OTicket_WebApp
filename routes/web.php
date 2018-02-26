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
	    'branchCounter' => 'BranchCounterController',
	    'branchService' => 'BranchServiceController',
	    'calling' => 'CallingController',
	    'ticket' => 'TicketController',
	    'queue' => 'QueueController',
	]);

	Route::post('/branch/{id}/counter', 'BranchController@updateCounter')->name('branch.updateCounter');
	Route::post('/branch/{id}/service/store', 'BranchController@addService')->name('branch.addService');
	Route::post('/branch/service/{id}/update', 'BranchController@updateService')->name('branch.updateService');
	Route::post('/branch/service/{id}/destroy', 'BranchController@deleteService')->name('branch.deleteService');

	
	Route::get('getSidebarSession', 'AppController@getSidebarSession');
	Route::get('getTabSession', 'AppController@getTabSession');
	Route::get('setSidebarSession', 'AppController@setSidebarSession');
	Route::get('setTabSession', 'AppController@setTabSession');
});


Auth::routes();


/* AppController */
Route::get('checkAuth', 'AppController@checkAuth');
Route::get('getAllSession', 'AppController@getAllSession');