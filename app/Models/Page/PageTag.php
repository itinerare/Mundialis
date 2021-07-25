<?php

namespace App\Models\Page;

use App\Models\Model;

class PageTag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'type', 'tag'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_tags';

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
        return $this->belongsTo('App\Models\Page\Page')->withTrashed();
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include page tags.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTag($query)
    {
        return $query->where('type', 'page_tag');
    }

    /**
     * Scope a query to only include utility tags.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUtilityTag($query)
    {
        return $query->where('type', 'utility');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the page tag's url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('pages/tags/'.str_replace(' ', '_', $this->tag));
    }

    /**
     * Get the page tag as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return '<a href="'.$this->url.'">'.$this->tag.'</a>';
    }

}
