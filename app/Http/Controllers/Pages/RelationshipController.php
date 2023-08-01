<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Page\PageRelationship;
use App\Models\Subject\TimeDivision;
use App\Services\RelationshipManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RelationshipController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Relationship Controller
    |--------------------------------------------------------------------------
    |
    | Handles page relationships.
    |
    */

    /**
     * Shows a page's relationships.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageRelationships(Request $request, $id) {
        $page = Page::visible(Auth::check() ? Auth::user() : null)->where('id', $id)->first();
        if (!$page) {
            abort(404);
        }

        $query = $page->relationships()->get()->filter(function ($relationship) {
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $relationship->pageTwo->is_visible;
        });

        $query = $query->concat($page->related()->get()->filter(function ($related) {
            if (Auth::check() && Auth::user()->canWrite) {
                return 1;
            }

            return $related->pageOne->is_visible;
        }));

        $sort = $request->only(['sort']);

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'newest':
                    $query = $query->sortByDesc('id');
                    break;
                case 'oldest':
                    $query = $query->sortBy('id');
                    break;
            }
        } else {
            $query = $query->sortBy('id');
        }

        return view('pages.relationships.relationships', [
            'page'          => $page,
            'relationships' => $query->paginate(20)->appends($request->query()),
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows a page's family tree.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageFamilyTree($id) {
        $page = Page::visible(Auth::check() ? Auth::user() : null)->where('id', $id)->first();
        if (!$page) {
            abort(404);
        }
        if (!$page->personRelations()) {
            abort(404);
        }

        return view('pages.relationships.family_tree', [
            'page' => $page,
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows the create relationship modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateRelationship($id) {
        $page = Page::where('id', $id)->first();
        if (!$page) {
            abort(404);
        }
        if (!Auth::user()->canEdit($page)) {
            abort(404);
        }

        return view('pages.relationships._create_edit_relationship', [
            'relationship'        => new PageRelationship,
            'page'                => $page,
            'pageOptions'         => Page::where('id', '!=', $page->id)->get()->filter(function ($option) use ($page) {
                return $option->category->subject['key'] == $page->category->subject['key'];
            })->pluck('title', 'id'),
            'relationshipOptions' => Config::get('mundialis.'.$page->category->subject['key'].'_relationships'),
        ]);
    }

    /**
     * Shows the edit relationship modal.
     *
     * @param int $pageId
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditRelationship($pageId, $id) {
        $page = Page::where('id', $pageId)->first();
        if (!$page) {
            abort(404);
        }
        if (!Auth::user()->canEdit($page)) {
            abort(404);
        }
        $relationship = PageRelationship::where('id', $id)->first();
        if (!$relationship) {
            abort(404);
        }

        return view('pages.relationships._create_edit_relationship', [
            'relationship'        => $relationship,
            'page'                => $page,
            'pageOptions'         => Page::where('id', '!=', $page->id)->get()->filter(function ($option) use ($page) {
                return $option->category->subject['key'] == $page->category->subject['key'];
            })->pluck('title', 'id'),
            'relationshipOptions' => Config::get('mundialis.'.$page->category->subject['key'].'_relationships'),
        ]);
    }

    /**
     * Creates or edits a relationship.
     *
     * @param App\Services\RelationshipManager $service
     * @param int                              $pageId
     * @param int                              $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditRelationship(Request $request, RelationshipManager $service, $pageId, $id = null) {
        $request->validate(PageRelationship::$rules);

        $data = $request->only([
            'page_one_id', 'page_two_id',
            'type_one', 'type_one_info', 'details_one',
            'type_two', 'type_two_info', 'details_two',
        ]);

        $page = Page::where('id', $pageId)->first();
        if (!Auth::user()->canEdit($page)) {
            abort(404);
        }
        if (!$page) {
            abort(404);
        }

        if ($id && $service->updatePageRelationship(PageRelationship::find($id), $data, Auth::user())) {
            flash('Relationship updated successfully.')->success();
        } elseif (!$id && $relationship = $service->createPageRelationship($data, $page, Auth::user())) {
            flash('Relationship created successfully.')->success();

            return redirect()->to('pages/'.$page->id.'/relationships');
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the relationship deletion modal.
     *
     * @param int $pageId
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteRelationship($pageId, $id) {
        $page = Page::where('id', $pageId)->first();
        if (!$page) {
            abort(404);
        }
        if (!Auth::user()->canEdit($page)) {
            abort(404);
        }
        $relationship = PageRelationship::where('id', $id)->first();
        if (!$relationship) {
            abort(404);
        }

        return view('pages.relationships._delete_relationship', [
            'relationship' => $relationship,
            'page'         => $page,
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param App\Services\RelationshipManager $service
     * @param int                              $pageId
     * @param int                              $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteRelationship(Request $request, RelationshipManager $service, $pageId, $id) {
        if ($id && $service->deletePageRelationship(PageRelationship::find($id), Auth::user())) {
            flash('Image deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->to('pages/'.$pageId.'/relationships');
    }
}
