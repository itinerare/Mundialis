<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\SoftDeletes;

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
        'category_id', 'title', 'summary', 'data', 'is_visible', 'parent_id', 'image_id'
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
        return $this->belongsToMany('App\Models\Page\PageImage')->using('App\Models\Page\PagePageImage')->withPivot('is_valid');;
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
     * Get the data attribute as an associative array.
     *
     * @return array
     */
    public function getDataAttribute()
    {
        if(!isset($this->attributes['data'])) return null;
        return json_decode($this->attributes['data'], true);
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
        return '<a href="'.$this->url.'">'.$this->title.'</a>';
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
        ($birth['chronology'] == $current['chronology'])))
            return $current['date'][str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))] -
            $birth['date'][str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))];

        return null;
    }

}
