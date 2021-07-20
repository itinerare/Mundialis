<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Model;

class PageImage extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash', 'extension', 'description', 'is_visible',
        'use_cropper', 'x0', 'x1', 'y0', 'y1',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_images';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;

    /**
     * Validation rules for image creation.
     *
     * @var array
     */
    public static $createRules = [
        'creator_url.*' => 'nullable|url',
        'image' => 'required|mimes:jpeg,gif,png|max:20000',
    ];

    /**
     * Validation rules for image updating.
     *
     * @var array
     */
    public static $updateRules = [
        'creator_url.*' => 'nullable|url',
        'image' => 'nullable|mimes:jpeg,gif,png|max:20000',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the page this image belongs to.
     */
    public function creators()
    {
        return $this->hasMany('App\Models\Page\PageImageCreator');
    }

    /**
     * Get the page this image belongs to.
     */
    public function pages()
    {
        return $this->belongsToMany('App\Models\Page\Page')->using('App\Models\Page\PagePageImage')->withPivot('is_valid');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include visible pages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User\User                  $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $user = null)
    {
        if($user && $user->canWrite) return $query;
        return $query->where('is_visible', 1);
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Gets the file directory containing the model's image.
     *
     * @return string
     */
    public function getImageDirectoryAttribute()
    {
        return 'images/pages/'.floor($this->id / 1000);
    }

    /**
     * Gets the file name of the model's image.
     *
     * @return string
     */
    public function getImageFileNameAttribute()
    {
        return $this->id . '_'.$this->hash.'.'.$this->extension;
    }

    /**
     * Gets the path to the file directory containing the model's image.
     *
     * @return string
     */
    public function getImagePathAttribute()
    {
        return public_path($this->imageDirectory);
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if(!isset($this->hash)) return null;
        return asset($this->imageDirectory . '/' . $this->imageFileName);
    }

    /**
     * Gets the file name of the model's thumbnail image.
     *
     * @return string
     */
    public function getThumbnailFileNameAttribute()
    {
        return $this->id . '_'.$this->hash.'_th.'.$this->extension;
    }

    /**
     * Gets the path to the file directory containing the model's thumbnail image.
     *
     * @return string
     */
    public function getThumbnailPathAttribute()
    {
        return $this->imagePath;
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        if(!isset($this->hash)) return null;
        return asset($this->imageDirectory . '/' . $this->thumbnailFileName);
    }

}
