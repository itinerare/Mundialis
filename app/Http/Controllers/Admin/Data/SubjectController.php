<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\Subject\LexiconCategory;
use App\Models\Subject\LexiconSetting;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\SubjectTemplate;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\TimeDivision;
use App\Services\SubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller {
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
     * @param string $subject
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSubjectIndex($subject) {
        $subjectKey = $subject;
        $subject = config('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        return view('admin.subjects.subject', [
            'subject'    => $subject,
            'categories' => SubjectCategory::where('subject', $subject['key'])->orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the subject template editing page.
     *
     * @param string $subject
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditTemplate($subject) {
        $subjectKey = $subject;
        $subject = config('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        $template = SubjectTemplate::where('subject', $subject['key'])->first();

        // Fallback for testing purposes
        if (isset($template->data) && !is_array($template->data)) {
            $template->data = json_decode($template->data, true);
        }

        return view('admin.subjects.template', [
            'subject'  => $subject,
            'template' => $template ? $template : new SubjectTemplate,
        ]);
    }

    /**
     * Edits subject template data.
     *
     * @param App\Services\SubjectService $service
     * @param string                      $subject
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditTemplate(Request $request, SubjectService $service, $subject, $id = null) {
        $request->validate(SubjectTemplate::$rules);

        $data = $request->only([
            'section_key', 'section_name', 'section_subject', 'cascade_template',
            'infobox_key', 'infobox_type', 'infobox_label', 'infobox_rules', 'infobox_choices', 'infobox_value', 'infobox_help',
            'field_key', 'field_type', 'field_label', 'field_rules', 'field_choices', 'field_value', 'field_help', 'field_is_subsection', 'field_section',
        ]);
        if ($service->editTemplate($subject, $data, Auth::user())) {
            flash('Template updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Shows the create category page.
     *
     * @param string $subject
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateCategory($subject) {
        $subjectKey = $subject;
        $subject = config('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        return view('admin.subjects.create_edit_category', [
            'subject'         => $subject,
            'category'        => new SubjectCategory,
            'categoryOptions' => SubjectCategory::where('subject', $subject['key'])->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the edit category page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditCategory($id) {
        $category = SubjectCategory::find($id);
        if (!$category) {
            abort(404);
        }

        $subject = $category->subject;

        return view('admin.subjects.create_edit_category', [
            'subject'         => $subject,
            'category'        => $category,
            'categoryOptions' => SubjectCategory::where('subject', $subject['key'])->where('id', '!=', $category->id)->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Creates or edits a category.
     *
     * @param App\Services\SubjectService $service
     * @param int|string                  $subject
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditCategory(Request $request, SubjectService $service, $subject) {
        is_numeric($subject) ? $request->validate(SubjectCategory::$updateRules + SubjectTemplate::$rules) : $request->validate(SubjectCategory::$createRules + SubjectTemplate::$rules);
        $data = $request->only([
            'name', 'description', 'image', 'remove_image', 'summary', 'parent_id', 'populate_template', 'cascade_template', 'cascade_recursively',
            'section_key', 'section_name', 'section_subject',
            'infobox_key', 'infobox_type', 'infobox_label', 'infobox_rules', 'infobox_choices', 'infobox_value', 'infobox_help',
            'field_key', 'field_type', 'field_label', 'field_rules', 'field_choices', 'field_value', 'field_help', 'field_is_subsection', 'field_section',
        ]);
        if (is_numeric($subject) && $service->updateCategory(SubjectCategory::find($subject), $data, Auth::user())) {
            flash('Category updated successfully.')->success();
        } elseif (!is_numeric($subject) && $category = $service->createCategory($data, Auth::user(), $subject)) {
            flash('Category created successfully.')->success();

            return redirect()->to('admin/data/categories/edit/'.$category->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the category deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteCategory($id) {
        $category = SubjectCategory::find($id);

        return view('admin.subjects._delete_category', [
            'category' => $category,
        ]);
    }

    /**
     * Deletes a category.
     *
     * @param App\Services\SubjectService $service
     * @param int                         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCategory(Request $request, SubjectService $service, $id) {
        $category = SubjectCategory::find($id);
        $subject = $category?->subject;
        if ($id && $service->deleteCategory($category, Auth::user())) {
            flash('Category deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }

            return redirect()->back();
        }

        return redirect()->to('admin/data/'.$subject['key']);
    }

    /**
     * Sorts categories.
     *
     * @param App\Services\SubjectService $service
     * @param string                      $subject
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortCategory(Request $request, SubjectService $service, $subject) {
        if ($service->sortCategory($request->get('sort'), $subject)) {
            flash('Category order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
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
    public function getTimeDivisions() {
        return view('admin.subjects.time_divisions', [
            'divisions' => TimeDivision::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Edits time divisions.
     *
     * @param App\Services\SubjectService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditDivisions(Request $request, SubjectService $service) {
        $request->validate(TimeDivision::$rules);

        $data = $request->only([
            'id', 'name', 'abbreviation', 'unit', 'use_for_dates', 'sort',
        ]);
        if ($service->editTimeDivisions($data, Auth::user())) {
            flash('Divisions updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Shows the chronology index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getTimeChronology() {
        return view('admin.subjects.time_chronology', [
            'chronologies' => TimeChronology::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create chronology page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateChronology() {
        return view('admin.subjects.create_edit_chronology', [
            'chronology'        => new TimeChronology,
            'chronologyOptions' => TimeChronology::pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the edit chronology page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditChronology($id) {
        $chronology = TimeChronology::find($id);
        if (!$chronology) {
            abort(404);
        }

        return view('admin.subjects.create_edit_chronology', [
            'chronology'        => $chronology,
            'chronologyOptions' => TimeChronology::where('id', '!=', $chronology->id)->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Creates or edits a chronology.
     *
     * @param App\Services\SubjectService $service
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditChronology(Request $request, SubjectService $service, $id = null) {
        $id ? $request->validate(TimeChronology::$updateRules) : $request->validate(TimeChronology::$createRules);
        $data = $request->only([
            'name', 'abbreviation', 'description', 'parent_id',
        ]);
        if ($id && $service->updateChronology(TimeChronology::find($id), $data, Auth::user())) {
            flash('Chronology updated successfully.')->success();
        } elseif (!$id && $chronology = $service->createChronology($data, Auth::user())) {
            flash('Chronology created successfully.')->success();

            return redirect()->to('admin/data/time/chronology/edit/'.$chronology->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the chronology deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteChronology($id) {
        $chronology = TimeChronology::find($id);

        return view('admin.subjects._delete_chronology', [
            'chronology' => $chronology,
        ]);
    }

    /**
     * Deletes a chronology.
     *
     * @param App\Services\SubjectService $service
     * @param int                         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteChronology(Request $request, SubjectService $service, $id) {
        if ($id && $service->deleteChronology(TimeChronology::find($id))) {
            flash('Chronology deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->to('admin/data/time/chronology');
    }

    /**
     * Sorts chronologies.
     *
     * @param App\Services\SubjectService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortChronology(Request $request, SubjectService $service) {
        if ($service->sortChronology($request->get('sort'))) {
            flash('Chronology order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /******************************************************************************
        SPECIALIZED - LANGUAGE
    *******************************************************************************/

    /**
     * Shows the lexicon settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLexiconSettings() {
        return view('admin.subjects.lang_settings', [
            'parts' => LexiconSetting::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Edits lexicon settings.
     *
     * @param App\Services\SubjectService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditLexiconSettings(Request $request, SubjectService $service) {
        $request->validate(LexiconSetting::$rules);

        $data = $request->only([
            'id', 'name', 'abbreviation', 'sort',
        ]);
        if ($service->editLexiconSettings($data, Auth::user())) {
            flash('Lexicon settings updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Shows the lexicon category index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLexiconCategories() {
        return view('admin.subjects.lang_categories', [
            'categories' => LexiconCategory::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create lexicon category page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateLexiconCategory() {
        return view('admin.subjects.create_edit_lang_category', [
            'category'        => new LexiconCategory,
            'categoryOptions' => LexiconCategory::pluck('name', 'id')->toArray(),
            'classes'         => LexiconSetting::all(),
        ]);
    }

    /**
     * Shows the edit lexicon category page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditLexiconCategory($id) {
        $category = LexiconCategory::find($id);
        if (!$category) {
            abort(404);
        }

        return view('admin.subjects.create_edit_lang_category', [
            'category'        => $category,
            'categoryOptions' => LexiconCategory::where('id', '!=', $category->id)->pluck('name', 'id')->toArray(),
            'classes'         => LexiconSetting::all(),
        ]);
    }

    /**
     * Creates or edits a lexicon category.
     *
     * @param App\Services\SubjectService $service
     * @param int|null                    $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditLexiconCategory(Request $request, SubjectService $service, $id = null) {
        $id ? $request->validate(LexiconCategory::$updateRules) : $request->validate(LexiconCategory::$createRules);
        $data = $request->only([
            'name', 'description', 'parent_id',
            'property_name', 'property_is_dimensional', 'property_dimensions', 'property_class',
            'declension_criteria', 'declension_regex', 'declension_replacement',
            'populate_settings',
        ]);
        if ($id && $service->updateLexiconCategory(LexiconCategory::find($id), $data, Auth::user())) {
            flash('Category updated successfully.')->success();
        } elseif (!$id && $category = $service->createLexiconCategory($data, Auth::user())) {
            flash('Category created successfully.')->success();

            return redirect()->to('admin/data/language/lexicon-categories/edit/'.$category->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the lexicon category deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteLexiconCategory($id) {
        $category = LexiconCategory::find($id);

        return view('admin.subjects._delete_lang_category', [
            'category' => $category,
        ]);
    }

    /**
     * Deletes a lexicon category.
     *
     * @param App\Services\SubjectService $service
     * @param int                         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteLexiconCategory(Request $request, SubjectService $service, $id) {
        if ($id && $service->deleteLexiconCategory(LexiconCategory::find($id))) {
            flash('Category deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->to('admin/data/language/lexicon-categories');
    }

    /**
     * Sorts lexicon categories.
     *
     * @param App\Services\SubjectService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortLexiconCategory(Request $request, SubjectService $service) {
        if ($service->sortLexiconCategory($request->get('sort'))) {
            flash('Lexicon category order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }
}
