<?php

namespace App\Http\Controllers\Pages;

use Auth;
use Config;
use App\Models\User\User;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\LexiconCategory;
use App\Models\Subject\LexiconSetting;

use App\Models\Page\Page;
use App\Models\Page\PageTag;
use App\Models\Lexicon\LexiconEntry;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Services\LexiconManager;

class SubjectController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Pages/Subject Controller
    |--------------------------------------------------------------------------
    |
    | Handles subject pages.
    |
    */

    /**
     * Shows a subject's category index.
     *
     * @param  string                    $subject
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSubject($subject, Request $request)
    {
        $subjectKey = $subject;
        $subject = Config::get('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        if ($subject['key'] == 'language') {
            $query = LexiconEntry::whereNull('category_id')->visible(Auth::check() ? Auth::user() : null);
            $sort = $request->only(['sort']);

            if ($request->get('class')) {
                $query->where('class', $request->get('class'));
            }

            if ($request->get('word')) {
                $query->where(function ($query) use ($request) {
                    $query->where('lexicon_entries.word', 'LIKE', '%' . $request->get('word') . '%');
                });
            }
            if ($request->get('meaning')) {
                $query->where(function ($query) use ($request) {
                    $query->where('lexicon_entries.meaning', 'LIKE', '%' . $request->get('meaning') . '%');
                });
            }
            if ($request->get('pronounciation')) {
                $query->where(function ($query) use ($request) {
                    $query->where('lexicon_entries.pronounciation', 'LIKE', '%' . $request->get('pronounciation') . '%');
                });
            }

            if (isset($sort['sort'])) {
                switch ($sort['sort']) {
                    case 'alpha':
                        $query->orderBy('word');
                        break;
                    case 'alpha-reverse':
                        $query->orderBy('word', 'DESC');
                        break;
                    case 'meaning':
                        $query->orderBy('meaning');
                        break;
                    case 'meaning-reverse':
                        $query->orderBy('meaning', 'DESC');
                        break;
                    case 'newest':
                        $query->orderBy('created_at', 'DESC');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'ASC');
                        break;
                }
            } else {
                $query->orderBy('word');
            }
        }

        return view('pages.subjects.subject', [
            'subject' => $subject,
            'categories' => SubjectCategory::where('subject', $subject['key'])->whereNull('parent_id')->orderBy('sort', 'DESC')->paginate(20)->appends($request->query())
        ] + ($subject['key'] == 'language' ? [
            'langCategories' => LexiconCategory::whereNull('parent_id')->orderBy('sort', 'DESC')->paginate(20)->appends($request->query()),
            'entries' => $query->paginate(20)->appends($request->query()),
            'classOptions' => LexiconSetting::orderBy('sort', 'DESC')->pluck('name', 'name')
        ] : []) + ($subject['key'] == 'time' ? [
            'timeCategories' => TimeChronology::whereNull('parent_id')->orderBy('sort', 'DESC')->paginate(20)->appends($request->query()),
            'showTimeline' => TimeDivision::dateEnabled()->count() ? 1 : 0,
        ] : []));
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
        if (!$category) {
            abort(404);
        }
        if ($category->subject['key'] != $subject) {
            abort(404);
        }

        $query = $category->pages()->visible(Auth::check() ? Auth::user() : null);
        $sort = $request->only(['sort']);

        if ($request->get('title')) {
            $query->where(function ($query) use ($request) {
                $query->where('pages.title', 'LIKE', '%' . $request->get('title') . '%');
            });
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

        return view('pages.subjects.category', [
            'category' => $category,
            'pages' => $query->paginate(20)->appends($request->query()),
            'tags' => (new PageTag())->listTags(),
            'dateHelper' => new TimeDivision()
        ]);
    }

    /******************************************************************************
        SPECIALIZED - TIME
    *******************************************************************************/

    /**
     * Shows a category's page.
     *
     * @param  int                       $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getTimeChronology($id, Request $request)
    {
        $chronology = TimeChronology::where('id', $id)->first();
        if (!$chronology) {
            abort(404);
        }

        $query = $chronology->pages()->visible(Auth::check() ? Auth::user() : null);
        $sort = $request->only(['sort']);

        if ($request->get('title')) {
            $query->where(function ($query) use ($request) {
                $query->where('pages.title', 'LIKE', '%' . $request->get('title') . '%');
            });
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

        return view('pages.subjects.time_chronology', [
            'chronology' => $chronology,
            'pages' => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'tags' => (new PageTag())->listTags(),
            'dateHelper' => new TimeDivision()
        ]);
    }

    /**
     * Shows the timeline page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getTimeTimeline()
    {
        if (!TimeDivision::dateEnabled()->count()) {
            abort(404);
        }

        return view('pages.subjects.time_timeline', [
            'tags' => (new PageTag())->listTags(),
            'chronologies' => TimeChronology::whereNull('parent_id')->orderBy('sort', 'DESC')->get(),
            'eventHelper' => new Page(),
            'dateHelper' => new TimeDivision(),
            'divisions' => (new TimeDivision())->dateEnabled()->orderBy('sort', 'DESC')->get()
        ]);
    }

    /******************************************************************************
        SPECIALIZED - LANGUAGE
    *******************************************************************************/

    /**
     * Shows a lexicon category's page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLexiconCategory(Request $request, $id)
    {
        $category = LexiconCategory::where('id', $id)->first();
        if (!$category) {
            abort(404);
        }

        $query = $category->entries()->visible(Auth::check() ? Auth::user() : null);
        $sort = $request->only(['sort']);

        if ($request->get('class')) {
            $query->where('class', $request->get('class'));
        }

        if ($request->get('word')) {
            $query->where(function ($query) use ($request) {
                $query->where('lexicon_entries.word', 'LIKE', '%' . $request->get('word') . '%');
            });
        }
        if ($request->get('meaning')) {
            $query->where(function ($query) use ($request) {
                $query->where('lexicon_entries.meaning', 'LIKE', '%' . $request->get('meaning') . '%');
            });
        }
        if ($request->get('pronounciation')) {
            $query->where(function ($query) use ($request) {
                $query->where('lexicon_entries.pronounciation', 'LIKE', '%' . $request->get('pronounciation') . '%');
            });
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'alpha':
                    $query->orderBy('word');
                    break;
                case 'alpha-reverse':
                    $query->orderBy('word', 'DESC');
                    break;
                case 'meaning':
                    $query->orderBy('meaning');
                    break;
                case 'meaning-reverse':
                    $query->orderBy('meaning', 'DESC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('word');
        }

        return view('pages.subjects.lang_category', [
            'category' => $category,
            'entries' => $query->paginate(20)->appends($request->query()),
            'classOptions' => LexiconSetting::orderBy('sort', 'DESC')->pluck('name', 'name')
        ]);
    }

    /**
     * Gets the lexicon entry modal.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLexiconEntryModal($id)
    {
        $entry = LexiconEntry::visible(Auth::check() ? Auth::user() : null)->where('id', $id)->first();
        if (!$entry) {
            abort(404);
        }

        return view('pages.subjects._lang_entry', [
            'entry' => $entry
        ]);
    }

    /**
     * Shows the create lexicon entry page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateLexiconEntry()
    {
        return view('pages.subjects.create_edit_lexicon_entry', [
            'entry' => new LexiconEntry(),
            'categoryOptions' => LexiconCategory::pluck('name', 'id'),
            'classOptions' => LexiconSetting::orderBy('sort', 'DESC')->pluck('name', 'name'),
            'entryOptions' => LexiconEntry::pluck('word', 'id')
        ]);
    }

    /**
     * Shows the edit lexicon entry page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditLexiconEntry($id)
    {
        $entry = LexiconEntry::where('id', $id)->first();
        if (!$entry) {
            abort(404);
        }

        return view('pages.subjects.create_edit_lexicon_entry', [
            'entry' => $entry,
            'categoryOptions' => LexiconCategory::pluck('name', 'id'),
            'classOptions' => LexiconSetting::orderBy('sort', 'DESC')->pluck('name', 'name'),
            'entryOptions' => LexiconEntry::where('id', '!=', $entry->id)->pluck('word', 'id')
        ]);
    }

    /**
     * Creates a new lexicon entry.
     *
     * @param  \Illuminate\Http\Request       $request
     * @param  App\Services\LexiconManager    $service
     * @param  int                            $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditLexiconEntry(Request $request, LexiconManager $service, $id = null)
    {
        $id ? $request->validate(LexiconEntry::$updateRules) : $request->validate(LexiconEntry::$createRules);

        $data = $request->only([
            'word', 'category_id', 'class',
            'meaning', 'pronunciation', 'definition', 'is_visible',
            'parent_id', 'parent', 'conjdecl', 'autoconj'
        ]);

        if ($id && $service->updateLexiconEntry(LexiconEntry::find($id), $data, Auth::user())) {
            flash('Lexicon entry updated successfully.')->success();
        } elseif (!$id && $entry = $service->createLexiconEntry($data, Auth::user())) {
            flash('Lexicon entry created successfully.')->success();
            return redirect()->to('language/lexicon/edit/'.$entry->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }
        return redirect()->back();
    }

    /**
     * Gets the lexicon entry deletion modal.
     *
     * @param  int       $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteLexiconEntry($id)
    {
        $entry = LexiconEntry::where('id', $id)->first();
        if (!$entry) {
            abort(404);
        }

        return view('pages.subjects._delete_lang_entry', [
            'entry' => $entry
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param  \Illuminate\Http\Request      $request
     * @param  App\Services\LexiconManager   $service
     * @param  int                           $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteLexiconEntry(Request $request, LexiconManager $service, $id)
    {
        if ($id && $service->deleteLexiconEntry(LexiconEntry::find($id), Auth::user())) {
            flash('Lexicon entry deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
            return redirect()->back();
        }
        return redirect()->to('language');
    }
}
