<?php

namespace App\Models\Subject;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class LexiconCategory extends Model
{
    use HasFactory;

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
        'name' => 'required|unique:lexicon_categories',
    ];

    /**
     * Validation rules for category updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id', 'description', 'data',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lexicon_categories';

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

    /**
     * Get entries in this category.
     */
    public function entries()
    {
        return $this->hasMany('App\Models\Lexicon\LexiconEntry', 'category_id');
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
        if (!isset($this->attributes['data'])) {
            return null;
        }

        return json_decode($this->attributes['data'], true);
    }

    /**
     * Get the category's url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('language/lexicon/' . $this->id);
    }

    /**
     * Get the category name as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return '<a href="' . $this->url . '">' . $this->name . '</a>';
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
        if (!isset($this->data) || !isset($this->data[$class]['properties'])) {
            return null;
        }
        foreach ($this->data[$class]['properties'] as $property) {
            if (isset($property['dimensions'])) {
                $arrays[] = $property['dimensions'];
            }
        }

        if (count($arrays) == 1) {
            return $arrays[0];
        }

        $results = $this->combinations($arrays);
        foreach ($results as $key=>$result) {
            $results[$key] = implode(' ', $result);
        }

        foreach ($this->data[$class]['properties'] as $property) {
            if (!isset($property['dimensions'])) {
                $results[] = $property['name'];
            }
        }

        return $results;
    }

    /**
     * Assembles an array of all possible combinations of several arrays.
     * Taken from https://stackoverflow.com/questions/8567082/how-to-generate-in-php-all-combinations-of-items-in-multiple-arrays.
     *
     * @param  array    $arrays
     * @return array
     */
    private function combinations($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return [];
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = $this->combinations($arrays, $i + 1);

        $result = [];

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge([$v], $t) :
                    [$v, $t];
            }
        }

        return $result;
    }
}
