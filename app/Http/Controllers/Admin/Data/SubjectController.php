<?php

namespace App\Http\Controllers\Admin\Data;

use Config;
use Auth;
use App\Models\Subject\SubjectTemplate;
use App\Models\Subject\SubjectCategory;

use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;

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
            'categories' => SubjectCategory::where('subject', $subject['key'])->orderBy('sort', 'DESC')->get()
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
        is_numeric($subject) ? $request->validate(SubjectCategory::$updateRules + SubjectTemplate::$rules) : $request->validate(SubjectCategory::$createRules + SubjectTemplate::$rules);
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
        $category = SubjectCategory::find($id); $subject = $category->subject;
        if($id && $service->deleteCategory($category)) {
            flash('Category deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
            return redirect()->back();
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
        SPECIALIZED - TIME
    *******************************************************************************/

    /**
     * Shows the divisions page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getTimeDivisions()
    {
        return view('admin.subjects.time_divisions', [
            'divisions' => TimeDivision::orderBy('sort', 'DESC')->get()
        ]);
    }

    /**
     * Edits subject template data.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditDivisions(Request $request, SubjectService $service)
    {
        $request->validate(TimeDivision::$rules);

        $data = $request->only([
            'name', 'abbreviation', 'unit', 'sort'
        ]);
        if($service->editTimeDivisions($data, Auth::user())) {
            flash('Divisions updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Shows the chronlogy index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getTimeChronology()
    {
        return view('admin.subjects.time_chronology', [
            'chronologies' => TimeChronology::orderBy('sort', 'DESC')->get()
        ]);
    }

    /**
     * Shows the create chronology page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateChronology()
    {
        return view('admin.subjects.create_edit_chronology', [
            'chronology' => new TimeChronology,
            'chronologyOptions' => TimeChronology::pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Shows the edit chronology page.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditChronology($id)
    {
        $chronology = TimeChronology::find($id);
        if(!$chronology) abort(404);

        return view('admin.subjects.create_edit_chronology', [
            'chronology' => $chronology,
            'chronologyOptions' => TimeChronology::where('id', '!=', $chronology->id)->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Creates or edits a chronology.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param  int|null                     $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditChronology(Request $request, SubjectService $service, $id = null)
    {
        $id ? $request->validate(TimeChronology::$updateRules) : $request->validate(TimeChronology::$createRules);
        $data = $request->only([
            'name', 'abbreviation', 'description', 'parent_id'
        ]);
        if($id && $service->updateChronology(TimeChronology::find($id), $data, Auth::user())) {
            flash('Chronology updated successfully.')->success();
        }
        else if (!$id && $chronology = $service->createChronology($data, Auth::user())) {
            flash('Chronology created successfully.')->success();
            return redirect()->to('admin/data/time/chronology/edit/'.$chronology->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the chronology deletion modal.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteChronology($id)
    {
        $chronology = TimeChronology::find($id);

        return view('admin.subjects._delete_chronology', [
            'chronology' => $chronology
        ]);
    }

    /**
     * Deletes a chronology.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @param  int                          $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteChronology(Request $request, SubjectService $service, $id)
    {
        if($id && $service->deleteChronology(TimeChronology::find($id))) {
            flash('Chronology deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/time/chronology');
    }

    /**
     * Sorts categories.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\SubjectService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortChronology(Request $request, SubjectService $service)
    {
        if($service->sortChronology($request->get('sort'))) {
            flash('Chronology order updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
}
