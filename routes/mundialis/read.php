<?php

use App\Http\Controllers\Pages\ImageController;
use App\Http\Controllers\Pages\PageController;
use App\Http\Controllers\Pages\RelationshipController;
use App\Http\Controllers\Pages\SpecialController;
use App\Http\Controllers\Pages\SubjectController;
use App\Http\Controllers\Pages\TagController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Read Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require read access. Can either be viewed by anyone
| or only by anyone with an account depending on site settings.
|
*/

Route::redirect('pages', '/');

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('{name}', 'getUser');
    Route::get('{name}/page-revisions', 'getUserPageRevisions');
    Route::get('{name}/image-revisions', 'getUserImageRevisions');
});

Route::controller(SubjectController::class)->group(function () {
    Route::get('{subject}', 'getSubject')
        ->where('subject', implode('|', array_keys(config('mundialis.subjects'))));
    Route::get('{subject}/categories/{id}', 'getSubjectCategory')
        ->where(['subject' => implode('|', array_keys(config('mundialis.subjects'))), 'id' => '[0-9]+']);

    Route::prefix('time')->group(function () {
        Route::get('timeline', 'getTimeTimeline');
        Route::get('chronologies/{id}', 'getTimeChronology')
            ->whereNumber('id');
    });

    Route::prefix('language/lexicon')->group(function () {
        Route::get('{id}', 'getLexiconCategory')
            ->whereNumber('id');
        Route::get('entries/{id}', 'getLexiconEntryModal')
            ->whereNumber('id');
    });
});

Route::prefix('pages')->group(function () {
    Route::controller(PageController::class)->group(function () {
        Route::get('{id}.', 'getPage');
        Route::get('{id}.{slug?}', 'getPage');
        Route::get('{id}/history', 'getPageHistory')
            ->whereNumber('id');
        Route::get('{page_id}/history/{id}', 'getPageVersion')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('{id}/links-here', 'getLinksHere')
            ->whereNumber('id');
    });

    Route::controller(ImageController::class)->group(function () {
        Route::get('{id}/gallery', 'getPageGallery')
            ->whereNumber('id');
        Route::get('{page_id}/gallery/{id}', 'getPageImage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('get-image/{page_id}/{id}', 'getPageImagePopup')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    });

    Route::controller(RelationshipController::class)->group(function () {
        Route::get('{id}/relationships', 'getPageRelationships')
            ->whereNumber('id');
        Route::get('{id}/relationships/tree', 'getPageFamilyTree')
            ->whereNumber('id');
    });

    Route::controller(TagController::class)->prefix('tags')->group(function () {
        Route::get('{tag}', 'getTag');
    });
});

Route::prefix('special')->group(function () {
    Route::controller(SpecialController::class)->group(function () {
        Route::get('/', 'getSpecialIndex');

        // MAINTENANCE REPORTS
        Route::get('untagged-pages', 'getUntaggedPages');
        Route::get('tagged-pages', 'getMostTaggedPages');
        Route::get('{mode}-revised-pages', 'getRevisedPages')
            ->whereAlphanumeric('mode');
        Route::get('unlinked-pages', 'getUnlinkedPages');
        Route::get('linked-pages', 'getMostLinkedPages');
        Route::get('recent-pages', 'getRecentPages');
        Route::get('recent-images', 'getRecentImages');

        Route::get('wanted-pages', 'getWantedPages');
        Route::get('protected-pages', 'getProtectedPages');
        Route::get('{tag}-pages', 'getUtilityTagPages')
            ->where('tag', implode('|', array_keys(config('mundialis.utility_tags'))));

        // LISTS
        Route::get('all-pages', 'getAllPages');
        Route::get('all-tags', 'getAllTags');
        Route::get('all-images', 'getAllImages');

        // USERS
        Route::get('user-list', 'getUserList');

        // OTHER
        Route::get('random-page', 'getRandomPage');
    });

    Route::controller(ImageController::class)->group(function () {
        Route::get('get-image/{id}', 'getPageImagePopup')
            ->whereNumber('id');
    });
});
