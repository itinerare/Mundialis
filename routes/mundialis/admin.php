<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require admin permissions to view.
|
*/

Route::get('/', 'AdminController@getIndex');

# TEXT PAGES
Route::group(['prefix' => 'pages'], function() {
    Route::get('/', 'PageController@getIndex');
    Route::get('edit/{id}', 'PageController@getEditPage');
    Route::post('edit/{id?}', 'PageController@postEditPage');
});

# SITE SETTINGS
Route::get('site-settings', 'AdminController@getSettings');
Route::post('site-settings/{key}', 'AdminController@postEditSetting');

# SITE IMAGES
Route::group(['prefix' => 'site-images'], function() {
    Route::get('/', 'AdminController@getSiteImages');
    Route::post('upload', 'AdminController@postUploadImage');
    Route::post('upload/css', 'AdminController@postUploadCss');
});
