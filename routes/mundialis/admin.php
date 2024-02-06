<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Data\SubjectController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\SpecialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for pages that require admin permissions to access. These largely
| encompass editing core site settings and page templates.
|
*/

Route::controller(AdminController::class)->get('/', 'getIndex');

/*
    DATA/SUBJECTS
*/
Route::prefix('data')->group(function () {
    Route::controller(SubjectController::class)->group(function () {
        Route::get('{subject}', 'getSubjectIndex')
            ->where('subject', implode('|', array_keys(config('mundialis.subjects'))));
        Route::get('{subject}/edit', 'getEditTemplate')
            ->where('subject', implode('|', array_keys(config('mundialis.subjects'))));
        Route::get('{subject}/create', 'getCreateCategory')
            ->where('subject', implode('|', array_keys(config('mundialis.subjects'))));
        Route::post('{subject}/edit', 'postEditTemplate')
            ->where('subject', implode('|', array_keys(config('mundialis.subjects'))));
        Route::post('{subject}/create', 'postCreateEditCategory')
            ->where('subject', implode('|', array_keys(config('mundialis.subjects'))));
        Route::post('{subject}/sort', 'postSortCategory')
            ->where('subject', implode('|', array_keys(config('mundialis.subjects'))));

        Route::prefix('categories')->group(function () {
            Route::get('edit/{id}', 'getEditCategory');
            Route::get('delete/{id}', 'getDeleteCategory');
            Route::post('edit/{id?}', 'postCreateEditCategory');
            Route::post('delete/{id}', 'postDeleteCategory');
        });

        Route::prefix('time')->group(function () {
            Route::get('divisions', 'getTimeDivisions');
            Route::post('divisions', 'postEditDivisions');

            Route::prefix('chronology')->group(function () {
                Route::get('/', 'getTimeChronology');
                Route::get('create', 'getCreateChronology');
                Route::get('edit/{id}', 'getEditChronology');
                Route::get('delete/{id}', 'getDeleteChronology');

                Route::post('create', 'postCreateEditChronology');
                Route::post('edit/{id?}', 'postCreateEditChronology');
                Route::post('delete/{id}', 'postDeleteChronology');
                Route::post('sort', 'postSortChronology');
            });
        });

        Route::prefix('language')->group(function () {
            Route::get('lexicon-settings', 'getLexiconSettings');
            Route::post('lexicon-settings', 'postEditLexiconSettings');

            Route::prefix('lexicon-categories')->group(function () {
                Route::get('/', 'getLexiconCategories');
                Route::get('create', 'getCreateLexiconCategory');
                Route::get('edit/{id}', 'getEditLexiconCategory');
                Route::get('delete/{id}', 'getDeleteLexiconCategory');

                Route::post('create', 'postCreateEditLexiconCategory');
                Route::post('edit/{id?}', 'postCreateEditLexiconCategory');
                Route::post('delete/{id}', 'postDeleteLexiconCategory');
                Route::post('sort', 'postSortLexiconCategory');
            });
        });
    });
});

/*
    USERS
*/

Route::controller(RankController::class)->prefix('ranks')->group(function () {
    Route::get('/', 'getIndex');
    Route::get('edit/{id}', 'getEditRank');
    Route::post('edit/{id?}', 'postEditRank');
});

Route::controller(InvitationController::class)->prefix('invitations')->group(function () {
    Route::get('/', 'getIndex');
    Route::post('create', 'postGenerateKey');
    Route::post('delete/{id?}', 'postDeleteKey');
});

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('/', 'getUserIndex');
    Route::get('{name}/edit', 'getUser');
    Route::get('{name}/updates', 'getUserUpdates');
    Route::post('{name}/basic', 'postUserBasicInfo');
    Route::post('{name}/account', 'postUserAccount');

    Route::get('{name}/ban', 'getBan');
    Route::get('{name}/ban-confirm', 'getBanConfirmation');
    Route::post('{name}/ban', 'postBan');
    Route::get('{name}/unban-confirm', 'getUnbanConfirmation');
    Route::post('{name}/unban', 'postUnban');
});

/*
    MAINTENANCE
*/
Route::controller(SpecialController::class)->prefix('special')->group(function () {
    Route::get('unwatched-pages', 'getUnwatchedPages');

    Route::prefix('deleted-pages')->group(function () {
        Route::get('/', 'getDeletedPages');
        Route::get('{id}', 'getDeletedPage')
            ->whereNumber('id');
        Route::get('{id}/restore', 'getRestorePage')
            ->whereNumber('id');
        Route::post('{id?}/restore', 'postRestorePage')
            ->whereNumber('id');
    });

    Route::prefix('deleted-images')->group(function () {
        Route::get('/', 'getDeletedImages');
        Route::get('{id}', 'getDeletedImage')
            ->whereNumber('id');
        Route::get('{id}/restore', 'getRestoreImage')
            ->whereNumber('id');
        Route::post('{id?}/restore', 'postRestoreImage')
            ->whereNumber('id');
    });
});

/*
    SITE SETTINGS
*/
Route::controller(PageController::class)->prefix('pages')->group(function () {
    Route::get('/', 'getIndex');
    Route::get('edit/{id}', 'getEditPage');
    Route::post('edit/{id?}', 'postEditPage');
});

Route::controller(AdminController::class)->prefix('site-settings')->group(function () {
    Route::get('/', 'getSettings');
    Route::post('{key}', 'postEditSetting');
});

Route::controller(AdminController::class)->prefix('site-images')->group(function () {
    Route::get('/', 'getSiteImages');
    Route::post('upload', 'postUploadImage');
    Route::post('upload/css', 'postUploadCss');
});
