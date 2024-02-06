<?php

use App\Http\Controllers\Pages\ImageController;
use App\Http\Controllers\Pages\PageController;
use App\Http\Controllers\Pages\RelationshipController;
use App\Http\Controllers\Pages\SpecialController;
use App\Http\Controllers\Pages\SubjectController;
use App\Http\Controllers\Pages\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Write Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require write permissions to access.
|
*/

Route::get('get/tags', [TagController::class, 'getAllTags']);

Route::prefix('pages')->group(function () {
    Route::controller(PageController::class)->group(function () {
        // BASIC CREATE/EDIT ROUTES
        Route::get('create/{category}', 'getCreatePage')
            ->whereNumber('category');
        Route::get('{id}/edit', 'getEditPage')
            ->whereNumber('id');
        Route::get('{id}/delete', 'getDeletePage')
            ->whereNumber('id');
        Route::get('{page_id}/history/{id}/reset', 'getResetPage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('{id}/move', 'getMovePage')
            ->whereNumber('id');
        Route::post('create', 'postCreateEditPage');
        Route::post('{id?}/edit', 'postCreateEditPage');
        Route::post('{id}/delete', 'postDeletePage')
            ->whereNumber('id');
        Route::post('{page_id}/history/{id}/reset', 'postResetPage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{id}/move', 'postMovePage')
            ->whereNumber('id');

        Route::middleware(['admin'])->group(function () {
            Route::get('{id}/protect', 'getProtectPage')
                ->whereNumber('id');
            Route::post('{id?}/protect', 'postProtectPage')
                ->whereNumber('id');
        });
    });

    Route::controller(ImageController::class)->group(function () {
        Route::get('{id}/gallery/create', 'getCreateImage')
            ->whereNumber('id');
        Route::get('{page_id}/gallery/edit/{id}', 'getEditImage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('{page_id}/gallery/delete/{id}', 'getDeleteImage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{id}/gallery/create', 'postCreateEditImage')
            ->whereNumber('id');
        Route::post('{page_id}/gallery/edit/{id?}', 'postCreateEditImage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{page_id}/gallery/delete/{id}', 'postDeleteImage')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    });

    Route::controller(RelationshipController::class)->group(function () {
        Route::get('{id}/relationships/create', 'getCreateRelationship')
            ->whereNumber('id');
        Route::get('{page_id}/relationships/edit/{id}', 'getEditRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::get('{page_id}/relationships/delete/{id}', 'getDeleteRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{id}/relationships/create', 'postCreateEditRelationship')
            ->whereNumber('id');
        Route::post('{page_id}/relationships/edit/{id?}', 'postCreateEditRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
        Route::post('{page_id}/relationships/delete/{id}', 'postDeleteRelationship')
            ->where(['page_id' => '[0-9]+', 'id' => '[0-9]+']);
    });
});

Route::controller(SubjectController::class)->prefix('language/lexicon')->group(function () {
    Route::get('create', 'getCreateLexiconEntry');
    Route::get('edit/{id?}', 'getEditLexiconEntry')
        ->whereNumber('id');
    Route::get('delete/{id?}', 'getDeleteLexiconEntry')
        ->whereNumber('id');
    Route::post('create', 'postCreateEditLexiconEntry');
    Route::post('edit/{id?}', 'postCreateEditLexiconEntry')
        ->whereNumber('id');
    Route::post('delete/{id?}', 'postDeleteLexiconEntry')
        ->whereNumber('id');
});

Route::controller(SpecialController::class)->prefix('special')->group(function () {
    Route::get('create-wanted/{title}', 'getCreateWantedPage');
    Route::post('create-wanted', 'postCreateWantedPage');
});
