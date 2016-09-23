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

Route::get('/', function () {
    return view('home', array('pageTitle' => 'Daily Timesheets'));
});

Route::get('users/resources/{date?}', 'Users@asResources');
Route::get('users/daily/{id}', 'Users@daily');
Route::resource('users', 'Users');


Route::get('events/daily', 'Events@dailyEventSets');
Route::resource('events', 'Events');