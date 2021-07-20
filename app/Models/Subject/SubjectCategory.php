<?php

namespace App\Models\Subject;

use Config;
use App\Models\Subject\SubjectTemplate;

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
        return $this->hasMany('App\Models\Page\Page', 'category_id');
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
     * Get the page's url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url($this->attributes['subject'].'/categories/'.$this->id);
    }

    /**
     * Get the category name as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return '<a href="'.$this->url.'">'.$this->name.'</a>';
    }

    /**
     * Get the category's subject, with information from config.
     *
     * @return array
     */
    public function getSubjectAttribute()
    {
        // Fetch config information for the recorded subject
        $subject = Config::get('mundialis.subjects.'.$this->attributes['subject']);
        // Then add its key to the array
        $subject['key'] = $this->attributes['subject'];
        return $subject;
    }

    /**
     * Get the category's subject's template.
     *
     * @return App\Models\Subject\SubjectTemplate
     */
    public function getSubjectTemplateAttribute()
    {
        return SubjectTemplate::where('subject', $this->attributes['subject'])->first();
    }

    /**
     * Get the category's template data, or failing that, its parent's.
     *
     * @return array
     */
    public function getTemplateAttribute()
    {
        // Check to see if this category's data is set,
        if(isset($this->data) && $this->data) return $this->data;
        // Else recursively check parents for data and return if data is found
        else if($this->parent) {
            $template = $this->fetchTemplateRecursive($this->parent);
            if(isset($template) && $template) return $template;
        }
        // If no data is found and the subject's template is set,
        // return the subject's template data
        else if(isset($this->subjectTemplate->data) && $this->subjectTemplate->data) return $this->subjectTemplate->data;
        // Failing that return an empty array so the form builder doesn't error
        else return [];
    }
    private function fetchTemplateRecursive($parent)
    {
        if(isset($parent->data)) $template = $parent->data;
        elseif($parent->parent) $template = $this->fetchTemplateRecursive($parent);

        return $template;
    }

    /**
     * Assemble the category's form fields for ease of processing.
     *
     * @return array
     */
    public function getFormFieldsAttribute()
    {
        $fields = [];

        if(isset($this->template['infobox'])) $fields = $fields + $this->template['infobox'];
        if(isset($this->template['sections']))
            foreach($this->template['sections'] as $sectionKey=>$section) {
                if(isset($this->template['fields'][$sectionKey])) $fields = $fields + $this->template['fields'][$sectionKey];
            }

        return $fields;
    }

}
