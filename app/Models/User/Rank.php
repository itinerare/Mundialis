<?php

namespace App\Models\User;

use App\Models\Model;

class Rank extends Model
{
    /**
     * Validation rules for ranks.
     *
     * @var array
     */
    public static $rules = [
        'name'        => 'required|between:3,100',
        'description' => 'nullable',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'sort',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ranks';

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Check if the rank is the admin rank.
     *
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        if ($this->id == self::orderBy('sort', 'DESC')->first()->id) {
            return true;
        }

        return false;
    }

    /**
     * Check if the rank is the admin rank.
     *
     * @return bool
     */
    public function getCanWriteAttribute()
    {
        if ($this->id == self::orderBy('sort', 'DESC')->skip(1)->first()->id || $this->isAdmin) {
            return true;
        }

        return false;
    }
}
