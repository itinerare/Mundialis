<?php

namespace App\Models\Subject;

use App\Models\Model;

class SubjectTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'data'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_templates';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * Validation rules for template components.
     *
     * @var array
     */
    public static $rules = [
        'section_name.*' => 'required_with:section_key.*',
        'infobox_key.*' => 'nullable|between:3,25|alpha_dash',
        'infobox_type.*' => 'nullable|required_with:field_key.*',
        'infobox_label.*' => 'nullable|string|required_with:field_key.*',
        'infobox_choices.*' => 'nullable|string|required_if:field_type.*,choice,multiple',
        'infobox_rules.*' => 'nullable|string|max:255',
        'infobox_value.*' => 'nullable|string|max:255',
        'infobox_help.*' => 'nullable|string|max:255',
        'field_key.*' => 'nullable|between:3,25|alpha_dash',
        'field_type.*' => 'nullable|required_with:field_key.*',
        'field_label.*' => 'nullable|string|required_with:field_key.*',
        'field_choices.*' => 'nullable|string|required_if:field_type.*,choice,multiple',
        'field_rules.*' => 'nullable|string|max:255',
        'field_value.*' => 'nullable|string|max:255',
        'field_help.*' => 'nullable|string|max:255'
    ];

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
