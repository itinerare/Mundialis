<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Page Tags
    |--------------------------------------------------------------------------
    |
    | This is a list of page tag prefixes that can be used in page tags. By default,
    | these do not do anything specific; additional functionality can be programmed
    | for specific prefixes if desired. However, presence here allows prefixes to be
    | disregarded when searching tags/for pages taged with these prefixes to appear
    | in search results for the base, unprefixed tag.
    |
    | This file can be safely added to, but do not remove the two default prefixes
    | (Hub: and Context:) as this will cause site functionality to break.
    |
    */

    'hub' => [
        'prefix' => 'Hub:',
        'regex' => 'Hub:([A-Za-z0-9_-_\s]+)',
        'regex_alt' => '/\Hub:([A-Za-z0-9_-_\s]+)/',
    ],

    'context' => [
        'prefix' => 'Context:',
        'regex' => 'Context:([A-Za-z0-9_-_\s]+)',
        'regex_alt' => '/\Context:([A-Za-z0-9_-_\s]+)/',
    ],
];
