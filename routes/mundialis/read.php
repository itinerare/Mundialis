<?php

/*
|--------------------------------------------------------------------------
| Read Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require read access to view. Can either be viewed
| by anyone or only by anyone with an account depending on site settings.
|
*/

Route::redirect('/pages', '/');

Route::group(['namespace' => 'Pages'], function() {
    # SUBJECTS/CATEGORIES
    Route::get('{subject}', 'PageController@getSubject')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));
    Route::get('{subject}/categories/{id}', 'PageController@getSubjectCategory')
        ->where(['subject' => implode('|', array_keys(Config::get('mundialis.subjects'))), 'id' => '[0-9]+']);

    # PAGES
    Route::group(['prefix' => 'pages'], function() {
        Route::get('/', 'PageController@getPagesIndex');
        Route::get('{id}', 'PageController@getPage');

        Route::get('{id}/gallery', 'ImageController@getPageGallery')
            ->where('id', '[0-9]+');
        Route::get('{page_id}/gallery/{id}', 'ImageController@getPageImage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('get-image/{page_id}/{id}', 'ImageController@getPageImagePopup')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);

        Route::get('{id}/history', 'PageController@getPageHistory')
            ->whereNumber('id');
        Route::get('{page_id}/history/{id}', 'PageController@getPageVersion')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    });

    # SPECIAL PAGES
    Route::group(['prefix' => 'special'], function() {
        Route::get('/', 'SpecialController@getSpecialIndex');
        Route::get('all-pages', 'SpecialController@getAllPages');
        Route::get('random-page', 'SpecialController@getRandomPage');

        # MAINTENANCE REPORTS
        Route::get('wanted-pages', 'SpecialController@getWantedPages');
        Route::get('protected-pages', 'SpecialController@getProtectedPages');
        Route::get('{tag}-pages', 'SpecialController@getUtilityTagPages')
            ->where('tag', implode('|', array_keys(Config::get('mundialis.page_tags'))));
    });
});
