<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsParentUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if(isParentUser()) {
            return $next($request);
        }

        if(isApiRequest()) {
            $notify = "The resource not found.";
            return responseManager("not_found", $notify, "error");
        }
        return abort(403);
    }
}
