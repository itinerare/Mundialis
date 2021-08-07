<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Controller@getIndex')->name('home');
Route::get('/terms', 'Controller@getTermsOfService');
Route::get('/privacy', 'Controller@getPrivacyPolicy');

Route::group(['middleware' => ['auth']], function() {
    # BANNED
    Route::get('banned', 'Users\AccountController@getBanned');
});

/***************************************************
    Routes that require read permissions
****************************************************/
Route::group(['middleware' => ['read']], function() {

    require_once __DIR__.'/mundialis/read.php';

    /* Routes that require login */
    Route::group(['middleware' => ['auth', 'verified']], function() {

        require_once __DIR__.'/mundialis/members.php';

        /* Routes that require write permissions */
        Route::group(['middleware' => ['write']], function() {

            require_once __DIR__.'/mundialis/write.php';

            /* Routes that require admin permissions */
            Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['admin']], function() {

                require_once __DIR__.'/mundialis/admin.php';
            });

        });

    });

});
