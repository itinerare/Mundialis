<?php

namespace App\Http\Middleware;

use Closure;

class CheckWrite {
    /**
     * Redirect users without write permissions to the home page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!$request->user()->canWrite) {
            flash('You do not have the permission to access this page.')->error();

            return redirect('/');
        }

        return $next($request);
    }
}
