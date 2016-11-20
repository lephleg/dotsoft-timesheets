<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Route::get('/', 'Home@index')->middleware('auth');
Route::get('/', 'Home@index')->middleware('auth');
Route::get('month', 'Home@month')->middleware('auth');

Route::get('login', array('uses' => 'Auth\LoginController@showLogin'));
Route::post('login', array('uses' => 'Auth\LoginController@login'));
Route::post('logout', array('uses' => 'Auth\LoginController@logout'));



Route::get('employees/daily-resources', 'Employees@asDailyResources');
Route::get('employees/monthly-resources', 'Employees@asMonthlyResources');
Route::get('employees/daily/{id}', 'Employees@daily');
Route::get('employees/month-average/{id}', 'Employees@monthAverage');

Route::resource('employees', 'Employees');


Route::get('events/daily', 'Events@dailyEventSets');
Route::resource('events', 'Events');

Route::get('store-daily', 'DailyLookups@store');
Route::get('store-month', 'DailyLookups@storeMonth');
Route::get('store-year', 'DailyLookups@storeYear');
Route::get('store-everything', 'DailyLookups@storeEverything');