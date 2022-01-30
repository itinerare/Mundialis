<?php

namespace App\Models\User;

use App\Models\Model;

class InvitationCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'user_id', 'recipient_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invitation_codes';
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
     * Get the user that created this invitation code.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    /**
     * Get the user that used this invitation code.
     */
    public function recipient()
    {
        return $this->belongsTo('App\Models\User\User', 'recipient_id');
    }
}
