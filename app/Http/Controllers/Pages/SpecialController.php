<?php

namespace App\Http\Controllers\Pages;

use Auth;
use Config;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;

use App\Models\Page\Page;
use App\Models\Page\PageTag;
use App\Models\Page\PageLink;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Special Page Controller
    |--------------------------------------------------------------------------
    |
    | Handles special pages.
    |
    */

    /**
     * Shows the special page index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSpecialIndex()
    {
        return view('pages.special.special');
    }

    /**
     * Redirects to a random page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRandomPage()
    {
        $page = Page::visible(Auth::check() ? Auth::user() : null)->get()->random();

        return redirect($page->url);
    }

    /**
     * Shows list of all pages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAllPages(Request $request)
    {
        $query = Page::visible(Auth::check() ? Auth::user() : null);
        $sort = $request->only(['sort']);

        if($request->get('title')) $query->where(function($query) use ($request) {
            $query->where('pages.title', 'LIKE', '%' . $request->get('title') . '%');
        });
        if($request->get('category_id')) $query->where('category_id', $request->get('category_id'));
        if($request->get('tags'))
            foreach($request->get('tags') as $tag)
                $query->whereIn('id', PageTag::tagSearch($tag)->tag()->pluck('page_id')->toArray());

        if(isset($sort['sort']))
        {
            switch($sort['sort']) {
                case 'alpha':
                    $query->orderBy('title');
                    break;
                case 'alpha-reverse':
                    $query->orderBy('title', 'DESC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        }
        else $query->orderBy('title');

        return view('pages.special.special_all', [
            'pages' => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'tags' => (new PageTag)->listTags(),
            'dateHelper' => new TimeDivision
        ]);
    }

    /**
     * Shows list of most tagged pages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $mode
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUntaggedPages(Request $request)
    {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get()
        ->filter(function ($page) {
            return $page->tags->count() == 0;
        })->sortBy('created_at');

        return view('pages.special.special_untagged', [
            'pages' => $query->paginate(20)->appends($request->query())
        ]);
    }

    /**
     * Shows list of most tagged pages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $mode
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMostTaggedPages(Request $request)
    {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get()
        ->filter(function ($page) {
            return $page->tags->count() > 0;
        })
        ->sortByDesc(function ($page) {
            return $page->tags->count();
        });

        return view('pages.special.special_most_tagged', [
            'pages' => $query->paginate(20)->appends($request->query())
        ]);
    }

    /**
     * Shows list of revised pages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $mode
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRevisedPages(Request $request, $mode)
    {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get();

        if($mode == 'least')
            $query = $query->sortBy(function ($page) {
                return $page->versions->count();
            });
        else
            $query = $query->sortByDesc(function ($page) {
                return $page->versions->count();
            });

        return view('pages.special.special_revised', [
            'mode' => $mode,
            'pages' => $query->paginate(20)->appends($request->query())
        ]);
    }

    /**
     * Shows list of most linked-to pages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMostLinkedPages(Request $request)
    {
        $query = PageLink::whereNotNull('link_id')->get()->filter(function ($value) {
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $value->linkedPage->is_visible;
        })->groupBy('link_id')->sortByDesc(function ($group) {
            return $group->count();
        });

        return view('pages.special.special_linked', [
            'pages' => $query->paginate(20)->appends($request->query())
        ]);
    }

    /**
     * Shows list of all pages with a given utility tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $tag
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUtilityTagPages(Request $request, $tag)
    {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->whereIn('id', PageTag::utilityTag()->tagSearch($tag)->pluck('page_id')->toArray());
        $sort = $request->only(['sort']);

        if($request->get('title')) $query->where(function($query) use ($request) {
            $query->where('pages.title', 'LIKE', '%' . $request->get('title') . '%');
        });
        if($request->get('category_id')) $query->where('category_id', $request->get('category_id'));
        if($request->get('tags'))
            foreach($request->get('tags') as $searchTag)
                $query->whereIn('id', PageTag::tagSearch($searchTag)->tag()->pluck('page_id')->toArray());

        if(isset($sort['sort']))
        {
            switch($sort['sort']) {
                case 'alpha':
                    $query->orderBy('title');
                    break;
                case 'alpha-reverse':
                    $query->orderBy('title', 'DESC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        }
        else $query->orderBy('title');

        return view('pages.special.special_utility', [
            'tag' => Config::get('mundialis.utility_tags.'.$tag),
            'pages' => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'tags' => (new PageTag)->listTags(),
            'dateHelper' => new TimeDivision
        ]);
    }

    /**
     * Shows list of all wanted pages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getWantedPages(Request $request)
    {
        $query = PageLink::whereNotNull('title')->get()->filter(function ($value) {
            if(Auth::check() && Auth::user()->canWrite) return 1;
            return $value->page->is_visible;
        })->groupBy('title')->sortByDesc(function ($group) {
            return $group->count();
        });

        return view('pages.special.special_wanted', [
            'pages' => $query->paginate(20)->appends($request->query())
        ]);
    }

}
