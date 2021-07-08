<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require admin permissions to view. These largely
| encompass editing core site settings and page templates.
|
*/

Route::get('/', 'AdminController@getIndex');

/*
    SUBJECTS
*/

Route::group(['prefix' => 'data', 'namespace' => 'Data'], function() {
    # GENERIC ROUTES
    Route::get('{subject}', 'SubjectController@getSubjectIndex');
    Route::get('{subject}/edit', 'SubjectController@getEditTemplate');
    Route::post('{subject}/edit', 'SubjectController@postEditTemplate');
    Route::get('{subject}/create', 'SubjectController@getCreateCategory');
    Route::post('{subject}/create', 'SubjectController@postCreateEditCategory');

    Route::get('categories/edit/{id}', 'SubjectController@getEditCategory');
    Route::get('categories/delete/{id}', 'SubjectController@getDeleteCategory');
    Route::post('categories/edit/{id?}', 'SubjectController@postCreateEditCategory');
    Route::post('categories/delete/{id}', 'SubjectController@postDeleteCategory');
    Route::post('{subject}/sort', 'SubjectController@postSortCategory');
});

/*
    USERS
*/

Route::group(['prefix' => 'users'], function() {
    # RANKS
    Route::group(['prefix' => 'ranks'], function() {
        Route::get('/', 'RankController@getIndex');
        Route::get('edit/{id}', 'RankController@getEditRank');
        Route::post('edit/{id?}', 'RankController@postEditRank');
    });
});

/*
    MAINTENANCE
*/

# RECENT CHANGES

/*
    SITE SETTINGS
*/

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
