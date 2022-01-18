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

Route::group(['namespace' => 'Pages'], function () {
    Route::group(['prefix' => 'pages'], function () {
        # BASIC CREATE/EDIT ROUTES
        Route::get('create/{category}', 'PageController@getCreatePage')
            ->whereNumber('category');
        Route::get('{id}/edit', 'PageController@getEditPage')
            ->whereNumber('id');
        Route::get('{id}/delete', 'PageController@getDeletePage')
            ->whereNumber('id');
        Route::get('{page_id}/history/{id}/reset', 'PageController@getResetPage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('{id}/move', 'PageController@getMovePage')
            ->whereNumber('id');
        Route::post('create', 'PageController@postCreateEditPage');
        Route::post('{id?}/edit', 'PageController@postCreateEditPage');
        Route::post('{id}/delete', 'PageController@postDeletePage')
            ->whereNumber('id');
        Route::post('{page_id}/history/{id}/reset', 'PageController@postResetPage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{id}/move', 'PageController@postMovePage')
            ->whereNumber('id');

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

        # RELATIONSHIP ROUTES
        Route::get('{id}/relationships/create', 'RelationshipController@getCreateRelationship')
            ->whereNumber('id');
        Route::get('{page_id}/relationships/edit/{id}', 'RelationshipController@getEditRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('{page_id}/relationships/delete/{id}', 'RelationshipController@getDeleteRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{id}/relationships/create', 'RelationshipController@postCreateEditRelationship')
            ->whereNumber('id');
        Route::post('{page_id}/relationships/edit/{id?}', 'RelationshipController@postCreateEditRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{page_id}/relationships/delete/{id}', 'RelationshipController@postDeleteRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);

        # PROTECTION ROUTES
        Route::group(['middleware' => ['admin']], function () {
            Route::get('{id}/protect', 'PageController@getProtectPage')
                ->whereNumber('id');
            Route::post('{id?}/protect', 'PageController@postProtectPage')
                ->whereNumber('id');
        });
    });

    Route::group(['prefix' => 'language/lexicon'], function () {
        # LEXICON ROUTES
        Route::get('create', 'SubjectController@getCreateLexiconEntry');
        Route::get('edit/{id?}', 'SubjectController@getEditLexiconEntry')
            ->whereNumber('id');
        Route::get('delete/{id?}', 'SubjectController@getDeleteLexiconEntry')
            ->whereNumber('id');
        Route::post('create', 'SubjectController@postCreateEditLexiconEntry');
        Route::post('edit/{id?}', 'SubjectController@postCreateEditLexiconEntry')
            ->whereNumber('id');
        Route::post('delete/{id?}', 'SubjectController@postDeleteLexiconEntry')
            ->whereNumber('id');
    });

    Route::group(['prefix' => 'special'], function () {
        # SPECIAL ROUTES
        Route::get('create-wanted/{title}', 'SpecialController@getCreateWantedPage');
        Route::post('create-wanted', 'SpecialController@postCreateWantedPage');
    });
});
