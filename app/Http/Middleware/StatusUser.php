<?php

namespace App\Http\Middleware;

use App\Client;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class StatusUser
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
        if (Auth::user()->status == 0) {
            return redirect()->route('app.home');
        }
        $user = User::find(Auth::user()->user_id);
        if (Auth::user()->is_admin == 0 && $user->status == 0) {
            return redirect()->route('app.home');
        }
        
        return $next($request);
    }
}
