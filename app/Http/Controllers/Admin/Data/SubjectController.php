<?php

namespace App\Http\Controllers\Admin\Data;

use Config;
use Auth;
use App\Models\Subject\SubjectTemplate;
//use App\Models\Subject\SubjectCategory;

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
        $name = null !== Config::get('mundialis.subjects.'.$subject.'.name') ? Config::get('mundialis.subjects.'.$subject.'.name') : ucfirst($subject);

        return view('admin.subjects.index', [
            'subject' => $subject,
            'subjectName' => $name,
            'categories' => []
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
        $name = null !== Config::get('mundialis.subjects.'.$subject.'.name') ? Config::get('mundialis.subjects.'.$subject.'.name') : ucfirst($subject);

        $template = SubjectTemplate::where('subject', $subject)->first();

        return view('admin.subjects.template', [
            'subject' => $subject,
            'subjectName' => $name,
            'template' => $template ? $template : new SubjectTemplate,
            'fieldTypes' => ['text' => 'Text', 'textarea' => 'Textbox', 'number' => 'Number', 'checkbox' => 'Checkbox/Toggle', 'choice' => 'Choose One', 'multiple' => 'Choose Multiple']
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
            'section_key', 'section_name',
            'infobox_key', 'infobox_type', 'infobox_label', 'infobox_rules', 'infobox_choices', 'infobox_value', 'infobox_help',
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
        $name = null !== Config::get('mundialis.subjects.'.$subject.'.name') ? Config::get('mundialis.subjects.'.$subject.'.name') : ucfirst($subject);

        return view('admin.subjects.create_edit_category', [
            'subject' => $subject,
            'subjectName' => $name,
            'category' => new SubjectCategory
        ]);
    }

    /**
     * Shows the edit category page.
     *
     * @param string     $subject
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditCategory($subject, $id)
    {
        if(null == Config::get('mundialis.subjects.'.$subject)) abort(404);
        $name = null !== Config::get('mundialis.subjects.'.$subject.'.name') ? Config::get('mundialis.subjects.'.$subject.'.name') : ucfirst($subject);

        $project = Project::find($id);
        if(!$project) abort(404);
        return view('admin.subjects.create_edit_project', [
            'subject' => $subject,
            'subjectName' => $name,
            'category' => $category
        ]);
    }

    /**
     * Creates or edits a category.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param string                        $subject
     * @param  int|null                     $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditCategory(Request $request, SubjectService $service, $subject, $id = null)
    {
        $id ? $request->validate(Project::$updateRules) : $request->validate(Project::$createRules);
        $data = $request->only([
            'name', 'description', 'is_visible'
        ]);
        if($id && $service->updateProject(Project::find($id), $data, Auth::user(), $subject)) {
            flash('Category updated successfully.')->success();
        }
        else if (!$id && $project = $service->createProject($data, Auth::user(), $subject)) {
            flash('Category created successfully.')->success();
            return redirect()->to('admin/data/projects/edit/'.$project->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the category deletion modal.
     *
     * @param string     $subject
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteCategory($subject, $id)
    {
        if(null == Config::get('mundialis.subjects.'.$subject)) abort(404);
        $category = SubjectCategory::find($id);

        return view('admin.subjects._delete_category', [
            'subject' => $subject,
            'category' => $category,
        ]);
    }

    /**
     * Deletes a category.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param string                        $subject
     * @param  int                          $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCategory(Request $request, SubjectService $service, $subject, $id)
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
