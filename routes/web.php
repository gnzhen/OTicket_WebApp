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

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resources([
    'config' => 'ConfigController',
    'manage' => 'ManageController',
    'report' => 'ReportController',
    'counter' => 'CounterController',
    'display' => 'DisplayController',
    'printer' => 'PrinterController',
]);

/* AppController */
Route::get('getSidebarSession', 'AppController@getSidebarSession');
Route::get('setSidebarSession', 'AppController@setSidebarSession');
Route::get('authCheck', 'AppController@authCheck');
Route::get('checkTimeout', 'AppController@checkTimeout');