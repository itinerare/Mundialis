<?php

namespace App\Models\Lexicon;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LexiconEntry extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'class', 'word', 'meaning', 'pronunciation', 'definition', 'parsed_definition', 'data',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lexicon_entries';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

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
        'word'    => 'required',
        'meaning' => 'required',
        'class'   => 'required',
    ];

    /**
     * Validation rules for entry updating.
     *
     * @var array
     */
    public static $updateRules = [
        'word'    => 'required',
        'meaning' => 'required',
        'class'   => 'required',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the category this entry belongs to.
     */
    public function category() {
        return $this->belongsTo('App\Models\Subject\LexiconCategory', 'category_id');
    }

    /**
     * Get the part of speech this entry belongs to.
     */
    public function lexicalClass() {
        return $this->belongsTo('App\Models\Subject\LexiconSetting', 'class', 'name');
    }

    /**
     * Get this entry's roots.
     */
    public function etymologies() {
        return $this->hasMany('App\Models\Lexicon\LexiconEtymology', 'entry_id');
    }

    /**
     * Get this entry's descendants.
     */
    public function descendants() {
        return $this->hasMany('App\Models\Lexicon\LexiconEtymology', 'parent_id');
    }

    /**
     * Get this entry's associated links.
     */
    public function links() {
        return $this->hasMany('App\Models\Page\PageLink', 'parent_id')->where('parent_type', 'entry');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include visible pages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User\User                 $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $user = null) {
        if ($user && $user->canWrite) {
            return $query;
        }

        return $query->where('is_visible', 1);
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the word linked to its category or to the subject.
     *
     * @return string
     */
    public function getDisplayNameAttribute() {
        return '<a href="'.($this->category ? $this->category->url.'?word='.$this->word : '/language?word='.$this->word).'">'.$this->word.'</a>';
    }

    /**
     * Get the formatted version of the word.
     *
     * @return string
     */
    public function getDisplayWordAttribute() {
        return $this->displayName.($this->lexicalClass->abbreviation ? ' <i>'.$this->lexicalClass->abbreviation.'.</i>' : ', '.$this->lexicalClass->name).', "'.$this->meaning.'"'.($this->category ? ' ('.$this->category->displayName.')' : null);
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Returns formatted etymology information for this entry.
     *
     * @return string
     */
    public function getEtymology() {
        if (!$this->etymologies->count()) {
            return null;
        }

        // Cycle through parents
        $i = 0;
        foreach ($this->etymologies as $parent) {
            // If there is a parent entry
            if ($parent->parentEntry) {
                $parentString[] = ($i == 0 ? 'from ' : ' and ').($parent->parentEntry->category ? $parent->parentEntry->category->displayName.' ' : null).'<i>'.$parent->parentEntry->displayName.'</i> ('.($parent->parentEntry->lexicalClass->abbreviation ? '<i>'.$parent->parentEntry->lexicalClass->abbreviation.'.</i>' : $parent->parentEntry->lexicalClass->name.', ').' "'.lcfirst($parent->parentEntry->meaning).'")'.($parent->parentEntry->etymologies->count() ? ' '.$parent->parentEntry->getEtymology() : null);
            }
            // If there is only a string
            else {
                $parentString[] = ($i == 0 ? 'from ' : ' and ').$parent->parent;
            }

            $i++;
        }

        return implode('', $parentString);
    }

    /**
     * Returns formatted descendant information for this entry.
     *
     * @return string
     */
    public function getDescendants() {
        if (!$this->descendants->count()) {
            return null;
        }

        // Cycle through parents
        $i = 0;
        foreach ($this->descendants->sortBy(function ($descendant) {
            return $descendant->entry->word;
        }) as $descendant) {
            $descendantString[] = '<li>'.$descendant->entry->displayWord.($descendant->entry->descendants->count() ? $descendant->entry->getDescendants() : null).'</li>';
            $i++;
        }

        return '<ul>'.implode('', $descendantString).'</ul>';
    }
}
