<?php

namespace App\Models\Lexicon;

use App\Models\Model;

class LexiconEtymology extends Model
{
    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $rules = [

    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry_id', 'parent_id', 'parent',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lexicon_etymologies';

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the entry this etymology belongs to.
     */
    public function entry()
    {
        return $this->belongsTo('App\Models\Lexicon\LexiconEntry');
    }

    /**
     * Get the entry this etymology refers to.
     */
    public function parentEntry()
    {
        return $this->belongsTo('App\Models\Lexicon\LexiconEntry', 'parent_id');
    }
}
