<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $curr_guard = "user";
        $redirect = "login";
        if (\Route::is('admin.*')) {
            $redirect = "admin.login";
            $curr_guard = "admin";
        }
        if (auth($curr_guard)->check() && !@auth($curr_guard)->user()->is_active) {
            Auth::guard($curr_guard)->logout();
            return redirect(route($redirect))->withErrors([
                'active' => 'You account has been deactivated by administrator. Please contact administrator for further details.'
            ]);
        }
        return $next($request);
    }
}
