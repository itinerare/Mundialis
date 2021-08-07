<?php

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require an account to view.
|
*/

Route::group(['prefix' => 'account', 'namespace' => 'Users'], function() {
    Route::get('settings', 'AccountController@getSettings');
    Route::post('profile', 'AccountController@postProfile');
    Route::post('password', 'AccountController@postPassword');
    Route::post('email', 'AccountController@postEmail');
    Route::post('avatar', 'AccountController@postAvatar');

    Route::get('two-factor/confirm', 'AccountController@getConfirmTwoFactor');
    Route::post('two-factor/enable', 'AccountController@postEnableTwoFactor');
    Route::post('two-factor/confirm', 'AccountController@postConfirmTwoFactor');
    Route::post('two-factor/disable', 'AccountController@postDisableTwoFactor');

    Route::get('watched-pages', 'AccountController@getWatchedPages');
    Route::post('watched-pages/{id}', 'AccountController@postWatchPage');
});

Route::group(['prefix' => 'notifications', 'namespace' => 'Users'], function() {
    Route::get('/', 'AccountController@getNotifications');
    Route::get('delete/{id}', 'AccountController@getDeleteNotification');
    Route::post('clear', 'AccountController@postClearNotifications');
    Route::post('clear/{type}', 'AccountController@postClearNotifications');
});
