<?php

namespace App\Http\Controllers\Pages;

use Auth;
use Config;

use App\Models\User\User;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;

use App\Services\PageManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Subject Page Controller
    |--------------------------------------------------------------------------
    |
    | Handles subject pages.
    |
    */

    /**
     * Shows the page index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageIndex()
    {
        return view('pages.index', [

        ]);
    }

    /**
     * Shows a subject's category index.
     *
     * @param  string                    $subject
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSubject($subject, Request $request)
    {
        $subjectKey = $subject; $subject = Config::get('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        return view('pages.subject', [
            'subject' => $subject,
            'categories' => SubjectCategory::where('subject', $subject['key'])->whereNull('parent_id')->orderBy('sort', 'DESC')->paginate(20)->appends($request->query())
        ]);
    }

    /**
     * Shows a category's page.
     *
     * @param  string                    $subject
     * @param  int                       $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSubjectCategory($subject, $id, Request $request)
    {
        $category = SubjectCategory::where('id', $id)->first();
        if(!$category) abort(404);
        if($category->subject['key'] != $subject) abort(404);

        $query = $category->pages()->visible(Auth::check() ? Auth::user() : null);
        $sort = $request->only(['sort']);

        if($request->get('title')) $query->where(function($query) use ($request) {
            $query->where('pages.title', 'LIKE', '%' . $request->get('title') . '%');
        });

        if($request->get('tags'))
            foreach($request->get('tags') as $tag)
                $query->whereIn('id', PageTag::tag()->where('tag', $tag)->pluck('page_id')->toArray());

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

        return view('pages.category', [
            'category' => $category,
            'pages' => $query->paginate(20)->appends($request->query()),
            'tags' => PageTag::tag()->pluck('tag', 'tag')->unique(),
            'dateHelper' => new TimeDivision
        ]);
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

        return view('pages.special_all', [
            'pages' => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'dateHelper' => new TimeDivision
        ]);
    }

    /**
     * Shows a page.
     *
     * @param  int        $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPage($id)
    {
        $page = Page::visible(Auth::check() ? Auth::user() : null)->where('id', $id)->first();
        if(!$page) abort(404);

        return view('pages.page', [
            'page' => $page
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision
        ] : []));
    }

    /**
     * Shows a page's revision history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageHistory(Request $request, $id)
    {
        $page = Page::visible(Auth::check() ? Auth::user() : null)->where('id', $id)->first();
        if(!$page) abort(404);

        $query = PageVersion::where('page_id', $page->id);
        $sort = $request->only(['sort']);

        if($request->get('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if(isset($sort['sort']))
        {
            switch($sort['sort']) {
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        }
        else $query->orderBy('created_at', 'DESC');

        return view('pages.page_history', [
            'page' => $page,
            'versions' => $query->paginate(20)->appends($request->query()),
            'users' => User::query()->orderBy('name')->pluck('name', 'id')->toArray()
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision
        ] : []));
    }

    /**
     * Shows a specific page version.
     *
     * @param  int        $pageId
     * @param  int        $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageVersion($pageId, $id)
    {
        $page = Page::visible(Auth::check() ? Auth::user() : null)->where('id', $pageId)->first();
        if(!$page) abort(404);
        $version = $page->versions()->where('id', $id)->first();

        return view('pages.page_version', [
            'page' => $page,
            'version' => $version
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision
        ] : []));
    }

    /**
     * Shows the create page page.
     *
     * @param  string            $subject
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreatePage($category)
    {
        $category = SubjectCategory::where('id', $category)->first();
        if(!$category) abort(404);

        return view('pages.create_edit_page', [
            'page' => new Page,
            'category' => $category
        ] + ($category->subject['key'] == 'places' ? [
            'placeOptions' => Page::subject('places')->pluck('title', 'id')
        ] : []) + ($category->subject['key'] == 'time' ? [
            'chronologyOptions' => TimeChronology::pluck('name', 'id')
        ] : []) + ($category->subject['key'] == 'people' ? [
            'placeOptions' => Page::subject('places')->pluck('title', 'id'),
            'chronologyOptions' => TimeChronology::pluck('name', 'id')
        ] : []));
    }

    /**
     * Shows the edit page.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditPage($id)
    {
        $page = Page::find($id);
        if(!$page) abort(404);

        return view('pages.create_edit_page', [
            'page' => $page,
            'category' => $page->category
        ] + ($page->category->subject['key'] == 'places' ? [
            'placeOptions' => Page::subject('places')->where('id', '!=', $page->id)->pluck('title', 'id')
        ] : []) + ($page->category->subject['key'] == 'time' ? [
            'chronologyOptions' => TimeChronology::pluck('name', 'id')
        ] : []) + ($page->category->subject['key'] == 'people' ? [
            'placeOptions' => Page::subject('places')->pluck('title', 'id'),
            'chronologyOptions' => TimeChronology::pluck('name', 'id')
        ] : []));
    }

    /**
     * Creates or edits a page.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\PageManager     $service
     * @param  int|null                     $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditPage(Request $request, PageManager $service, $id = null)
    {
        if(!$id) $category = SubjectCategory::where('id', $request->get('category_id'))->first();
        else $category = Page::find($id)->category;

        // Form an array of possible answers based on configured fields,
        // Set any un-set toggles (since Laravel does not pass anything on for them),
        // and collect any custom validation rules for the configured fields
        $answerArray = ['title', 'summary', 'description', 'category_id', 'is_visible',
        'parent_id', 'page_tag', 'utility_tag', 'reason', 'is_minor'];
        $validationRules = ($id ? Page::$updateRules : Page::$createRules);
        foreach($category->formFields as $key=>$field) {
            $answerArray[] = $key;
            if(isset($field['rules'])) $validationRules[$key] = $field['rules'];
            if($field['type'] == 'checkbox' && !isset($request[$key])) $request[$key] = 0;
        }
        if($category->subject['key'] == 'time')
            foreach(['start', 'end'] as $segment) {
                foreach((new TimeDivision)->dateFields() as $key=>$field) {
                    $answerArray[] = 'date_'.$segment.'_'.$key;
                    if(isset($field['rules'])) $validationRules['date_'.$segment.'_'.$key] = $field['rules'];
                    if($field['type'] == 'checkbox' && !isset($request['date_'.$segment.'_'.$key])) $request['date_'.$segment.'_'.$key] = 0;
                }
            }
        if($category->subject['key'] == 'people') {
            $answerArray[] = 'people_name';
            foreach(['birth', 'death'] as $segment) {
                $answerArray[] = $segment.'_place_id';
                $answerArray[] = $segment.'_chronology_id';
                foreach((new TimeDivision)->dateFields() as $key=>$field) {
                    $answerArray[] = $segment.'_'.$key;
                }
            }
        }

        $request->validate($validationRules);
        $data = $request->only($answerArray);

        if($id && $service->updatePage(Page::find($id), $data, Auth::user())) {
            flash('Page updated successfully.')->success();
        }
        else if (!$id && $page = $service->createPage($data, Auth::user())) {
            flash('Page created successfully.')->success();
            return redirect()->to($page->url);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the page reset modal.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getResetPage($pageId, $id)
    {
        $page = Page::find($pageId);
        $version = $page->versions()->where('id', $id)->first();

        return view('pages._reset_page', [
            'page' => $page,
            'version' => $version
        ]);
    }

    /**
     * Resets a page to a given version.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\PageManager     $service
     * @param  int                          $pageId,
     * @param  int                          $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postResetPage(Request $request, PageManager $service, $pageId, $id)
    {
        if($id && $service->resetPage(Page::find($pageId), PageVersion::find($id), Auth::user(), $request->get('reason'))) {
            flash('Page reset successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
            return redirect()->back();
        }
        return redirect()->to(Page::find($pageId)->url);
    }

    /**
     * Gets the page deletion modal.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletePage($id)
    {
        $page = Page::find($id);

        return view('pages._delete_page', [
            'page' => $page
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\PageManager     $service
     * @param  int                          $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeletePage(Request $request, PageManager $service, $id)
    {
        if($id && $service->deletePage(Page::find($id), Auth::user(), $request->get('reason'))) {
            flash('Page deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
            return redirect()->back();
        }
        return redirect()->to('/');
    }

}
