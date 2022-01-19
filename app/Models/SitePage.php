<?php

namespace App\Models;

class SitePage extends Model
{
    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'title', 'text',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_pages';
}
