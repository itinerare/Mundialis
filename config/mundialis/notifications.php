<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | A list of notification type IDs and the messages associated with them.
    |
    */

    // WATCHED_PAGE_UPDATED
    0 => [
        'name' => 'Watched Page Updated',
        'message' => 'A page you have watched (<a href="{page_url}">{page_title}</a>) was updated by <a href="{user_url}">{user_name}</a>. (<a href="{url}">View Watched Pages</a>)',
        'url' => 'account/watched-pages'
    ],

    // WATCHED_PAGE_IMAGE_UPDATED
    1 => [
        'name' => 'Watched Page Images Updated',
        'message' => 'A page you have watched (<a href="{page_url}">{page_title}</a>) has had its images updated by <a href="{user_url}">{user_name}</a>. (<a href="{url}">View Watched Pages</a>)',
        'url' => 'account/watched-pages'
    ],
];
