<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageLink extends Model
{
    use HasFactory;

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'link_id', 'title', 'parent_type', 'linked_type',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_links';

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the parent this link belongs to.
     */
    public function parent()
    {
        switch ($this->parent_type) {
            case 'page':
                return $this->belongsTo('App\Models\Page\Page');
                break;
            case 'entry':
                return $this->belongsTo('App\Models\Lexicon\LexiconEntry');
        }
    }

    /**
     * Get the object this link goes to.
     */
    public function linked()
    {
        switch ($this->linked_type) {
            case 'page':
            return $this->belongsTo('App\Models\Page\Page', 'link_id');
                break;
            case 'entry':
                return $this->belongsTo('App\Models\Lexicon\LexiconEntry');
        }
    }
}
