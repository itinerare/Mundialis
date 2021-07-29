<?php

namespace App\Models\Lexicon;

use App\Models\Model;

class LexiconEntry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'class', 'word', 'meaning', 'pronunciation', 'definition', 'data'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lexicon_entries';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;

    /**
     * Validation rules for entry creation.
     *
     * @var array
     */
    public static $createRules = [
        'word' => 'required',
        'meaning' => 'required',
        'class' => 'required'
    ];

    /**
     * Validation rules for entry updating.
     *
     * @var array
     */
    public static $updateRules = [
        'word' => 'required',
        'meaning' => 'required',
        'class' => 'required'
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the category this entry belongs to.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Subject\LexiconCategory', 'category_id');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include visible pages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User\User                  $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $user = null)
    {
        if($user && $user->canWrite) return $query;
        return $query->where('is_visible', 1);
    }

}
