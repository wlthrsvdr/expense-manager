<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Controller;

class AuthController extends Controller
{
    protected $data = array();

    public function __construct()
    {
        $this->middleware('system.guest', ['except' => "logout"]);
    }


    public function login(Request $request, $redirect_uri = NULL)
    {
        return view('users.pages.login', $this->data);
    }

    public function authenticate(Request $request, $redirect_uri = NULL)
    {

        $credentials = $request->only('email', 'password');


        if (Auth::guard('client')->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {


            if ($redirect_uri and session()->has($redirect_uri)) {
                return redirect(session()->get($redirect_uri));
            }

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Welcome back!");
            return redirect()->route('user.index');
        }

        session()->flash('notification-status', "failed");
        session()->flash('notification-msg', "Invalid username or password.");
        return redirect()->back();
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        session()->flash('notification-status', "success");
        session()->flash('notification-msg', "You have been logged out from the system");
        return redirect()->route('resident.index');
    }
}
