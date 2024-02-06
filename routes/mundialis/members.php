<?php

use App\Http\Controllers\Users\AccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require an account to access.
|
*/

Route::controller(AccountController::class)->group(function () {
    Route::prefix('account')->group(function () {
        Route::get('settings', 'getSettings');
        Route::post('profile', 'postProfile');
        Route::post('password', 'postPassword');
        Route::post('email', 'postEmail');
        Route::post('avatar', 'postAvatar');

        Route::get('two-factor/confirm', 'getConfirmTwoFactor');
        Route::post('two-factor/enable', 'postEnableTwoFactor');
        Route::post('two-factor/confirm', 'postConfirmTwoFactor');
        Route::post('two-factor/disable', 'postDisableTwoFactor');

        Route::get('watched-pages', 'getWatchedPages');
        Route::post('watched-pages/{id}', 'postWatchPage');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', 'getNotifications');
        Route::get('delete/{id}', 'getDeleteNotification');
        Route::post('clear', 'postClearNotifications');
        Route::post('clear/{type}', 'postClearNotifications');
    });
});
