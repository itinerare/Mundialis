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

Route::group(['prefix' => 'pages', 'namespace' => 'Pages'], function() {
    # BASIC VIEW ROUTES
    Route::get('/', 'PageController@getPagesIndex');
    Route::get('{subject}', 'PageController@getSubject');
    Route::get('{subject}/categories/{id}', 'PageController@getSubjectCategory');
    Route::get('view/{id}', 'PageController@getPage');
});

# SPECIAL PAGES
Route::group(['prefix' => 'special', 'namespace' => 'Pages'], function() {
    Route::get('all-pages', 'PageController@getAllPages');
    Route::get('wanted-pages', 'PageController@getWantedPages');
    Route::get('protected-pages', 'PageController@getProtectedPages');
});
