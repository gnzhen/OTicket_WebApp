<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



/* Mobile User */
Route::post('test', 'Api\MobileUserController@test')->name('mobile.test');
Route::post('register', 'Api\MobileUserController@register')->name('mobile.register');
Route::post('login', 'Api\MobileUserController@login')->name('mobile.login');
Route::post('logout', 'Api\MobileUserController@logout')->name('mobile.logout');
Route::get('branches', 'Api\MobileUserController@getBranches')->name('mobile.branches');
Route::get('services', 'Api\MobileUserController@getServices')->name('mobile.services');
Route::get('branchServices', 'Api\MobileUserController@getBranchServices');
Route::post('branchServicesByBranchId', 'Api\MobileUserController@getBranchServicesByBranchId');
Route::post('branchServicesDetailsByBranchId', 'Api\MobileUserController@getBranchServicesDetailsByBranchId');
Route::post('queuesByBranchId', 'Api\MobileUserController@getQueuesByBranchId');
Route::post('issueTicket', 'Api\MobileUserController@issueTicket');
Route::post('postponeDetails', 'Api\MobileUserController@getPostponeDetails');
Route::post('postponeTicket', 'Api\MobileUserController@postponeUserTicket');
Route::post('cancelTicket', 'Api\MobileUserController@cancelUserTicket');
Route::post('userCurrentTickets', 'Api\MobileUserController@getUserCurrentTicketsAndDetails');
Route::post('ticketDetails', 'Api\MobileUserController@getTicketDetails');
Route::post('histories', 'Api\MobileUserController@getUserHistories');


Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});