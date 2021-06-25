<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        //check here if the user is authenticated
        if (!$this->auth->user()) {
            if ($request->is('moderate*')) {
                return redirect()->route('loginModerate');
            } elseif ($request->is('admin-panel*')) {
                return redirect()->route('login');
            }
        }
        return $next($request);
    }
}
