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

# SPECIAL PAGES
Route::group(['prefix' => 'special'], function() {
    Route::get('all-pages', 'SpecialController@getAllPages');
    Route::get('wanted-pages', 'SpecialController@getWantedPages');
    Route::get('protected-pages', 'SpecialController@getProtectedPages');
});
