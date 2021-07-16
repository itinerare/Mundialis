<?php

/*
|--------------------------------------------------------------------------
| Write Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require write permissions to view.
|
*/

/*
    SUBJECTS/PAGES
*/

Route::group(['prefix' => 'pages', 'namespace' => 'Pages'], function() {
    # BASIC CREATE/EDIT ROUTES
    Route::get('/', 'PageController@getPageIndex');
    Route::get('create/{category}', 'PageController@getCreatePage');
    Route::get('edit/{id}', 'PageController@getEditPage');
    Route::get('delete/{id}', 'PageController@getDeletePage');
    Route::post('create', 'PageController@postCreateEditPage');
    Route::post('edit/{id?}', 'PageController@postCreateEditPage');
    Route::post('delete/{id}', 'PageController@postDeletePage');
});

