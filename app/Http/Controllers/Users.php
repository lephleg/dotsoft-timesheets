<?php

namespace App\Http\Controllers;

use App\User;
use View;

class Users extends Controller
{

    public function index() {
        $users = User::all();

        return View::make('users', array('pageTitle' => 'Users', 'users' => $users));
    }

    public function view($id) {
        $paxtonUser = User::find($id);
        return response()->json($paxtonUser);
    }


    public function getPaxtonUserId($id) {
        $paxtonUser = User::find($id);
        $paxtonUserId = $paxtonUser->pxt_user_id;
        return response()->json($paxtonUserId);
    }
}