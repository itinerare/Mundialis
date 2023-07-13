<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

class PageRelationship extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_one_id', 'page_two_id',
        'type_one', 'type_one_info', 'details_one',
        'type_two', 'type_two_info', 'details_two',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_relationships';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * Validation rules for page creation.
     *
     * @var array
     */
    public static $rules = [
        'page_one_id'   => 'required',
        'page_two_id'   => 'required',

        'type_one'      => 'required',
        'type_one_info' => 'required_if:type_one,custom,romantic_custom',

        'type_two'      => 'required',
        'type_two_info' => 'required_if:type_two,custom,romantic_custom',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get one of the pages this relationship belongs to.
     */
    public function pageOne() {
        return $this->belongsTo('App\Models\Page\Page', 'page_one_id');
    }

    /**
     * Get one of the pages this relationship belongs to.
     */
    public function pageTwo() {
        return $this->belongsTo('App\Models\Page\Page', 'page_two_id');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get one of the the relationship's types.
     *
     * @return string
     */
    public function getDisplayTypeOneAttribute() {
        // First check to see if the set type is a "custom" one, and if so,
        // just return the information provided
        if ($this->type_one == 'custom' || $this->type_one == 'romantic_custom') {
            return $this->type_one_info;
        }

        // Get relevant config file
        $config = Config::get('mundialis.'.$this->pageOne->category->subject['key'].'_relationships');

        // Cycle through its groups
        foreach ($config as $group) {
            if (isset($group[$this->type_one])) {
                return $group[$this->type_one];
            }
        }

        return null;
    }

    /**
     * Get one of the the relationship's types.
     *
     * @return string
     */
    public function getDisplayTypeTwoAttribute() {
        // First check to see if the set type is a "custom" one, and if so,
        // just return the information provided
        if ($this->type_two == 'custom' || $this->type_two == 'romantic_custom') {
            return $this->type_two_info;
        }

        // Get relevant config file
        $config = Config::get('mundialis.'.$this->pageTwo->category->subject['key'].'_relationships');

        // Cycle through its groups
        foreach ($config as $group) {
            if (isset($group[$this->type_two])) {
                return $group[$this->type_two];
            }
        }

        return null;
    }
}
