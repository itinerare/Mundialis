<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    |
    | This is a list of subjects for pages, used to support some flexible routes
    | and page generation. You should not edit this file unless you know precisely
    | what you're doing.
    |
    */

    'people' => [
        'name' => 'People'
    ],

    'places' => [
        'name' => 'Places'
    ],

    'species' => [
        'name' => 'Flora & Fauna'
    ],

    'things' => [
        'name' => 'Things'
    ],

    'concepts' => [
        'name' => 'Concepts'
    ],

    'time' => [
        'name' => 'Time & Events',
        'description' => 'Pages for this subject correspond to individual events. While categories are used much like other subjects, this subject also has separate chronology settings that are used to order large spans of time (and events within them) as well as settings for divisions of time.',
        'pages' => [
            // This is used to generate links/interface with the generic views; routes etc must
            // be manually set as these correspond to particular/specialized functions
            'divisions' => '<i class="fas fa-stopwatch"></i> Divisions of Time',
            'chronology' => '<i class="far fa-clock"></i> Chronology'
        ]
    ],

    'language' => [
        'name' => 'Language'
    ],

    'misc' => [
        'name' => 'Miscellaneous',
        'description' => 'While the other subjects should be broad enough to cover most if not all use cases, this subject-of-sorts is here in the event that some content falls outside the others nonetheless.'
    ],
];
