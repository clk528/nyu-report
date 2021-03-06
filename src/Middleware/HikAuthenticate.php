<?php

namespace clk528\NyuReport\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class HikAuthenticate extends Authenticate
{

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('clk.sso');
        }
    }
}
