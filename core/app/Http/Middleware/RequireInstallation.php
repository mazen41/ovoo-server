<?php

namespace App\Http\Middleware;

use App\Lib\Installer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sends a freshly unpacked copy to the setup wizard.
 */
class RequireInstallation
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('install', 'install/*')) {
            return $next($request);
        }

        if (!Installer::isInstalled()) {
            return redirect()->route('install.requirements');
        }

        return $next($request);
    }
}
