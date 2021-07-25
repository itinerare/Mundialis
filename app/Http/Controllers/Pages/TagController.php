<?php

namespace App\Http\Controllers\Pages;

use Auth;
use Route;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;

use App\Models\Page\Page;
use App\Models\Page\PageTag;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Page Tag Controller
    |--------------------------------------------------------------------------
    |
    | Handles page tag display and related functions.
    |
    */

    /**
     * Shows a tag's page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $tag
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getTag(Request $request, $tag)
    {
        $tag = str_replace('_', ' ', $tag);

        $query = Page::visible(Auth::check() ? Auth::user() : null)->whereIn('id', PageTag::tag()->tagSearch($tag)->pluck('page_id')->toArray());
        $sort = $request->only(['sort']);

        if($request->get('title')) $query->where(function($query) use ($request) {
            $query->where('pages.title', 'LIKE', '%' . $request->get('title') . '%');
        });
        if($request->get('category_id')) $query->where('category_id', $request->get('category_id'));
        if($request->get('tags'))
            foreach($request->get('tags') as $searchTag)
                $query->whereIn('id', PageTag::tagSearch($searchTag)->tag()->pluck('page_id')->toArray());

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

        return view('pages.tags.tag', [
            'tag' => $tag,
            'pages' => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'tags' => (new PageTag)->listTags(),
            'dateHelper' => new TimeDivision
        ]);
    }

    /**
     * Gets all extant tags. Used for page editing.
     *
     * @return array
     */
    public function getAllTags()
    {
        $query = PageTag::tag()->pluck('tag')->unique();

        $tags = [];
        foreach($query as $tag)
            $tags[] = ['tag' => $tag];

        return json_encode($tags);
    }

}
