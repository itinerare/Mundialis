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

Route::get('get/tags', 'Pages\TagController@getAllTags');

Route::group(['prefix' => 'pages', 'namespace' => 'Pages'], function() {
    # BASIC CREATE/EDIT ROUTES
    Route::get('create/{category}', 'PageController@getCreatePage')
        ->whereNumber('category');
    Route::get('{id}/edit', 'PageController@getEditPage')
        ->whereNumber('id');
    Route::get('{id}/delete', 'PageController@getDeletePage')
        ->whereNumber('id');
    Route::get('{page_id}/history/{id}/reset', 'PageController@getResetPage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    Route::post('create', 'PageController@postCreateEditPage');
    Route::post('{id?}/edit', 'PageController@postCreateEditPage');
    Route::post('{id}/delete', 'PageController@postDeletePage')
        ->whereNumber('id');
    Route::post('{page_id}/history/{id}/reset', 'PageController@postResetPage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);

    # IMAGE ROUTES
    Route::get('{id}/gallery/create', 'ImageController@getCreateImage')
        ->whereNumber('id');
    Route::get('{page_id}/gallery/edit/{id}', 'ImageController@getEditImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    Route::get('{page_id}/gallery/delete/{id}', 'ImageController@getDeleteImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    Route::post('{id}/gallery/create', 'ImageController@postCreateEditImage')
        ->whereNumber('id');
    Route::post('{page_id}/gallery/edit/{id?}', 'ImageController@postCreateEditImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    Route::post('{page_id}/gallery/delete/{id}', 'ImageController@postDeleteImage')
        ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);

    # PROTECTION ROUTES
    Route::group(['middleware' => ['admin']], function() {
        Route::get('{id}/protect', 'PageController@getProtectPage')
            ->whereNumber('id');
        Route::post('{id?}/protect', 'PageController@postProtectPage')
            ->whereNumber('id');
    });
});

Route::group(['prefix' => 'special', 'namespace' => 'Pages'], function() {
    Route::get('create-wanted/{title}', 'SpecialController@getCreateWantedPage');
    Route::post('create-wanted', 'SpecialController@postCreateWantedPage');
});

