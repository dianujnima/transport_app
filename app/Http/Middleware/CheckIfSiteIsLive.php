<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfSiteIsLive
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
        if (config('app.site_live') == false) {
            return redirect(route('front.pages.thankyou'))->withErrors(['not_live' => 'Thanks for signup up with us!']);
        }
        return $next($request);
    }
}
