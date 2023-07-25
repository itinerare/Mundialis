<?php

namespace App\Models\Subject;

use App\Models\Model;
use App\Models\Page\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

class SubjectCategory extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'name', 'summary', 'parent_id', 'description', 'data', 'has_image',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_categories';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

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
        'name'  => 'required|unique:subject_categories',
        'image' => 'nullable|mimes:png',
    ];

    /**
     * Validation rules for category updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name'  => 'required',
        'image' => 'nullable|mimes:png',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get parent category of this category.
     */
    public function parent() {
        return $this->belongsTo(SubjectCategory::class, 'parent_id');
    }

    /**
     * Get child categories of this category.
     */
    public function children() {
        return $this->hasMany(SubjectCategory::class, 'parent_id');
    }

    /**
     * Get pages in this category.
     */
    public function pages() {
        return $this->hasMany(Page::class, 'category_id');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the category's url.
     *
     * @return string
     */
    public function getUrlAttribute() {
        return url($this->attributes['subject'].'/categories/'.$this->id);
    }

    /**
     * Get the category name as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute() {
        return '<a href="'.$this->url.'">'.$this->name.'</a>';
    }

    /**
     * Gets the file directory containing the model's image.
     *
     * @return string
     */
    public function getImageDirectoryAttribute() {
        return 'images/data/categories';
    }

    /**
     * Gets the file name of the model's image.
     *
     * @return string
     */
    public function getImageFileNameAttribute() {
        return $this->id.'-image.png';
    }

    /**
     * Gets the path to the file directory containing the model's image.
     *
     * @return string
     */
    public function getImagePathAttribute() {
        return public_path($this->imageDirectory);
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getImageUrlAttribute() {
        if (!$this->has_image) {
            return null;
        }

        return asset($this->imageDirectory.'/'.$this->imageFileName);
    }

    /**
     * Get the category's subject, with information from config.
     *
     * @return array
     */
    public function getSubjectAttribute() {
        // Fetch config information for the recorded subject
        $subject = config('mundialis.subjects.'.$this->attributes['subject']);
        // Then add its key to the array
        $subject['key'] = $this->attributes['subject'];

        return $subject;
    }

    /**
     * Get the category's subject's template.
     *
     * @return SubjectTemplate
     */
    public function getSubjectTemplateAttribute() {
        return SubjectTemplate::where('subject', $this->attributes['subject'])->first();
    }

    /**
     * Get the category's template data, or failing that, its parent's.
     *
     * @return array
     */
    public function getTemplateAttribute() {
        // Check to see if this category's data is set,
        if (isset($this->data) && $this->data) {
            // Fallback for testing purposes
            if (!is_array($this->data)) {
                $this->data = json_decode($this->data, true);
            }

            return $this->data;
        }

        // Else recursively check parents for data and return if data is found
        if ($this->parent) {
            $template = $this->fetchTemplateRecursive($this->parent);
            if (isset($template) && $template) {
                return $template;
            }
        }

        // If no data is found and the subject's template is set,
        // return the subject's template data
        if (isset($this->subjectTemplate->data) && $this->subjectTemplate->data) {
            // Fallback for testing purposes
            if (!is_array($this->subjectTemplate->data)) {
                $this->subjectTemplate->data = json_decode($this->subjectTemplate->data, true);
            }

            return $this->subjectTemplate->data;
        }

        // Failing that return an empty array so the form builder doesn't error
        else {
            return [];
        }
    }

    /**
     * Assemble the category's form fields for ease of processing.
     *
     * @return array
     */
    public function getFormFieldsAttribute() {
        $fields = [];

        if (isset($this->template['infobox'])) {
            $fields = $fields + $this->template['infobox'];
        }
        if (isset($this->template['sections'])) {
            foreach ($this->template['sections'] as $sectionKey=>$section) {
                if (isset($this->template['fields'][$sectionKey])) {
                    $fields = $fields + $this->template['fields'][$sectionKey];
                }
            }
        }

        return $fields;
    }

    private function fetchTemplateRecursive($parent) {
        if (isset($parent->data) && $parent->data) {
            // Fallback for testing purposes
            if (!is_array($parent->data)) {
                $parent->data = json_decode($parent->data, true);
            }

            return $parent->data;
        }
        if (isset($parent->parent_id) && $parent->parent) {
            return $this->fetchTemplateRecursive($parent->parent);
        }

        return null;
    }
}
