<?php

namespace App\Http\Controllers;

use App\AddedEvent;
use App\DeletedEvent;
use App\Event;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;

class Home extends Controller {

    public function index() {


        return view('home', array('pageTitle' => 'Daily Timesheets'));
    }




}