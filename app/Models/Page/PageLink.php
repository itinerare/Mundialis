<?php

namespace App\Models\Page;

use App\Models\Model;

class PageLink extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'link_id', 'title'
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
     * Get the page this version belongs to.
     */
    public function page()
    {
        return $this->belongsTo('App\Models\Page\Page');
    }

    /**
     * Get the page this version belongs to.
     */
    public function linkedPage()
    {
        return $this->belongsTo('App\Models\Page\Page', 'link_id');
    }

}
