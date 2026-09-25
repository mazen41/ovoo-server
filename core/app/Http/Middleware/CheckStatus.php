<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
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
        if (Auth::check()) {
            $user = auth()->user();

            if ($user->status  && $user->ev  && $user->sv  && $user->tv && $user->is_deleted  == Status::NO) {
                return $next($request);
            } else {
                if ($user->is_deleted  == Status::YES) {
                    auth()->logout();
                }
                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return apiResponse("unverified", "error", $notify, [
                        'user' => $user
                    ]);
                } else {
                    return to_route('user.authorization');
                }
            }
        }
        abort(403);
    }
}
