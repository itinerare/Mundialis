<?php

namespace App\Http\Controllers\Admin\Data;

use Config;
use Auth;
use App\Models\Subject\SubjectTemplate;
use App\Models\Subject\SubjectCategory;

use App\Services\SubjectService;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin / Gallery Data Controller
    |--------------------------------------------------------------------------
    |
    | Handles creation/editing of gallery data.
    |
    */

    /******************************************************************************
        GENERIC
    *******************************************************************************/

    /**
     * Shows the project index.
     *
     * @param string     $subject
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSubjectIndex($subject)
    {
        if(null == Config::get('mundialis.subjects.'.$subject)) abort(404);
        $subjectKey = $subject; $subject = Config::get('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        return view('admin.subjects.index', [
            'subject' => $subject,
            'categories' => SubjectCategory::where('subject', $subject['key'])->get()
        ]);
    }

    /**
     * Shows the subject template editing page.
     *
     * @param string     $subject
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditTemplate($subject)
    {
        if(null == Config::get('mundialis.subjects.'.$subject)) abort(404);
        $subjectKey = $subject; $subject = Config::get('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        $template = SubjectTemplate::where('subject', $subject['key'])->first();

        return view('admin.subjects.template', [
            'subject' => $subject,
            'template' => $template ? $template : new SubjectTemplate
        ]);
    }

    /**
     * Edits subject template data.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param string                        $subject
     * @param  int|null                     $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditTemplate(Request $request, SubjectService $service, $subject, $id = null)
    {
        $request->validate(SubjectTemplate::$rules);

        $data = $request->only([
            'section_key', 'section_name', 'cascade_template',
            'infobox_key', 'infobox_type', 'infobox_label', 'infobox_rules', 'infobox_choices', 'infobox_value', 'infobox_help', 'widget_key', 'widget_section',
            'field_key', 'field_type', 'field_label', 'field_rules', 'field_choices', 'field_value', 'field_help', 'field_is_subsection', 'field_section'
        ]);
        if($service->editTemplate($subject, $data, Auth::user())) {
            flash('Template updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Shows the create category page.
     *
     * @param string     $subject
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateCategory($subject)
    {
        if(null == Config::get('mundialis.subjects.'.$subject)) abort(404);
        $subjectKey = $subject; $subject = Config::get('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        return view('admin.subjects.create_edit_category', [
            'subject' => $subject,
            'category' => new SubjectCategory,
            'categoryOptions' => SubjectCategory::where('subject', $subject['key'])->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Shows the edit category page.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditCategory($id)
    {
        $category = SubjectCategory::find($id);
        if(!$category) abort(404);

        if(null == Config::get('mundialis.subjects.'.$category->subject)) abort(404);
        $subject = Config::get('mundialis.subjects.'.$category->subject); $subject['key'] = $category->subject;

        return view('admin.subjects.create_edit_category', [
            'subject' => $subject,
            'category' => $category,
            'categoryOptions' => SubjectCategory::where('subject', $subject['key'])->where('id', '!=', $category->id)->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Creates or edits a category.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param string|int                    $subject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditCategory(Request $request, SubjectService $service, $subject)
    {
        is_numeric($subject) ? $request->validate(SubjectCategory::$updateRules + SubjectCategory::$templateRules) : $request->validate(SubjectCategory::$createRules + SubjectCategory::$templateRules);
        $data = $request->only([
            'name', 'description', 'parent_id', 'populate_template', 'cascade_template', 'cascade_recursively',
            'section_key', 'section_name',
            'infobox_key', 'infobox_type', 'infobox_label', 'infobox_rules', 'infobox_choices', 'infobox_value', 'infobox_help', 'widget_key', 'widget_section',
            'field_key', 'field_type', 'field_label', 'field_rules', 'field_choices', 'field_value', 'field_help', 'field_is_subsection', 'field_section'
        ]);
        if(is_numeric($subject) && $service->updateCategory(SubjectCategory::find($subject), $data, Auth::user())) {
            flash('Category updated successfully.')->success();
        }
        else if (!is_numeric($subject) && $category = $service->createCategory($data, Auth::user(), $subject)) {
            flash('Category created successfully.')->success();
            return redirect()->to('admin/data/categories/edit/'.$category->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the category deletion modal.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteCategory($id)
    {
        $category = SubjectCategory::find($id);

        return view('admin.subjects._delete_category', [
            'category' => $category
        ]);
    }

    /**
     * Deletes a category.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param  int                          $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCategory(Request $request, SubjectService $service, $id)
    {
        if($id && $service->deleteCategory(SubjectCategory::find($id), $subject)) {
            flash('Category deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/'.$subject);
    }

    /**
     * Sorts categories.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param string                        $subject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortCategory(Request $request, SubjectService $service, $subject)
    {
        if($service->sortCategory($request->get('sort'), $subject)) {
            flash('Category order updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /******************************************************************************
        SPECIALIZED
    *******************************************************************************/

}
