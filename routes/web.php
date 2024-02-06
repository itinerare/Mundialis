<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\AccountController;
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

Route::controller(Controller::class)->group(function () {
    Route::get('/', 'getIndex')->name('home');
    Route::prefix('info')->group(function () {
        Route::get('privacy', 'getPrivacyPolicy');
        Route::get('terms', 'getTermsOfService');
    });
});

Route::middleware(['auth'])->controller(AccountController::class)->group(function () {
    Route::get('banned', 'getBanned');
});

/***************************************************
    Routes that require read permissions
****************************************************/
Route::middleware(['read'])->group(function () {
    Route::group(__DIR__.'/mundialis/read.php');

    /* Routes that require login */
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::group(__DIR__.'/mundialis/members.php');

        /* Routes that require write permissions */
        Route::group(['middleware' => ['write']], function () {
            Route::group(__DIR__.'/mundialis/write.php');

            /* Routes that require admin permissions */
            Route::prefix('admin')->middleware(['admin'])->group(__DIR__.'/mundialis/admin.php');
        });
    });
});
