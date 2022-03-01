<?php

namespace App\Http\Middleware;

use App\Client;
use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
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
        if (Auth::user()->is_admin != 2) {
            return redirect()->route('app.home');
        }
        return $next($request);
    }
}
