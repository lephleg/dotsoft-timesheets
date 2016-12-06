<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating employees for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'username';
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login()
    {

        // validate the info, create rules for the inputs
        $rules = array(
            'username'  => 'required',
            'password'  => 'required|min:6' // password can only be alphanumeric and has to be greater than 6 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return view('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'username'  => Input::get('username'),
                'password'  => Input::get('password')
            );

            $rememberMe = Input::get('remember_me') ? Input::get('remember_me') : null;

            // attempt to do the login
            if (Auth::attempt($userdata, $rememberMe)) {

                // validation successful!
                // redirect them to the secure section
                 return Redirect::to('/');

            } else {
                // if Auth::attempt fails (wrong credentials) create a new message bag instance.
                $errors = new MessageBag(['password' => ['Wrong credentials. Access denied.']]);

                // validation not successful, send back to form
                return Redirect::to('login')
                    ->withErrors($errors)
                    ->withInput(Input::except('password'));

            }
        }

    }

    public function logout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }



}
