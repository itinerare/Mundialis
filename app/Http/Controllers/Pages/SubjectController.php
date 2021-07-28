<?php

namespace App\Http\Controllers\Pages;

use Auth;
use Config;

use App\Models\User\User;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\LexiconCategory;

use App\Models\Page\PageTag;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $subjectKey = $subject; $subject = Config::get('mundialis.subjects.'.$subject);
        $subject['key'] = $subjectKey;

        return view('pages.subjects.subject', [
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
                $query->whereIn('id', PageTag::tagSearch($tag)->tag()->pluck('page_id')->toArray());

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

        return view('pages.subjects.category', [
            'category' => $category,
            'pages' => $query->paginate(20)->appends($request->query()),
            'tags' => (new PageTag)->listTags(),
            'dateHelper' => new TimeDivision
        ]);
    }

}
