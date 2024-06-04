<?php

namespace App\Models\User;

use App\Models\Model;

class InvitationCode extends Model {
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
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'user:id,name,rank_id,is_banned', 'recipient:id,name,rank_id,is_banned',
    ];

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
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that used this invitation code.
     */
    public function recipient() {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
