<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\SitePage;
use App\Models\Page\PageVersion;
use App\Models\Page\PageImageVersion;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Show the index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        $pageVersions = PageVersion::orderBy('created_at', 'DESC')->get()->filter(function ($version) {
            if(Auth::check() && Auth::user()->isAdmin) return 1;
            if(!$version->page || isset($version->page->deleted_at)) return 0;
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $version->page->is_visible;
        });

        $imageVersions = PageImageVersion::orderBy('updated_at', 'DESC')->get()->filter(function ($version) {
            if(Auth::check() && Auth::user()->isAdmin) return 1;
            if(!$version->image || isset($version->image->deleted_at)) return 0;
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $version->image->is_visible;
        });

        return view('index', [
            'page' => SitePage::where('key', 'about')->first(),
            'pageVersions' => $pageVersions->take(10),
            'imageVersions' => $imageVersions->take(10)
        ]);
    }
}
