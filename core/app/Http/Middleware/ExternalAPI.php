<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use App\Models\ExternalApiIpWhiteList;
use App\Models\User;
use App\Models\UserApiCredentials;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExternalAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $clientId     = $request->headers->get('client-id');
        $clientSecret = $request->headers->get('client-secret');

        if (!$clientId) {
            return apiResponse('Unauthorized', 'error', ['The client id is required'], statusCode: 401);
        }

        if (!$clientSecret) {
            return apiResponse('Unauthorized', 'error', ['The client secret is required'], statusCode: 401);
        }

        $credentials = UserApiCredentials::where('client_id', $clientId)->where('client_secret', $clientSecret)->first();

        if (!$credentials) {
            return apiResponse('Unauthorized', 'error', ['Invalid credentials'], statusCode: 401);
        }

        $user = User::active()->find($credentials->user_id);

        if (!$user) {
            return apiResponse('Unauthorized', 'error', ['The credentials provided do not match the ones associated with this user in our records. Please verify the credentials and try again.'], statusCode: 401);
        }

        $ip = @$_SERVER['REMOTE_ADDR'];

        if (!$ip) {
            return apiResponse('Unauthorized', 'error', ["Your ip address is not valid"], statusCode: 401);
        }

        $whiteListIp = ExternalApiIpWhiteList::where('user_id', $user->id)->where('ip', $ip)->exists();

        if (!$whiteListIp) {
            $message[] = "Access to this API endpoint is restricted to IP addresses that have been explicitly whitelisted.";
            $message[] = "In order to access this API endpoint, please add your IP address ($ip) to the white list from the user dashboard.";
            return apiResponse('Unauthorized', 'error', $message, statusCode: 401);
        }

        if ($user->plan_id == Status::NO) {
            $notify[] = "You cannot proceed without an active plan. Please purchase a plan to continue";
            return apiResponse('subscription_required', 'error', $notify);
        }

        if (!userSubscriptionExpiredCheck($user)) {
            $notify[] = "Your plan has expired. Please renew or purchase a new plan to regain access.";
            return apiResponse('subscription_required', 'error', $notify);
        }

        if (!$user->api_available) {
            return apiResponse('not_available', 'error', ['Your current plan does not support API access. Please upgrade your plan.']);
        }

        return $next($request);
    }
}
