<?php

namespace App\Models\Subject;

use App\Models\Model;

class TimeDivision extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'abbreviation', 'unit'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'time_divisions';

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

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Return the display name (abbreviation if present, name if not)
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if(isset($this->abbreviation)) return '<abbr data-toggle="tooltip" title="'.$this->name.'">'.$this->abbreviation.'</abbr>';
        return $this->name;
    }

}
