<?php

namespace App\Models\Subject;

use App\Models\Model;

class LexiconSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'abbreviation'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lexicon_settings';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * Validation rules for category updating.
     *
     * @var array
     */
    public static $rules = [
        'name.*' => 'required'
    ];

}
