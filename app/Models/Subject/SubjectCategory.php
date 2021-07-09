<?php

namespace App\Models\Subject;

use App\Models\Model;

class SubjectCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'name', 'parent_id', 'description', 'data'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_categories';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * Validation rules for category creation.
     *
     * @var array
     */
    public static $createRules = [
        'name' => 'required|unique:subject_categories'
    ];

    /**
     * Validation rules for category updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required'
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the template for this subject.
     */
    public function subjectTemplate()
    {
        return $this->belongsTo('App\Models\Subject\SubjectTemplate', 'subject', 'subject');
    }

    /**
     * Get parent category of this category.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Subject\SubjectCategory', 'parent_id');
    }

    /**
     * Get child categories of this category.
     */
    public function children()
    {
        return $this->hasMany('App\Models\Subject\SubjectCategory', 'parent_id');
    }

    /**
     * Get pages in this category.
     */
    public function pages()
    {
        return $this->hasMany('App\Models\Subject\SubjectPage');
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
