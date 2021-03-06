<?php

use App\Events\DisplayEvent;

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
	    'report' => 'ReportController',
	    'display' => 'DisplayController',
	    'printer' => 'PrinterController',
	    'branch' => 'BranchController',
	    'service' => 'ServiceController',
	    'counter' => 'CounterController',
	    'branchCounter' => 'BranchCounterController',
	    'branchService' => 'BranchServiceController',
	    'ticket' => 'TicketController',
	    'queue' => 'QueueController',
	    'call' => 'CallController',
	]);

	Route::post('/branch/{id}/counter', 'BranchController@updateCounter')->name('branch.updateCounter');
	Route::post('/branch/{id}/service/store', 'BranchController@addService')->name('branch.addService');
	Route::post('/branch/service/{id}/update', 'BranchController@updateService')->name('branch.updateService');
	Route::post('/branch/service/{id}/destroy', 'BranchController@deleteService')->name('branch.deleteService');
	Route::post('/call/open', 'CallController@openCounter')->name('call.openCounter');
	Route::post('/call/close/{id}', 'CallController@closeCounter')->name('call.closeCounter');
	Route::post('/call/call', 'CallController@call')->name('call.call');
	Route::post('/call/recall', 'CallController@recall')->name('call.recall');
	Route::post('/call/skip', 'CallController@skip')->name('call.skip');
	Route::post('/call/done', 'CallController@done')->name('call.done');
	Route::post('/report/download', 'ReportController@download')->name('report.download');
	Route::post('/report', 'ReportController@back')->name('report.back');
	Route::post('/report/result', 'ReportController@result')->name('report.result');
	Route::post('/printer/qrcode', 'PrinterController@showQRCode')->name('printer.qrcode');
	
	Route::get('getSidebarSession', 'AppController@getSidebarSession');
	Route::get('getTabSession', 'AppController@getTabSession');
	Route::get('setSidebarSession', 'AppController@setSidebarSession');
	Route::get('setTabSession', 'AppController@setTabSession');
	Route::get('testFunction', 'AppController@testFunction');
	Route::get('ticketsJSON', 'AppController@getTicketsToCallJSON');
	Route::post('generateSysWaitTime', 'AppController@generateSysWaitTime')->name('app.generateSysWaitTime');
});

Route::resources([
	'mail' => 'MailController',
]);

Auth::routes();

/* AppController */
Route::get('checkAuth', 'AppController@checkAuth');
Route::get('getAllSession', 'AppController@getAllSession');
Route::get('test', 'AppController@test');
