<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Services\FileManager;

use Config;
use Settings;
use DB;
use Auth;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    |
    | Handles general admin requests.
    |
    */

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('admin.index');
    }

    /******************************************************************************
        SITE SETTINGS
    *******************************************************************************/

    /**
     * Shows the settings index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSettings()
    {
        return view('admin.settings', [
            'settings' => DB::table('site_settings')->orderBy('key')->get()
        ]);
    }

    /**
     * Edits a setting.
     *
     * @param  \Illuminate\Http\Request       $request
     * @param  string                         $key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditSetting(Request $request, $key)
    {
        if (!$request->get('value')) {
            $value = 0;
        }
        if (DB::table('site_settings')->where('key', $key)->update(['value' => isset($value) ? $value : $request->get('value')])) {
            flash('Setting updated successfully.')->success();
        } else {
            flash('Invalid setting selected.')->error();
        }
        return redirect()->back();
    }

    /******************************************************************************
        SITE IMAGES
    *******************************************************************************/

    /**
     * Shows the site images index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSiteImages()
    {
        return view('admin.images', [
            'images' => Config::get('mundialis.image_files')
        ]);
    }

    /**
     * Uploads a site image file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\FileManager  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUploadImage(Request $request, FileManager $service)
    {
        $request->validate(['file' => 'required|file']);
        $file = $request->file('file');
        $key = $request->get('key');
        $filename = Config::get('mundialis.image_files.'.$key)['filename'];

        if ($service->uploadFile($file, null, $filename, false)) {
            flash('Image uploaded successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }
        return redirect()->back();
    }

    /**
     * Uploads a custom site CSS file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\FileManager  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUploadCss(Request $request, FileManager $service)
    {
        $request->validate(['file' => 'required|file']);
        $file = $request->file('file');

        if ($service->uploadCss($file)) {
            flash('File uploaded successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }
        return redirect()->back();
    }
}
