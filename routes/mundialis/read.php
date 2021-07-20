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
    });

    # SPECIAL PAGES
    Route::group(['prefix' => 'special'], function() {
        Route::get('all-pages', 'PageController@getAllPages');
        Route::get('wanted-pages', 'PageController@getWantedPages');
        Route::get('protected-pages', 'PageController@getProtectedPages');
    });
});
