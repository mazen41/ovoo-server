<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasMetaWhatsapp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = getParentUser();
        if ($user->currentWhatsapp()) {
            return $next($request);
        }
        
        return responseManager('not_available', "Please add a whatsapp business account first.");
    }
}
