<?php

namespace App\Http\Middleware;

use Closure;
use Settings;

class CheckAdmin
{
    /**
     * Redirect non-admins to the home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Settings::get('visitors_can_read') && !$request->user()) {
            flash('You must be logged in to view this page!')->error();
            return redirect('/');
        }

        return $next($request);
    }
}
