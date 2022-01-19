<?php

namespace App\Http\Middleware;

use Closure;
use Settings;

class CheckRead
{
    /**
     * Redirect visitors to the homepage if site is private.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->is_banned) {
            return redirect('/banned');
        }

        if (!Settings::get('visitors_can_read') && !$request->user()) {
            flash('You must be logged in to view this page!')->error();

            return redirect('/');
        }

        return $next($request);
    }
}
