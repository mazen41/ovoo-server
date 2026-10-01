<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The flow builder routes are deliberately absent from this list: they are authenticated,
     * state changing POSTs made by the React client, which now sends the CSRF token from the
     * page head. Only genuine server to server callbacks, which cannot carry a session token,
     * belong here.
     */
    protected $except = [
        'webhook',
        'pusher/auth/*',
    ];
}
