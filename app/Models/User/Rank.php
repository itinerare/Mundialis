<?php

namespace App\Models\User;

use App\Models\Model;

class Rank extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'sort'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ranks';

    /**
     * Validation rules for ranks.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|between:3,100',
        'description' => 'nullable'
    ];

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
        if($this->id == Rank::orderBy('sort', 'DESC')->first()->id) return true;
        return false;
    }

    /**
     * Check if the rank is the admin rank.
     *
     * @return bool
     */
    public function getCanWriteAttribute()
    {
        if($this->id == 2 || $this->isAdmin) return true;
        return false;
    }

}
