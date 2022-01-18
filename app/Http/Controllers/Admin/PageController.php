<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\SitePage;
use App\Services\SitePageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin / Text Page Controller
    |--------------------------------------------------------------------------
    |
    | Handles editing of text pages.
    |
    */

    /**
     * Shows the text page index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('admin.pages.index', [
            'pages' => SitePage::orderBy('key')->paginate(20),
        ]);
    }

    /**
     * Shows the edit text page page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditPage($id)
    {
        $page = SitePage::find($id);
        if (!$page) {
            abort(404);
        }

        return view('admin.pages.edit_page', [
            'page' => $page,
        ]);
    }

    /**
     * Edits a text page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\SitePageService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditPage(Request $request, SitePageService $service, $id = null)
    {
        $data = $request->only(['text']);

        if ($service->updatePage(SitePage::find($id), $data, Auth::user())) {
            flash('Page updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }
}
