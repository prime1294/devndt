<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class SentinelAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Sentinel::check()) {
            return redirect()->route('user.login');
        }

        if(Sentinel::check()) {
            $user = Sentinel::getUser();
            if($user->status == 0) {
                Sentinel::logout();
                return redirect()->route('user.login');
            }
            //check is trial expired
            if(today()->greaterThan(Carbon::parse($user->trial_end)))
            {
                Sentinel::logout();
                return redirect()->route('user.login')->with('alert-error', "Your plan is expired. Please contact to support");
            }
        }
        return $next($request);
    }
}
