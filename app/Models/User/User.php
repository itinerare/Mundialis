<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'rank_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get user settings.
     */
    public function settings()
    {
        return $this->hasOne('App\Models\User\UserSettings');
    }

    /**
     * Get user-editable profile data.
     */
    public function profile()
    {
        return $this->hasOne('App\Models\User\UserProfile');
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    /**
     * Get the user's rank data.
     */
    public function rank()
    {
        return $this->belongsTo('App\Models\User\Rank');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Checks if the user has an admin rank.
     *
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->rank->isAdmin;
    }

    /**
     * Checks if the user has an admin rank.
     *
     * @return bool
     */
    public function getCanWriteAttribute()
    {
        return $this->rank->canWrite;
    }

    /**
     * Gets the user's profile URL.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('user/'.$this->name);
    }

    /**
     * Gets the URL for editing the user in the admin panel.
     *
     * @return string
     */
    public function getAdminUrlAttribute()
    {
        return url('admin/users/'.$this->name.'/edit');
    }

    /**
     * Displays the user's name, linked to their profile page.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return ($this->is_banned ? '<strike>' : '') . '<a href="'.$this->url.'" class="display-user">'.$this->name.'</a>' . ($this->is_banned ? '</strike>' : '');
    }

    /**
     * Displays the user's avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return ($this->avatar);
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Check if a user can edit a specific page.
     *
     * @param  \App\Models\Page\Page     $page
     * @return bool
     */
    public function canEdit($page)
    {
        // Admins can always edit pages, so just return true
        if($this->isAdmin) return true;
        // Normally, users with write permissions will be able to edit,
        // but if a page is protected, they cannot
        if($this->canWrite) {
            if($page->protection) {
                if($page->protection->is_protected) return false;
            }
            else return true;
        }

        return false;
    }
}
