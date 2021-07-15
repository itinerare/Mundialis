<?php

namespace App\Models\Subject;

use App\Models\Model;

class LexiconCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id', 'description', 'data'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lexicon_categories';

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
        'name' => 'required|unique:lexicon_categories'
    ];

    /**
     * Validation rules for category updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required'
    ];

    /**
     * Get parent category of this category.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Subject\LexiconCategory', 'parent_id');
    }

    /**
     * Get child categories of this category.
     */
    public function children()
    {
        return $this->hasMany('App\Models\Subject\LexiconCategory', 'parent_id');
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

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Assembles an array of all possible combinations of properties/dimensions
     * for a given lexical class.
     *
     * @param  int    $class
     * @return array
     */
    public function classCombinations($class, $i = 0)
    {
        if(!isset($this->data) || !isset($this->data[$class]['properties'])) return null;
        foreach($this->data[$class]['properties'] as $property)
            if(isset($property['dimensions'])) $arrays[] = $property['dimensions'];

        if(count($arrays) == 1) return $arrays[0];

        $results = $this->combinations($arrays);
        foreach($results as $key=>$result) $results[$key] = implode(' ', $result);

        foreach($this->data[$class]['properties'] as $property) if(!isset($property['dimensions'])) $results[] = $property['name'];

        return $results;
    }

    /**
     * Assembles an array of all possible combinations of several arrays.
     * Taken from https://stackoverflow.com/questions/8567082/how-to-generate-in-php-all-combinations-of-items-in-multiple-arrays
     *
     * @param  array    $arrays
     * @return array
     */
    public function combinations($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = $this->combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }

}
