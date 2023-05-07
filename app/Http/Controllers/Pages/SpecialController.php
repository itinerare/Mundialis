<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PageLink;
use App\Models\Page\PageTag;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\User\Rank;
use App\Models\User\User;
use Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;

class SpecialController extends Controller {
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
    public function getSpecialIndex() {
        return view('pages.special.special');
    }

    /**
     * Redirects to a random page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRandomPage() {
        if (!Page::visible(Auth::check() ? Auth::user() : null)->count()) {
            return redirect('/');
        }
        $page = Page::visible(Auth::check() ? Auth::user() : null)->get()->random();

        return redirect($page->url);
    }

    /**
     * Shows list of all pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAllPages(Request $request) {
        $query = Page::visible(Auth::check() ? Auth::user() : null);
        $sort = $request->only(['sort']);

        if ($request->get('title')) {
            $query->where(function ($query) use ($request) {
                $query->where('pages.title', 'LIKE', '%'.$request->get('title').'%');
            });
        }
        if ($request->get('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }
        if ($request->get('tags')) {
            foreach ($request->get('tags') as $tag) {
                $query->whereIn('id', PageTag::tagSearch($tag)->tag()->pluck('page_id')->toArray());
            }
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
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
        } else {
            $query->orderBy('title');
        }

        return view('pages.special.all_pages', [
            'pages'           => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'tags'            => (new PageTag)->listTags(),
            'dateHelper'      => new TimeDivision,
        ]);
    }

    /**
     * Shows list of all tags.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAllTags(Request $request) {
        $query = PageTag::tag()->get()
            ->filter(function ($tag) {
                if (Auth::check() && Auth::user()->canWrite) {
                    return 1;
                }
                if (!$tag->page) {
                    return 0;
                }

                return $tag->page->is_visible;
            })->groupBy('baseTag');

        return view('pages.special.all_tags', [
            'tags' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of all images.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAllImages(Request $request) {
        $query = PageImage::visible(Auth::check() ? Auth::user() : null);

        if ($request->get('creator_url')) {
            $creatorUrl = $request->get('creator_url');
            $query->whereHas('creators', function ($query) use ($creatorUrl) {
                $query->where('url', 'LIKE', '%'.$creatorUrl.'%');
            });
        }
        if ($request->get('creator_id')) {
            $creator = User::find($request->get('creator_id'));
            $query->whereHas('creators', function ($query) use ($creator) {
                $query->where('user_id', $creator->id);
            });
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        return view('pages.special.all_images', [
            'images' => $query->paginate(20)->appends($request->query()),
            'users'  => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows list of untagged pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUntaggedPages(Request $request) {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get()
            ->filter(function ($page) {
                return $page->tags->count() == 0;
            })->sortBy('created_at');

        return view('pages.special.untagged', [
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of most tagged pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMostTaggedPages(Request $request) {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get()
            ->filter(function ($page) {
                return $page->tags->count() > 0;
            })
            ->sortByDesc(function ($page) {
                return $page->tags->count();
            });

        return view('pages.special.most_tagged', [
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of revised pages.
     *
     * @param string $mode
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRevisedPages(Request $request, $mode) {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get();

        if ($mode == 'least') {
            $query = $query->sortBy(function ($page) {
                return $page->versions->count();
            });
        } else {
            $query = $query->sortByDesc(function ($page) {
                return $page->versions->count();
            });
        }

        return view('pages.special.revised', [
            'mode'  => $mode,
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of most linked-to pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMostLinkedPages(Request $request) {
        $query = PageLink::whereNotNull('link_id')->get()->filter(function ($value) {
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $value->linkedPage->is_visible;
        })->groupBy('link_id')->sortByDesc(function ($group) {
            return $group->count();
        });

        return view('pages.special.linked', [
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of recent page edits.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRecentPages(Request $request) {
        $query = PageVersion::orderBy('created_at', 'DESC')->get()->filter(function ($version) {
            if (!$version->page || isset($version->page->deleted_at)) {
                return 0;
            }
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $version->page->is_visible;
        });

        $mode = $request->get('mode');
        if (isset($mode) && is_numeric($mode)) {
            $query = $query->filter(function ($version) use ($mode) {
                if ($version->created_at > Carbon::now()->subDays($mode)) {
                    return 1;
                }

                return 0;
            });
        }

        return view('pages.special.recent_pages', [
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of recent image edits.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRecentImages(Request $request) {
        $query = PageImageVersion::orderBy('updated_at', 'DESC')->get()->filter(function ($version) {
            if (!$version->image || isset($version->image->deleted_at)) {
                return 0;
            }
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $version->image->is_visible;
        });

        $mode = $request->get('mode');
        if (isset($mode) && is_numeric($mode)) {
            $query = $query->filter(function ($version) use ($mode) {
                if ($version->created_at > Carbon::now()->subDays($mode)) {
                    return 1;
                }

                return 0;
            });
        }

        return view('pages.special.recent_images', [
            'images' => $query->paginate(15)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of all protected pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getProtectedPages(Request $request) {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get()->filter(function ($page) {
            if ($page->protection && $page->protection->is_protected) {
                return 1;
            }

            return 0;
        })->sortBy('name');

        return view('pages.special.protected', [
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows list of all pages with a given utility tag.
     *
     * @param string $tag
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUtilityTagPages(Request $request, $tag) {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->whereIn('id', PageTag::utilityTag()->tagSearch($tag)->pluck('page_id')->toArray());
        $sort = $request->only(['sort']);

        if ($request->get('title')) {
            $query->where(function ($query) use ($request) {
                $query->where('pages.title', 'LIKE', '%'.$request->get('title').'%');
            });
        }
        if ($request->get('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }
        if ($request->get('tags')) {
            foreach ($request->get('tags') as $searchTag) {
                $query->whereIn('id', PageTag::tagSearch($searchTag)->tag()->pluck('page_id')->toArray());
            }
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
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
        } else {
            $query->orderBy('title');
        }

        return view('pages.special.utility', [
            'tag'             => Config::get('mundialis.utility_tags.'.$tag),
            'pages'           => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'tags'            => (new PageTag)->listTags(),
            'dateHelper'      => new TimeDivision,
        ]);
    }

    /**
     * Shows list of all wanted pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getWantedPages(Request $request) {
        $query = PageLink::whereNotNull('title')->get()->filter(function ($value) {
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $value->parent->is_visible;
        })->groupBy('title')->sortByDesc(function ($group) {
            return $group->count();
        });

        return view('pages.special.wanted', [
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows the interface to create a wanted page.
     *
     * @param string $title
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateWantedPage($title) {
        // Collect categories and information and group them
        $groupedCategories = SubjectCategory::orderBy('sort', 'DESC')->get()->keyBy('id')->groupBy(function ($category) {
            return $category->subject['name'];
        }, $preserveKeys = true)->toArray();

        // Collect subjects and information
        $orderedSubjects = collect(Config::get('mundialis.subjects'))->filter(function ($subject) use ($groupedCategories) {
            if (isset($groupedCategories[$subject['name']])) {
                return 1;
            } else {
                return 0;
            }
        })->pluck('name', 'name');

        foreach ($groupedCategories as $subject=> $categories) {
            foreach ($categories as $id=>$category) {
                $groupedCategories[$subject][$id] = $category['name'];
            }
        }

        // Organize them according to standard subject listing
        $sortedCategories = $orderedSubjects->map(function ($subject, $key) use ($groupedCategories) {
            return $groupedCategories[$subject];
        });

        return view('pages.special.create_wanted', [
            'title'      => $title,
            'categories' => $sortedCategories,
        ]);
    }

    /**
     * Redirects to page creation based on provided input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateWantedPage(Request $request) {
        return redirect()->to('pages/create/'.$request->get('category_id').'?title='.$request->get('title'));
    }

    /**
     * Shows the user list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUserList(Request $request) {
        $query = User::join('ranks', 'users.rank_id', '=', 'ranks.id')->select('ranks.name AS rank_name', 'users.*');
        $sort = $request->only(['sort']);

        if ($request->get('name')) {
            $query->where(function ($query) use ($request) {
                $query->where('users.name', 'LIKE', '%'.$request->get('name').'%');
            });
        }
        if ($request->get('rank_id')) {
            $query->where('rank_id', $request->get('rank_id'));
        }

        switch ($sort['sort'] ?? null) {
            default:
                $query->orderBy('ranks.sort', 'DESC')->orderBy('name');
                break;
            case 'alpha':
                $query->orderBy('name');
                break;
            case 'alpha-reverse':
                $query->orderBy('name', 'DESC');
                break;
            case 'rank':
                $query->orderBy('ranks.sort', 'DESC')->orderBy('name');
                break;
            case 'newest':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'ASC');
                break;
        }

        return view('pages.special.user_list', [
            'users' => $query->paginate(30)->appends($request->query()),
            'ranks' => [0 => 'Any Rank'] + Rank::orderBy('ranks.sort', 'DESC')->pluck('name', 'id')->toArray(),
        ]);
    }
}
