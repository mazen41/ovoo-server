<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = getParentUser();

        if ($user->plan_id == Status::NO) {
            if($request->expectsJson()){
                return apiResponse("subscription_expired", "error", ["You cannot proceed without an active plan. Please purchase a plan to continue"]);
            }
            $notify = "You cannot proceed without an active plan. Please purchase a plan to continue";
            return responseManager('subscription_required', $notify);
        }

        if (!userSubscriptionExpiredCheck($user)) {
            $notify = "Your plan has expired. Please renew or purchase a new plan to regain access.";
            if($request->ajax()){
                return apiResponse("subscription_expired", "error", [$notify]);
            }
            return responseManager('subscription_expired', $notify);
        }

        return $next($request);
    }
}
