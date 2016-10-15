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
Route::get('/', 'Home@index');

Route::get('login', function () {
    return view('login');
});


Route::get('employees/resources', 'Employees@asResources');
Route::get('employees/daily/{id}', 'Employees@daily');

Route::resource('employees', 'Employees');


Route::get('events/daily', 'Events@dailyEventSets');
Route::resource('events', 'Events');