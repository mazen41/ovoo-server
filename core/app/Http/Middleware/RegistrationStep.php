<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RegistrationStep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user->profile_complete) {
            if ($request->is('api/*')) {
                $notify[] = 'Please complete your profile to go next';
                return apiResponse("profile_incomplete","error",$notify);
            } else {
                return to_route('user.data');
            }
        }
        return $next($request);
    }
}
