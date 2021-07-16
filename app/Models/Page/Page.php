<?php

namespace App\Models\Page;

use App\Models\Subject\SubjectCategory;

use App\Models\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'title', 'summary', 'data', 'is_visible'
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
     * Validation rules for category creation.
     *
     * @var array
     */
    public static $createRules = [
        'title' => 'required|unique:pages'
    ];

    /**
     * Validation rules for category updating.
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

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include visible pages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
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

}
