<?php

namespace App\Models\Subject;

use App\Models\Model;
use App\Models\Page\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeChronology extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'abbreviation', 'description',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'time_chronology';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * Validation rules for chronology creation.
     *
     * @var array
     */
    public static $createRules = [
        'name'         => 'required|unique:time_chronology',
        'abbreviation' => 'nullable|unique:time_chronology',
    ];

    /**
     * Validation rules for chronology updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get parent category of this category.
     */
    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get child categories of this category.
     */
    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get pages in this category.
     */
    public function pages() {
        return $this->hasMany(Page::class, 'parent_id')->whereRelation('category', 'subject', 'time');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Return the display name (abbreviation if present, name if not) as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute() {
        if (isset($this->abbreviation)) {
            return '<a href="'.$this->url.'"><abbr data-toggle="tooltip" title="'.$this->name.'">'.$this->abbreviation.'</abbr></a>';
        }

        return '<a href="'.$this->url.'">'.$this->name.'</a>';
    }

    /**
     * Return the full display name as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameFullAttribute() {
        return '<a href="'.$this->url.'">'.$this->name.'</a>';
    }

    /**
     * Get the chronology's url.
     *
     * @return string
     */
    public function getUrlAttribute() {
        return url('time/chronologies/'.$this->id);
    }
}
