<?php

/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
|
| These are settings that affect how the site works.
| These are not expected to be changed often or on short schedule and are
| therefore separate from the settings modifiable in the admin panel.
| It's highly recommended that you do any required modifications to this file
| as well as config/app.php before you start using the site.
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Site Name
    |--------------------------------------------------------------------------
    |
    | This differs from the app name in that it is allowed to contain spaces
    | (APP_NAME in .env cannot take spaces). This will be displayed on the
    | site wherever the name needs to be displayed.
    |
    */
    'site_name'                  => 'Mundialis',

    /*
    |--------------------------------------------------------------------------
    | Site Description
    |--------------------------------------------------------------------------
    |
    | This is the description used for the site in meta tags-- previews
    | displayed on various social media sites, discord, and the like.
    | It is not, however, displayed on the site itself. This should be kept short and snappy!
    |
    */
    'site_desc'                  => 'A Mundialis site',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | This is the current version of Mundialis that your site is using.
    | Do not change this value!
    |
    */
    'version'                    => '2.2.5',

    /*
    |--------------------------------------------------------------------------
    | Image Thumbnail Dimensions & Processing
    |--------------------------------------------------------------------------
    |
    | This affects the dimensions used by the image thumbnail cropper.
    | Using a smallish size is recommended to reduce the amount of time
    | needed to load page indexes.
    |
    | 0: Default thumbnail cropping behavior. 1: Watermark thumbnails.
    | Expects the whole of the character to be visible in the thumbnail.
    |
    */
    'image_thumbnails'           => [
        'width'  => 300,
        'height' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Thumbnail Automation (Replaces Cropper)
    |--------------------------------------------------------------------------
    |
    | This feature will replace the thumbnail cropper as option at image uploads.
    | It will automatically add transparent borders to the images to make them square,
    | based on the bigger dimension (between width/height).
    | Thumbnails will effectively be small previews of the full images.
    | This feature will not replace the manual uploading of thumbnails.
    |
    | Simply change to "1" to enable, or keep at "0" to disable.
    |
    */
    'image_thumbnail_automation' => 0,

    /*
    |--------------------------------------------------------------------------
    | Enable Backups
    |--------------------------------------------------------------------------
    |
    | This feature will create a daily backup automatically. Requires a cron job
    | to be set up as well!
    | Note that it's recommended to configure config/backup.php as desired as well,
    | especially to adjust the location backups are saved to (by default, they are
    | saved locally). Note that even if this is disabled, backups can still be ran
    | manually using the backup:run command.
    |
    | Simply change to "1" to enable, or keep at "0" to disable.
    |
    */
    'enable_backups'             => 0,
];
