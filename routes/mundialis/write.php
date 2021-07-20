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
    Route::get('create/{category}', 'PageController@getCreatePage');
    Route::get('{id}/edit', 'PageController@getEditPage')
        ->whereNumber('id');
    Route::get('{id}/delete', 'PageController@getDeletePage')
        ->whereNumber('id');
    Route::post('create', 'PageController@postCreateEditPage');
    Route::post('{id?}/edit', 'PageController@postCreateEditPage');
    Route::post('{id}/delete', 'PageController@postDeletePage')
        ->whereNumber('id');

    # IMAGE ROUTES
    Route::get('{id}/gallery/create', 'ImageController@getCreateImage')
        ->whereNumber('id');
    Route::get('{page_id}/gallery/edit/{id}', 'ImageController@getEditImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    Route::get('{page_id}/gallery/delete/{id}', 'ImageController@getDeleteImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    Route::post('{id}/gallery/create', 'ImageController@postCreateEditImage')
        ->where('id', '[0-9]+');
    Route::post('{page_id}/gallery/edit/{id?}', 'ImageController@postCreateEditImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    Route::post('{page_id}/gallery/delete/{id}', 'ImageController@postDeleteImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
});

