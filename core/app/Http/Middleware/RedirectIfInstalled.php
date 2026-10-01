<?php

namespace App\Http\Middleware;

use App\Lib\Installer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Closes the setup wizard once the application is installed.
 *
 */
class RedirectIfInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Installer::isInstalled()) {
            return redirect(url('/'));
        }

        return $next($request);
    }
}
