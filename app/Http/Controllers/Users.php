<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class Users extends Controller
{

    public function index() {
        $users = User::where('department', 'DOTSOFT')
            ->orderBy('last_name', 'ASC')
            ->get();

        return view('users.index', array('pageTitle' => 'Users', 'users' => $users));
    }

    public function show($id) {
        $user = User::find($id);
        return view('users.profile', array('pageTitle' => 'User Profile', 'user' => $user));
    }


    public function daily($id)
    {
        $user = User::where('pxt_user_id', $id)->first();

        return response()->json($user->getUserDailySets('2016-09-16'));

    }

    public function asResources(Request $request)
    {
        $startParam = $request->query('start');

        $department = 'dotsoft';
        if ($department == 'all') {
            $users = User::orderBy('last_name', 'ASC')
                ->get();
        } else {
            $users = User::where('department', strtoupper($department))
                ->orderBy('last_name', 'ASC')
                ->get();
        }

        $colorCodes = array_values(getRainbowColorScheme());
        $resources =[];
        foreach ($users as $index => $user) {
            $resource = array(
                'id' => $user->pxt_user_id,
                'user' => $user->last_name . ' ' . $user->first_name,
                'total' =>
                    $user->getUserDailySets($startParam)['total']->h . " hours & " .
                    $user->getUserDailySets($startParam)['total']->i . " mins",
                'eventColor' => $colorCodes[$index % 7]
            );
            array_push($resources, $resource);
        }

        return response()->json($resources);
    }

}