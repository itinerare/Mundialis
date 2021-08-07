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

# PROFILES
Route::group(['prefix' => 'user', 'namespace' => 'Users'], function() {
    Route::get('{name}', 'UserController@getUser');
    Route::get('{name}/page-revisions', 'UserController@getUserPageRevisions');
    Route::get('{name}/image-revisions', 'UserController@getUserImageRevisions');
});

Route::group(['namespace' => 'Pages'], function() {
    # SUBJECTS/CATEGORIES
    Route::get('{subject}', 'SubjectController@getSubject')
        ->where('subject', implode('|', array_keys(Config::get('mundialis.subjects'))));
    Route::get('{subject}/categories/{id}', 'SubjectController@getSubjectCategory')
        ->where(['subject' => implode('|', array_keys(Config::get('mundialis.subjects'))), 'id' => '[0-9]+']);

    # PAGES
    Route::group(['prefix' => 'pages'], function() {
        Route::get('/', 'PageController@getPagesIndex');
        Route::get('{id}', 'PageController@getPage');

        Route::get('{id}/gallery', 'ImageController@getPageGallery')
            ->whereNumber('id');
        Route::get('{page_id}/gallery/{id}', 'ImageController@getPageImage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('get-image/{page_id}/{id}', 'ImageController@getPageImagePopup')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);

        Route::get('{id}/history', 'PageController@getPageHistory')
            ->whereNumber('id');
        Route::get('{page_id}/history/{id}', 'PageController@getPageVersion')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);

        Route::get('{id}/relationships', 'RelationshipController@getPageRelationships')
            ->whereNumber('id');
        Route::get('{id}/relationships/tree', 'RelationshipController@getPageFamilyTree')
            ->whereNumber('id');

        Route::get('{id}/links-here', 'PageController@getLinksHere')
            ->whereNumber('id');

        Route::group(['prefix' => 'tags'], function() {
            Route::get('{tag}', 'TagController@getTag');
        });
    });

    # TIME
    Route::group(['prefix' => 'time'], function() {
        Route::get('timeline', 'SubjectController@getTimeTimeline');
        Route::get('chronologies/{id}', 'SubjectController@getTimeChronology')
            ->whereNumber('id');
    });

    # LEXICON
    Route::group(['prefix' => 'language/lexicon'], function() {
        Route::get('{id}', 'SubjectController@getLexiconCategory')
            ->whereNumber('id');
        Route::get('entries/{id}', 'SubjectController@getLexiconEntryModal')
            ->whereNumber('id');
    });

    # SPECIAL PAGES
    Route::group(['prefix' => 'special'], function() {
        Route::get('/', 'SpecialController@getSpecialIndex');

        # MAINTENANCE REPORTS
        Route::get('untagged-pages', 'SpecialController@getUntaggedPages');
        Route::get('tagged-pages', 'SpecialController@getMostTaggedPages');
        Route::get('{mode}-revised-pages', 'SpecialController@getRevisedPages')
            ->whereAlphanumeric('mode');
        Route::get('linked-pages', 'SpecialController@getMostLinkedPages');
        Route::get('recent-pages', 'SpecialController@getRecentPages');
        Route::get('recent-images', 'SpecialController@getRecentImages');

        Route::get('wanted-pages', 'SpecialController@getWantedPages');
        Route::get('protected-pages', 'SpecialController@getProtectedPages');
        Route::get('{tag}-pages', 'SpecialController@getUtilityTagPages')
            ->where('tag', implode('|', array_keys(Config::get('mundialis.utility_tags'))));

        # LISTS
        Route::get('all-pages', 'SpecialController@getAllPages');
        Route::get('all-tags', 'SpecialController@getAllTags');
        Route::get('all-images', 'SpecialController@getAllImages');
        Route::get('get-image/{id}', 'ImageController@getPageImagePopup')
            ->whereNumber('id');

        # OTHER
        Route::get('random-page', 'SpecialController@getRandomPage');
    });
});
