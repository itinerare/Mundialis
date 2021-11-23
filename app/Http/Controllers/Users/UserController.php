<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;

use DB;
use Auth;
use Route;
use App\Models\User\User;

use App\Models\Page\PageVersion;
use App\Models\Page\PageImageVersion;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | Displays user profile pages.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $name = Route::current()->parameter('name');
        $this->user = User::where('name', $name)->first();
        if(!$this->user) abort(404);
    }

    /**
     * Shows a user's profile.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUser($name)
    {
        $pageVersions = PageVersion::orderBy('created_at', 'DESC')->where('user_id', $this->user->id)->get()->filter(function ($version) {
            if(!$version->page || isset($version->page->deleted_at)) return 0;
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $version->page->is_visible;
        });

        $imageVersions = PageImageVersion::orderBy('updated_at', 'DESC')->where('user_id', $this->user->id)->get()->filter(function ($version) {
            if(!$version->image || isset($version->image->deleted_at)) return 0;
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $version->image->is_visible;
        });

        return view('user.profile', [
            'user' => $this->user,
            'pageVersions' => $pageVersions->take(15),
            'imageVersions' => $imageVersions->take(5)
        ]);
    }

    /**
     * Shows a user's page revisions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $name
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUserPageRevisions(Request $request, $name)
    {
        $query = PageVersion::orderBy('created_at', 'DESC')->where('user_id', $this->user->id)->get()->filter(function ($version) {
            if(!$version->page || isset($version->page->deleted_at)) return 0;
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $version->page->is_visible;
        });

        return view('user.page_revisions', [
            'user' => $this->user,
            'versions' => $query->paginate(20)->appends($request->query())
        ]);
    }

    /**
     * Shows a user's image revisions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $name
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUserImageRevisions(Request $request, $name)
    {
        $query = PageImageVersion::orderBy('updated_at', 'DESC')->where('user_id', $this->user->id)->get()->filter(function ($version) {
            if(!$version->image || isset($version->image->deleted_at)) return 0;
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $version->image->is_visible;
        });

        return view('user.image_revisions', [
            'user' => $this->user,
            'versions' => $query->paginate(15)->appends($request->query())
        ]);
    }
}
