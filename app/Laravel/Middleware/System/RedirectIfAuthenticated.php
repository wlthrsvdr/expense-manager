<?php

namespace App\Laravel\Middleware\System;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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

        if (Auth::guard($guard)->check()) {
            if (Auth::guard("admin")->check()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.index');
            }
            // return redirect()->route('admin.dashboard');
        }


        return $next($request);
    }
}
