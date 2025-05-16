<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

abstract class Controller {
    /**
     * Create a new controller instance.
     */
    public function __construct() {
        // Flash any errors
        if (Session::get('errors')) {
            foreach (Session::get('errors')->all() as $message) {
                flash($message)->error();
            }
        }
    }
}
