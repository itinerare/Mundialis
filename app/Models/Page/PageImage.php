<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageImage extends Model {
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'is_visible',
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
        'creator_id.*'  => 'nullable|required_without:creator_url.*',
        'creator_url.*' => 'nullable|required_without:creator_id.*|url',
        'image'         => 'required|mimes:jpeg,gif,png|max:20000',
    ];

    /**
     * Validation rules for image updating.
     *
     * @var array
     */
    public static $updateRules = [
        'creator_id.*'  => 'nullable|required_without:creator_url.*',
        'creator_url.*' => 'nullable|required_without:creator_id.*|url',
        'image'         => 'nullable|mimes:jpeg,gif,png|max:20000',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the page this image belongs to.
     */
    public function creators() {
        return $this->hasMany('App\Models\Page\PageImageCreator');
    }

    /**
     * Get the page this image belongs to.
     */
    public function pages() {
        return $this->belongsToMany('App\Models\Page\Page')->using('App\Models\Page\PagePageImage')->withPivot('is_valid');
    }

    /**
     * Get this image's versions.
     */
    public function versions() {
        return $this->hasMany('App\Models\Page\PageImageVersion');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include visible pages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User\User                 $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $user = null) {
        if ($user && $user->canWrite) {
            return $query;
        }

        return $query->where('is_visible', 1)->whereRelation('pages', 'is_visible', 1);
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the image's most recent version.
     *
     * @return PageImageVersion
     */
    public function getVersionAttribute() {
        return $this->versions()->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Get the image's most recent version with image.
     *
     * @return PageImageVersion
     */
    public function getImageVersionAttribute() {
        return $this->versions()->whereNotNull('hash')->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Gets the file directory containing the model's image.
     *
     * @return string
     */
    public function getImageDirectoryAttribute() {
        return 'images/pages/'.floor($this->id / 1000);
    }

    /**
     * Gets the path to the file directory containing the model's image.
     *
     * @return string
     */
    public function getImagePathAttribute() {
        return public_path($this->imageDirectory);
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getImageUrlAttribute() {
        return $this->imageVersion->imageUrl;
    }

    /**
     * Gets the path to the file directory containing the model's thumbnail image.
     *
     * @return string
     */
    public function getThumbnailPathAttribute() {
        return $this->imagePath;
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute() {
        return $this->imageVersion->thumbnailUrl;
    }
}
