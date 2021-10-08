<?php

namespace App\Laravel\Middleware\System;

use Closure, Session, Route;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {

            $guardo =  Auth::check();

            $redirect_uri = $request->url();
            $redirect_key = base64_encode($redirect_uri);
            session()->put($redirect_key, $redirect_uri);
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Unauthorized access. Please login first." .  $guardo);

            if (Auth::guard('admin')) {
                if (!Auth::guard('admin')->check()) {
                    return redirect()->route('admin.login', [$redirect_key]);
                } else {
                    return redirect()->route('admin.dashboard');
                }
            } else {
                if (!Auth::guard('client')->check()) {
                    return redirect()->route('user.login', [$redirect_key]);
                } else {
                    return redirect()->route('user.dashboard');
                }
            }
        }




        return $next($request);
    }

    public function get_guard()
    {
        if (Auth::guard('admin')->check()) {
            return "admin";
        } elseif (Auth::guard('client')->check()) {
            return "client";
        }
    }
}
