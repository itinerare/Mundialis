<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Page\PageProtection;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\TimeDivision;
use App\Models\User\User;
use App\Services\PageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PageController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Subject Page Controller
    |--------------------------------------------------------------------------
    |
    | Handles subject pages.
    |
    */

    /**
     * Shows a page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPage($id) {
        $page = Page::visible(Auth::user() ?? null)->where('id', $id)->first();
        if (!$page) {
            abort(404);
        }

        return view('pages.page', [
            'page' => $page,
        ] + (config('mundialis.subjects.'.$page->category->subject['key'].'.hasDates') ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows a page's revision history.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageHistory(Request $request, $id) {
        $page = Page::visible(Auth::user() ?? null)->where('id', $id)->first();
        if (!$page) {
            abort(404);
        }

        $query = PageVersion::where('page_id', $page->id);
        $sort = $request->only(['sort']);

        if ($request->get('user_id')) {
            $query->where('user_id', $request->get('user_id'));
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

        return view('pages.page_history', [
            'page'     => $page,
            'versions' => $query->paginate(20)->appends($request->query()),
            'users'    => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ] + (config('mundialis.subjects.'.$page->category->subject['key'].'.hasDates') ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows the links to a page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLinksHere(Request $request, $id) {
        $page = Page::visible(Auth::user() ?? null)->where('id', $id)->first();
        if (!$page) {
            abort(404);
        }

        $query = $page->linked()->get()->filter(function ($link) {
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $link->linked->is_visible;
        });

        return view('pages.page_links_here', [
            'page'  => $page,
            'links' => $query->paginate(20)->appends($request->query()),
        ] + (config('mundialis.subjects.'.$page->category->subject['key'].'.hasDates') ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows a specific page version.
     *
     * @param int $pageId
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageVersion($pageId, $id) {
        $page = Page::visible(Auth::user() ?? null)->where('id', $pageId)->first();
        if (!$page) {
            abort(404);
        }
        $version = $page->versions()->where('id', $id)->first();

        return view('pages.page_version', [
            'page'    => $page,
            'version' => $version,
        ] + (config('mundialis.subjects.'.$page->category->subject['key'].'.hasDates') ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows the create page page.
     *
     * @param int $category
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreatePage($category) {
        $category = SubjectCategory::where('id', $category)->first();
        if (!$category) {
            abort(404);
        }

        return view('pages.create_edit_page', [
            'page'     => new Page,
            'category' => $category,
        ] + (config('mundialis.subjects.'.$category->subject['key'].'.editing.placeOptions') ? [
            'placeOptions' => Page::subject('places')->pluck('title', 'id'),
        ] : []) + (config('mundialis.subjects.'.$category->subject['key'].'.editing.chronologyOptions') ? [
            'chronologyOptions' => TimeChronology::pluck('name', 'id'),
        ] : []));
    }

    /**
     * Shows the edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditPage($id) {
        $page = Page::find($id);
        if (!$page || !Auth::user()->canEdit($page)) {
            abort(404);
        }

        return view('pages.create_edit_page', [
            'page'     => $page,
            'category' => $page->category,
        ] + (config('mundialis.subjects.'.$page->category->subject['key'].'.editing.placeOptions') ? [
            'placeOptions' => Page::subject('places')->where('id', '!=', $page->id)->pluck('title', 'id'),
        ] : []) + (config('mundialis.subjects.'.$page->category->subject['key'].'.editing.chronologyOptions') ? [
            'chronologyOptions' => TimeChronology::pluck('name', 'id'),
        ] : []));
    }

    /**
     * Creates or edits a page.
     *
     * @param App\Services\PageManager $service
     * @param int|null                 $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditPage(Request $request, PageManager $service, $id = null) {
        if (!$id) {
            $category = SubjectCategory::where('id', $request->get('category_id'))->first();
        } else {
            if (!Page::where('id', $id)->exists()) {
                abort(404);
            }
            $category = Page::find($id)->category;
        }

        // Form an array of possible answers based on configured fields,
        // Set any un-set toggles (since Laravel does not pass anything on for them),
        // and collect any custom validation rules for the configured fields
        $answerArray = ['title', 'summary', 'description', 'category_id', 'is_visible',
            'parent_id', 'page_tag', 'utility_tag', 'reason', 'is_minor'];
        $validationRules = ($id ? Page::$updateRules : Page::$createRules);

        // Validate against the configured utility tags
        $validationRules = $validationRules + [
            'utility_tags.*' => ['nullable', Rule::in(array_keys(config('mundialis.utility_tags')))],
        ];

        if ($category) {
            foreach ($category->formFields as $key=>$field) {
                $answerArray[] = $key;
                if (isset($field['rules'])) {
                    $validationRules[$key] = $field['rules'];
                }
                if ($field['type'] == 'checkbox' && !isset($request[$key])) {
                    $request[$key] = 0;
                }
            }
            if ($category->subject['key'] == 'time') {
                foreach (['start', 'end'] as $segment) {
                    foreach ((new TimeDivision)->dateFields() as $key=>$field) {
                        $answerArray[] = 'date_'.$segment.'_'.$key;
                        if (isset($field['rules'])) {
                            $validationRules['date_'.$segment.'_'.$key] = $field['rules'];
                        }
                        if ($field['type'] == 'checkbox' && !isset($request['date_'.$segment.'_'.$key])) {
                            $request['date_'.$segment.'_'.$key] = 0;
                        }
                    }
                }
            }
            if ($category->subject['key'] == 'people') {
                $answerArray[] = 'people_name';
                foreach (['birth', 'death'] as $segment) {
                    $answerArray[] = $segment.'_place_id';
                    $answerArray[] = $segment.'_chronology_id';
                    foreach ((new TimeDivision)->dateFields() as $key=>$field) {
                        $answerArray[] = $segment.'_'.$key;
                    }
                }
            }
        }

        $request->validate($validationRules);
        $data = $request->only($answerArray);

        if ($id && $service->updatePage(Page::find($id), $data, Auth::user())) {
            flash('Page updated successfully.')->success();
        } elseif (!$id && $page = $service->createPage($data, Auth::user())) {
            flash('Page created successfully.')->success();

            return redirect()->to($page->url);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Shows a page's protection settings.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getProtectPage(Request $request, $id) {
        $page = Page::where('id', $id)->first();
        if (!$page) {
            abort(404);
        }

        $query = PageProtection::where('page_id', $page->id);
        $sort = $request->only(['sort']);

        if ($request->get('user_id')) {
            $query->where('user_id', $request->get('user_id'));
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

        return view('pages.page_protection', [
            'page'        => $page,
            'protections' => $query->paginate(20)->appends($request->query()),
            'users'       => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ] + (config('mundialis.subjects.'.$page->category->subject['key'].'.hasDates') ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Updates a page's protection.
     *
     * @param App\Services\PageManager $service
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postProtectPage(Request $request, PageManager $service, $id) {
        if ($id && $service->protectPage(Page::find($id), Auth::user(), $request->only(['reason', 'is_protected']))) {
            flash('Page protection updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }

            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     * Gets the page move page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMovePage($id) {
        $page = Page::find($id);
        if (!$page || !Auth::user()->canEdit($page)) {
            abort(404);
        }

        // Collect categories and information and group them
        $groupedCategories = SubjectCategory::orderBy('sort', 'DESC')->where('id', '!=', $page->category_id)->get()->keyBy('id')->groupBy(function ($category) {
            return $category->subject['name'];
        }, $preserveKeys = true)->toArray();

        // Collect subjects and information
        $orderedSubjects = collect(config('mundialis.subjects'))->filter(function ($subject) use ($groupedCategories) {
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

        return view('pages.page_move', [
            'page'       => $page,
            'categories' => $sortedCategories,
        ] + (config('mundialis.subjects.'.$page->category->subject['key'].'.hasDates') ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Moves a page to a given category.
     *
     * @param App\Services\PageManager $service
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMovePage(Request $request, PageManager $service, $id) {
        if ($id && $service->movePage(Page::find($id), SubjectCategory::find($request->get('category_id')), Auth::user(), $request->get('reason'))) {
            flash('Page moved successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }

            return redirect()->back();
        }

        return redirect()->to(Page::find($id)->url);
    }

    /**
     * Gets the page reset modal.
     *
     * @param int $id
     * @param int $pageId
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getResetPage($pageId, $id) {
        $page = Page::find($pageId);
        if ($page && !Auth::user()->canEdit($page)) {
            abort(404);
        }
        $version = $page?->versions()->where('id', $id)->first();

        return view('pages._reset_page', [
            'page'    => $page,
            'version' => $version,
        ]);
    }

    /**
     * Resets a page to a given version.
     *
     * @param App\Services\PageManager $service
     * @param int                      $pageId
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postResetPage(Request $request, PageManager $service, $pageId, $id) {
        $page = Page::find($pageId);
        $version = $page?->versions()->where('id', $id)->first();

        if ($id && $service->resetPage($page, $version, Auth::user(), $request->get('reason'))) {
            flash('Page reset successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }

            return redirect()->back();
        }

        return redirect()->to(Page::find($pageId)->url);
    }

    /**
     * Gets the page deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletePage($id) {
        $page = Page::find($id);
        if ($page && !Auth::user()->canEdit($page)) {
            abort(404);
        }

        return view('pages._delete_page', [
            'page' => $page,
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param App\Services\PageManager $service
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeletePage(Request $request, PageManager $service, $id) {
        if ($id && $service->deletePage(Page::find($id), Auth::user(), $request->get('reason'))) {
            flash('Page deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }

            return redirect()->back();
        }

        return redirect()->to('/');
    }
}
