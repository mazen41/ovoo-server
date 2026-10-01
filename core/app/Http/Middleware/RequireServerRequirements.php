<?php

namespace App\Http\Middleware;

use App\Lib\Installer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps every step after the first behind the server requirements check.
 *
 * The requirements page disables its Continue button, but a disabled button is not a gate: without
 * this, typing /install/purchase walks straight past a failing check, and the final POST would go
 * on to build a database on a server that cannot run the application. The checks are re-read on
 * each request rather than cached in the session, so fixing a missing extension mid-install takes
 * effect immediately.
 */
class RequireServerRequirements
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Installer::checkRequirements()['passed']) {
            return redirect()->route('install.requirements');
        }

        return $next($request);
    }
}
