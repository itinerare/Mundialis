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

Route::group(['prefix' => 'data', 'namespace' => 'Data'], function () {
    // GENERIC ROUTES
    Route::get('{subject}', 'SubjectController@getSubjectIndex')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));
    Route::get('{subject}/edit', 'SubjectController@getEditTemplate')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));

    Route::get('{subject}/create', 'SubjectController@getCreateCategory')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));
    Route::post('{subject}/edit', 'SubjectController@postEditTemplate')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));
    Route::post('{subject}/create', 'SubjectController@postCreateEditCategory')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));

    Route::get('categories/edit/{id}', 'SubjectController@getEditCategory');
    Route::get('categories/delete/{id}', 'SubjectController@getDeleteCategory');
    Route::post('categories/edit/{id?}', 'SubjectController@postCreateEditCategory');
    Route::post('categories/delete/{id}', 'SubjectController@postDeleteCategory');
    Route::post('{subject}/sort', 'SubjectController@postSortCategory')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));

    // SPECIALIZED ROUTES
    Route::group(['prefix' => 'time'], function () {
        Route::get('divisions', 'SubjectController@getTimeDivisions');
        Route::post('divisions', 'SubjectController@postEditDivisions');

        Route::get('chronology', 'SubjectController@getTimeChronology');
        Route::get('chronology/create', 'SubjectController@getCreateChronology');
        Route::get('chronology/edit/{id}', 'SubjectController@getEditChronology');
        Route::get('chronology/delete/{id}', 'SubjectController@getDeleteChronology');

        Route::post('chronology/create', 'SubjectController@postCreateEditChronology');
        Route::post('chronology/edit/{id?}', 'SubjectController@postCreateEditChronology');
        Route::post('chronology/delete/{id}', 'SubjectController@postDeleteChronology');
        Route::post('chronology/sort', 'SubjectController@postSortChronology');
    });

    Route::group(['prefix' => 'language'], function () {
        Route::get('lexicon-settings', 'SubjectController@getLexiconSettings');
        Route::post('lexicon-settings', 'SubjectController@postEditLexiconSettings');

        Route::get('lexicon-categories', 'SubjectController@getLexiconCategories');
        Route::get('lexicon-categories/create', 'SubjectController@getCreateLexiconCategory');
        Route::get('lexicon-categories/edit/{id}', 'SubjectController@getEditLexiconCategory');
        Route::get('lexicon-categories/delete/{id}', 'SubjectController@getDeleteLexiconCategory');

        Route::post('lexicon-categories/create', 'SubjectController@postCreateEditLexiconCategory');
        Route::post('lexicon-categories/edit/{id?}', 'SubjectController@postCreateEditLexiconCategory');
        Route::post('lexicon-categories/delete/{id}', 'SubjectController@postDeleteLexiconCategory');
        Route::post('lexicon-categories/sort', 'SubjectController@postSortLexiconCategory');
    });
});

/*
    USERS
*/

// RANKS
Route::group(['prefix' => 'ranks'], function () {
    Route::get('/', 'RankController@getIndex');
    Route::get('edit/{id}', 'RankController@getEditRank');
    Route::post('edit/{id?}', 'RankController@postEditRank');
});

// INVITATIONS
Route::group(['prefix' => 'invitations'], function () {
    Route::get('/', 'InvitationController@getIndex');
    Route::post('create', 'InvitationController@postGenerateKey');
    Route::post('delete/{id}', 'InvitationController@postDeleteKey');
});

// USERS
Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@getUserIndex');
    Route::get('{name}/edit', 'UserController@getUser');
    Route::get('{name}/updates', 'UserController@getUserUpdates');
    Route::post('{name}/basic', 'UserController@postUserBasicInfo');
    Route::post('{name}/account', 'UserController@postUserAccount');
    Route::post('{name}/forgot-password', 'UserController@postForgotPassword');

    Route::get('{name}/ban', 'UserController@getBan');
    Route::get('{name}/ban-confirm', 'UserController@getBanConfirmation');
    Route::post('{name}/ban', 'UserController@postBan');
    Route::get('{name}/unban-confirm', 'UserController@getUnbanConfirmation');
    Route::post('{name}/unban', 'UserController@postUnban');
});

/*
    MAINTENANCE
*/

// SPECIAL PAGES
Route::group(['prefix' => 'special'], function () {
    Route::get('unwatched-pages', 'SpecialController@getUnwatchedPages');

    Route::get('deleted-pages', 'SpecialController@getDeletedPages');
    Route::get('deleted-pages/{id}', 'SpecialController@getDeletedPage')
        ->whereNumber('id');
    Route::get('deleted-pages/{id}/restore', 'SpecialController@getRestorePage')
        ->whereNumber('id');
    Route::post('deleted-pages/{id?}/restore', 'SpecialController@postRestorePage')
        ->whereNumber('id');
    Route::get('deleted-images', 'SpecialController@getDeletedImages');
    Route::get('deleted-images/{id}', 'SpecialController@getDeletedImage')
        ->whereNumber('id');
    Route::get('deleted-images/{id}/restore', 'SpecialController@getRestoreImage')
        ->whereNumber('id');
    Route::post('deleted-images/{id?}/restore', 'SpecialController@postRestoreImage')
        ->whereNumber('id');
});

/*
    SITE SETTINGS
*/

// TEXT PAGES
Route::group(['prefix' => 'pages'], function () {
    Route::get('/', 'PageController@getIndex');
    Route::get('edit/{id}', 'PageController@getEditPage');
    Route::post('edit/{id?}', 'PageController@postEditPage');
});

// SITE SETTINGS
Route::get('site-settings', 'AdminController@getSettings');
Route::post('site-settings/{key}', 'AdminController@postEditSetting');

// SITE IMAGES
Route::group(['prefix' => 'site-images'], function () {
    Route::get('/', 'AdminController@getSiteImages');
    Route::post('upload', 'AdminController@postUploadImage');
    Route::post('upload/css', 'AdminController@postUploadCss');
});
