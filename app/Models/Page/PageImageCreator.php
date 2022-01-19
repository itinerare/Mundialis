<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageImageCreator extends Model
{
    use HasFactory;

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
        'creator_url' => 'nullable|url',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_image_id', 'user_id', 'url',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_image_creators';

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the image this creator belongs to.
     */
    public function image()
    {
        return $this->belongsTo('App\Models\Page\PageImage');
    }

    /**
     * Get the user this creator belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the creator as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if (isset($this->user_id) && $this->user) {
            return $this->user->displayName;
        } elseif (isset($this->url)) {
            return prettyProfileLink($this->url);
        }

        return null;
    }
}
