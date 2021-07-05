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
    # PEOPLE
    Route::group(['prefix' => 'people'], function() {
        Route::get('/', 'PeopleController@getIndex');
        Route::get('edit', 'PeopleController@getEditTemplate');

        Route::get('create', 'PeopleController@getCreateCategory');
        Route::get('edit/{id}', 'PeopleController@getEditCategory');
        Route::get('delete/{id}', 'PeopleController@getDeleteCategory');
        Route::post('create', 'PeopleController@postCreateEditCategory');
        Route::post('edit/{id?}', 'PeopleController@postCreateEditCategory');
        Route::post('delete/{id}', 'PeopleController@postDeleteCategory');
        Route::post('sort', 'PeopleController@postSortCategories');
    });

    # PLACES
    Route::group(['prefix' => 'places'], function() {
        Route::get('/', 'PlacesController@getIndex');
        Route::get('edit', 'PlacesController@getEditTemplate');

        Route::get('create', 'PlacesController@getCreateCategory');
        Route::get('edit/{id}', 'PlacesController@getEditCategory');
        Route::get('delete/{id}', 'PlacesController@getDeleteCategory');
        Route::post('create', 'PlacesController@postCreateEditCategory');
        Route::post('edit/{id?}', 'PlacesController@postCreateEditCategory');
        Route::post('delete/{id}', 'PlacesController@postDeleteCategory');
        Route::post('sort', 'PlacesController@postSortCategories');
    });

    # FLORA & FAUNA
    Route::group(['prefix' => 'species'], function() {
        Route::get('/', 'SpeciesController@getIndex');
        Route::get('edit', 'SpeciesController@getEditTemplate');

        Route::get('create', 'SpeciesController@getCreateCategory');
        Route::get('edit/{id}', 'SpeciesController@getEditCategory');
        Route::get('delete/{id}', 'SpeciesController@getDeleteCategory');
        Route::post('create', 'SpeciesController@postCreateEditCategory');
        Route::post('edit/{id?}', 'SpeciesController@postCreateEditCategory');
        Route::post('delete/{id}', 'SpeciesController@postDeleteCategory');
        Route::post('sort', 'SpeciesController@postSortCategories');
    });

    # THINGS
    Route::group(['prefix' => 'things'], function() {
        Route::get('/', 'ThingsController@getIndex');
        Route::get('edit', 'ThingsController@getEditTemplate');

        Route::get('create', 'ThingsController@getCreateCategory');
        Route::get('edit/{id}', 'ThingsController@getEditCategory');
        Route::get('delete/{id}', 'ThingsController@getDeleteCategory');
        Route::post('create', 'ThingsController@postCreateEditCategory');
        Route::post('edit/{id?}', 'ThingsController@postCreateEditCategory');
        Route::post('delete/{id}', 'ThingsController@postDeleteCategory');
        Route::post('sort', 'ThingsController@postSortCategories');
    });

    # CONCEPTS
    Route::group(['prefix' => 'concepts'], function() {
        Route::get('/', 'ConceptsController@getIndex');
        Route::get('edit', 'ConceptsController@getEditTemplate');

        Route::get('create', 'ConceptsController@getCreateCategory');
        Route::get('edit/{id}', 'ConceptsController@getEditCategory');
        Route::get('delete/{id}', 'ConceptsController@getDeleteCategory');
        Route::post('create', 'ConceptsController@postCreateEditCategory');
        Route::post('edit/{id?}', 'ConceptsController@postCreateEditCategory');
        Route::post('delete/{id}', 'ConceptsController@postDeleteCategory');
        Route::post('sort', 'ConceptsController@postSortCategories');
    });

    # TIME
    Route::group(['prefix' => 'time'], function() {
        Route::get('/', 'TimeController@getIndex');
        Route::get('edit', 'TimeController@getEditTemplate');

        Route::get('create', 'TimeController@getCreateCategory');
        Route::get('edit/{id}', 'TimeController@getEditCategory');
        Route::get('delete/{id}', 'TimeController@getDeleteCategory');
        Route::post('create', 'TimeController@postCreateEditCategory');
        Route::post('edit/{id?}', 'TimeController@postCreateEditCategory');
        Route::post('delete/{id}', 'TimeController@postDeleteCategory');
        Route::post('sort', 'TimeController@postSortCategories');
    });

    # MISC
    Route::group(['prefix' => 'misc'], function() {
        Route::get('/', 'MiscController@getIndex');
        Route::get('edit', 'MiscController@getEditTemplate');

        Route::get('create', 'MiscController@getCreateCategory');
        Route::get('edit/{id}', 'MiscController@getEditCategory');
        Route::get('delete/{id}', 'MiscController@getDeleteCategory');
        Route::post('create', 'MiscController@postCreateEditCategory');
        Route::post('edit/{id?}', 'MiscController@postCreateEditCategory');
        Route::post('delete/{id}', 'MiscController@postDeleteCategory');
        Route::post('sort', 'MiscController@postSortCategories');
    });
});

/*
    MAINTENANCE
*/

# USERS

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
