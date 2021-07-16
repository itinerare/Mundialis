<?php

namespace App\Http\Controllers\Pages;

use Auth;
use Config;

use App\Models\Subject\SubjectCategory;
use App\Models\Page\Page;

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
     * Shows the subject page index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('pages.index', [

        ]);
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
            'placeOptions' => Page::visible()->subject('places')->pluck('title', 'id')
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
            'placeOptions' => Page::visible()->subject('places')->where('id', '!=', $page->id)->pluck('title', 'id')
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

        // Form an array of possible answers based on configured fields,
        // Set any un-set toggles (since Laravel does not pass anything on for them),
        // and collect any custom validation rules for the configured fields
        $answerArray = ['title', 'summary', 'category_id', 'is_visible']; $validationRules = ($id ? Page::$updateRules : Page::$createRules);
        foreach(($id ? Page::find($id)->category->formFields : $category->formFields) as $key=>$field) {
            $answerArray[] = $key;
            if(isset($field['rules'])) $validationRules[$key] = $field['rules'];
            if($field['type'] == 'checkbox' && !isset($request[$key])) $request[$key] = 0;
        }

        $request->validate($validationRules);

        $data = $request->only($answerArray);

        if($id && $service->updatePage(Page::find($id), $data, Auth::user())) {
            flash('Page updated successfully.')->success();
        }
        else if (!$id && $page = $service->createPage($data, Auth::user())) {
            flash('Page created successfully.')->success();
            return redirect()->to('pages/edit/'.$page->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
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
        if($id && $service->deletePage(Page::find($id))) {
            flash('Page deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('pages');
    }

}
