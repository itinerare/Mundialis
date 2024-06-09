<?php

namespace App\Http\Controllers;

use App\Models\Page\PageImageVersion;
use App\Models\Page\PageVersion;
use App\Models\SitePage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Show the index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex() {
        $pageVersions = PageVersion::with('page', 'user')->orderBy('created_at', 'DESC')->get()->filter(function ($version) {
            if (!$version->page || isset($version->page->deleted_at)) {
                return 0;
            }
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $version->page->is_visible;
        });

        $imageVersions = PageImageVersion::with('image', 'user')->orderBy('updated_at', 'DESC')->get()->filter(function ($version) {
            if (!$version->image || isset($version->image->deleted_at)) {
                return 0;
            }
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $version->image->is_visible;
        });

        return view('index', [
            'page'          => SitePage::where('key', 'about')->first(),
            'pageVersions'  => $pageVersions->take(10),
            'imageVersions' => $imageVersions->take(10),
        ]);
    }

    /**
     * Show the terms of service page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getTermsOfService() {
        $page = SitePage::where('key', 'terms')->first();
        if (!$page) {
            abort(404);
        }

        return view('text_page', [
            'page' => $page,
        ]);
    }

    /**
     * Show the privacy policy page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPrivacyPolicy() {
        $page = SitePage::where('key', 'privacy')->first();
        if (!$page) {
            abort(404);
        }

        return view('text_page', [
            'page' => $page,
        ]);
    }
}
