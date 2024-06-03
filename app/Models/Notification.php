<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'notification_type_id', 'is_unread', 'data',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

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
     * Get the user who owns notification.
     */
    public function user() {
        return $this->belongsTo('App\Models\User\User');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the data attribute as an associative array.
     *
     * @return array
     */
    public function getDataAttribute() {
        return json_decode($this->attributes['data'], true);
    }

    /**
     * Get the notification message using the stored data.
     *
     * @return array
     */
    public function getMessageAttribute() {
        $notification = config('mundialis.notifications.'.$this->notification_type_id);

        $message = $notification['message'];

        // Replace the URL...
        $message = str_replace('{url}', url($notification['url']), $message);

        // Replace any variables in data...
        $data = $this->data;
        if ($data && count($data)) {
            foreach ($data as $key => $value) {
                $message = str_replace('{'.$key.'}', $value, $message);
            }
        }

        return $message;
    }

    /**
     * Get the notification ID from type.
     *
     * @param mixed $type
     *
     * @return array
     */
    public static function getNotificationId($type) {
        return constant('self::'.$type);
    }

    /**********************************************************************************************

        CONSTANTS

    **********************************************************************************************/

    public const WATCHED_PAGE_UPDATED = 0;
    public const WATCHED_PAGE_IMAGE_UPDATED = 1;
    public const WATCHED_PAGE_DELETED = 2;
}
