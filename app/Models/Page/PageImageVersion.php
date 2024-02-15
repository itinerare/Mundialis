<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageImageVersion extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_image_id', 'user_id', 'hash', 'extension',
        'use_cropper', 'x0', 'x1', 'y0', 'y1',
        'type', 'reason', 'is_minor', 'data',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_image_versions';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
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
     * Get the user this version belongs to.
     */
    public function user() {
        return $this->belongsTo('App\Models\User\User');
    }

    /**
     * Get the image this version belongs to.
     */
    public function image() {
        return $this->belongsTo('App\Models\Page\PageImage', 'page_image_id')->withTrashed();
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Gets the file name of the model's image.
     *
     * @return string
     */
    public function getImageFileNameAttribute() {
        return $this->image->id.'_'.$this->id.'_'.$this->hash.'.'.$this->extension;
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getImageUrlAttribute() {
        if (!isset($this->hash)) {
            return null;
        }

        return asset($this->image->imageDirectory.'/'.$this->imageFileName);
    }

    /**
     * Gets the file name of the model's thumbnail image.
     *
     * @return string
     */
    public function getThumbnailFileNameAttribute() {
        return $this->image->id.'_'.$this->id.'_'.$this->hash.'_th.'.$this->extension;
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute() {
        if (!isset($this->hash)) {
            return null;
        }

        return asset($this->image->imageDirectory.'/'.$this->thumbnailFileName);
    }
}
