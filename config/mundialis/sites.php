<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sites
    |--------------------------------------------------------------------------
    |
    | This is a list of sites and appropriate regex for use in for formatting links.
    | Feel free to add more sites, as this file is used solely for link formatting.
    |
    */

    'deviantart' => [
        'full_name'    => 'deviantART',
        'display_name' => 'dA',
        'regex'        => '/deviantart\.com\/([A-Za-z0-9_-]+)/',
        'link'         => 'deviantart.com',
    ],

    'twitter'    => [
        'full_name'    => 'Twitter',
        'display_name' => 'twitter',
        'regex'        => '/twitter\.com\/([A-Za-z0-9_-]+)/',
        'link'         => 'twitter.com',
    ],

    'instagram'  => [
        'full_name'    => 'Instagram',
        'display_name' => 'ig',
        'regex'        => '/instagram\.com\/([A-Za-z0-9_-]+)/',
        'link'         => 'instagram.com',
    ],

    'tumblr'     => [
        'full_name'    => 'Tumblr',
        'display_name' => 'tumblr',
        'regex'        => '/([A-Za-z0-9_-]+)\.tumblr\.com/',
        'link'         => 'tumblr.com',
    ],

    'imgur'      => [
        'full_name'    => 'Imgur',
        'display_name' => 'imgur',
        'regex'        => '/imgur\.com\/user\/([A-Za-z0-9_-]+)/',
        'link'         => 'imgur.com/user/',
    ],

    'twitch'     => [
        'full_name'    => 'Twitch.tv',
        'display_name' => 'twitch',
        'regex'        => '/twitch\.tv\/([A-Za-z0-9_-]+)/',
        'link'         => 'twitch.tv',
    ],

    'toyhouse'   => [
        'full_name'    => 'Toyhou.se',
        'display_name' => 'TH',
        'regex'        => '/toyhou\.se\/([A-Za-z0-9_-]+)/',
        'link'         => 'toyhou.se',
    ],

    'artstation' => [
        'full_name'    => 'Artstation',
        'display_name' => 'artstation',
        'regex'        => '/artstation\.com\/([A-Za-z0-9_-]+)/',
        'link'         => 'artstation.com',
    ],

    'picarto'    => [
        'full_name'    => 'Picarto',
        'display_name' => 'picarto',
        'regex'        => '/picarto\.tv\/([A-Za-z0-9_-]+)/',
        'link'         => 'picarto.tv',
    ],
];
