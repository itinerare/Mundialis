<?php

namespace App\Models\Subject;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeDivision extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'abbreviation', 'unit', 'use_for_dates',
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
        'name.*' => 'required',
    ];

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include date-enabled divisions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateEnabled($query)
    {
        return $query->where('use_for_dates', 1);
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Return the display name (abbreviation if present, name if not).
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if (isset($this->abbreviation)) {
            return '<abbr data-toggle="tooltip" title="'.$this->name.'">'.$this->abbreviation.'.</abbr>';
        }

        return $this->name;
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Return form field information for date-enabled divisions.
     *
     * @return array
     */
    public function dateFields()
    {
        $fields = [];
        foreach ($this->dateEnabled()->orderBy('sort')->get() as $division) {
            $fields[$division->id] = [
                'label'   => $division->name,
                'type'    => 'number',
                'rules'   => null,
                'choices' => null,
                'value'   => null,
                'help'    => null,
            ];
        }

        return $fields;
    }

    /**
     * Return a formatted datestring using date-enabled divisions.
     *
     * @param array $data
     *
     * @return string
     */
    public function formatTimeDate($data)
    {
        // Cycle through date-enabled divisions and add a formatted string to the array
        foreach ($this->dateEnabled()->orderBy('sort')->get() as $division) {
            if (isset($data[$division->id])) {
                // First try the current methodology for finding date information
                $date[] = $division->displayName.' '.$data[$division->id];
            } elseif (isset($data[str_replace(' ', '_', strtolower($division->name))])) {
                // Then as a fallback, check for the earlier methodology
                $date[] = $division->displayName.' '.$data[str_replace(' ', '_', strtolower($division->name))];
            }
        }

        if (isset($date)) {
            return implode(', ', $date);
        }

        return null;
    }
}
