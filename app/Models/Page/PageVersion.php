<?php

namespace App\Models\Page;

use App\Models\Model;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageVersion extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'user_id', 'type', 'is_minor', 'reason', 'data',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_versions';

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
     * Get the page this version belongs to.
     */
    public function page() {
        return $this->belongsTo(Page::class)->withTrashed();
    }

    /**
     * Get the user this version belongs to.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the length of changes documented.
     *
     * @return int
     */
    public function getLengthAttribute() {
        if (!isset($this->attributes['data'])) {
            return null;
        }

        return strlen($this->attributes['data']);
    }

    /**
     * Get the length of changes documented as a formatted string.
     *
     * @return string
     */
    public function getLengthStringAttribute() {
        // Attempt to fetch the prior version
        $version = $this->all()->where('page_id', $this->page_id)->sortByDesc('created_at')->where('created_at', '<', $this->created_at)->first();

        // If there is a prior version, find the difference
        if ($version) {
            $difference = ($this->length - $version->length);
        }
        // Otherwise the difference is the length of this version
        else {
            $difference = $this->length;
        }

        return 'Length: '.$this->length.($difference != 0 ? ' <span class="text-'.($difference > 0 ? 'success' : 'danger').'">('.($difference > 0 ? '+' : '').$difference.')</span>' : null);
    }
}
