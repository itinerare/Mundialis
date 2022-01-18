<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageProtection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'user_id', 'is_protected', 'reason'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_protections';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;

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
     * Get the user this version belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }
}
