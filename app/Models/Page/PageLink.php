<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageLink extends Model {
    use HasFactory;

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

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the parent this link belongs to.
     */
    public function parent() {
        return $this->morphTo();
    }

    /**
     * Get the object this link goes to.
     */
    public function linked() {
        return $this->morphTo('linked', 'linked_type', 'link_id');
    }
}
