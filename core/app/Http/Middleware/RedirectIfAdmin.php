<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::guard($guard)->user()->can('view dashboard')) {
                return to_route('admin.dashboard');
            }
            return to_route('admin.profile');
        }
        return $next($request);
    }
}
