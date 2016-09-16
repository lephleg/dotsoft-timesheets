<?php

namespace App\Http\Controllers;

use App\User;
use View;

class Users extends Controller
{

    public function index() {
        $users = User::where('department', 'DOTSOFT')
                    ->orderBy('last_name', 'ASC')
                    ->get();

        return View::make('users.index', array('pageTitle' => 'Users', 'users' => $users));
    }

    public function show($id) {
        $user = User::find($id);
        return View::make('users.profile', array('pageTitle' => 'User Profile', 'user' => $user));
    }


    public function getPaxtonUserId($id) {
        $paxtonUser = User::find($id);
        $paxtonUserId = $paxtonUser->pxt_user_id;
        return response()->json($paxtonUserId);
    }
}