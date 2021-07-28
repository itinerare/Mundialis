<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\SoftDeletes;
use Request;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;

use App\Models\Model;

class Page extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'title', 'summary', 'is_visible', 'parent_id', 'image_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;

    /**
     * Validation rules for page creation.
     *
     * @var array
     */
    public static $createRules = [
        'title' => 'required|unique:pages'
    ];

    /**
     * Validation rules for page updating.
     *
     * @var array
     */
    public static $updateRules = [
        'title' => 'required'
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the category this page belongs to.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Subject\SubjectCategory', 'category_id');
    }

    /**
     * Get the parent this page belongs to.
     */
    public function parent()
    {
        if($this->category->subject['key'] == 'time')
            return $this->belongsTo('App\Models\Subject\TimeChronology', 'parent_id');
        return $this->belongsTo('App\Models\Page\Page', 'parent_id');
    }

    /**
     * Get this page's primary image.
     */
    public function image()
    {
        return $this->hasOne('App\Models\Page\PageImage', 'id', 'image_id');
    }

    /**
     * Get this page's images.
     */
    public function images()
    {
        return $this->belongsToMany('App\Models\Page\PageImage')->using('App\Models\Page\PagePageImage')->withPivot('is_valid');
    }

    /**
     * Get this page's versions.
     */
    public function versions()
    {
        return $this->hasMany('App\Models\Page\PageVersion');
    }

    /**
     * Get this page's protection records.
     */
    public function protections()
    {
        return $this->hasMany('App\Models\Page\PageProtection');
    }

    /**
     * Get this page's tags.
     */
    public function tags()
    {
        return $this->hasMany('App\Models\Page\PageTag')->where('type', '!=', 'utility');
    }

    /**
     * Get this page's utility tags.
     */
    public function utilityTags()
    {
        return $this->hasMany('App\Models\Page\PageTag')->where('type', 'utility');
    }

    /**
     * Get this page's associated links.
     */
    public function links()
    {
        return $this->hasMany('App\Models\Page\PageLink');
    }

    /**
     * Get this page's associated links.
     */
    public function linked()
    {
        return $this->hasMany('App\Models\Page\PageLink', 'link_id');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include visible pages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User\User                  $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $user = null)
    {
        if($user && $user->canWrite) return $query;
        return $query->where('is_visible', 1);
    }

    /**
     * Scope a query to only include pages of a given subject.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string                                 $subject
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubject($query, $subject)
    {
        return $query->whereIn('category_id',
            SubjectCategory::where('subject', 'places')->pluck('id')->toArray()
        );
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the page's most recent version.
     *
     * @return \App\Models\Page\PageVersion
     */
    public function getVersionAttribute()
    {
        return $this->versions()->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Get the page's most recent protection record.
     *
     * @return \App\Models\Page\PageProtection
     */
    public function getProtectionAttribute()
    {
        if(!$this->protections->count()) return null;
        return $this->protections()->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Get the current version's data attribute as an associative array.
     *
     * @return array
     */
    public function getDataAttribute()
    {
        if(!$this->versions->count() || !isset($this->version->data['data'])) return null;
        return $this->version->data['data'];
    }

    /**
     * Get the current version's parsed data attribute as an associative array.
     *
     * @return array
     */
    public function getParsedDataAttribute()
    {
        if(!$this->versions->count() || !isset($this->version->data['data']['parsed'])) return null;
        return $this->version->data['data']['parsed'];
    }

    /**
     * Get the page's slug.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return str_replace(' ', '_', $this->title);
    }

    /**
     * Get the page's url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('pages/'.$this->id.'.'.$this->slug);
    }

    /**
     * Get the page title as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if(url()->current() == $this->url) {
            return $this->title.(!$this->is_visible ? ' <i class="fas fa-eye-slash" data-toggle="tooltip" title="This page is currently hidden"></i>' : '');
        }
        return
            '<a href="'.$this->url.'" class=text-primary page-link"'.($this->summary ? ' data-toggle="tooltip" title="'.$this->summary.'"' : '').'>'.$this->title.'</a>'.(!$this->is_visible ? ' <i class="fas fa-eye-slash" data-toggle="tooltip" title="This page is currently hidden"></i>' : '');
    }

    /**
     * Get the page's tags for use by the tag entry field.
     *
     * @return string
     */
    public function getEntryTagsAttribute()
    {
        $tags = [];
        foreach($this->tags()->pluck('tag') as $tag)
            $tags[] = ['tag' => $tag];

        return json_encode($tags);
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Attempt to calculate the age of a person by comparing two time arrays.
     *
     * @param  array     $birth
     * @param  array     $current
     * @return string
     */
    public function personAge($birth, $current)
    {
        if(isset($birth['date'][
            str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))
            ]) &&
        isset($current['date'][
            str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))
            ]) &&
        (isset($birth['chronology']) && isset($current['chronology']) &&
        ($birth['chronology'] == $current['chronology']))) {
            // Cycle through both birth and current dates,
            // collecting set values, then turn them into a "datestring"
            foreach($birth['date'] as $date) {
                // Trim leading zeros on each while collecting
                $birthString[] = ltrim($date, 0);
            }
            $birthString = implode('', array_reverse($birthString));

            foreach($current['date'] as $date) {
                $currentString[] = ltrim($date, 0);
            }
            $currentString = implode('', array_reverse($currentString));

            // Just calculate the rough age either for use or for checking against
            $roughYear = $current['date'][str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))] -
            $birth['date'][str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))];

            // If the birth and current strings are the same, more precisely
            // calculate and return age
            if(strlen($birthString) == strlen($currentString)) {
                $ageString = floor($currentString - $birthString);

                if($roughYear == $ageString) return $roughYear;
                else return floor($ageString/pow(10, (strlen($ageString)-2)));
            }

            // Failing that, just return the rough age
            return $roughYear;
        }

        return null;
    }

}
