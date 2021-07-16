<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    |
    | This is a list of subjects for pages, used to support some flexible routes
    | and generation of subject pages, among other things. You should not edit
    | this file unless you know precisely what you're doing.
    |
    | All subjects must have a set name (used for page generation) and should have
    | a description that lays out the purpose of the subject at least in brief.
    |
    */

    'people' => [
        'name' => 'People',
        'description' => 'Pages for this subject represent people within or relevant to your project. These can include characters, both significant and not, NPCs, etc.',
        'segments' => [
            // This is used for documenting special fields included in the subjects' templates
            // as a matter of course.
            'infobox' => [
                'birth-death' => [
                    'name' => 'Birth & Death (Optional)',
                    'description' => 'These allow setting of the time and place of birth and/or death. If both are set, and both times are within the same <a href="/admin/data/time/chronology">chronology</a>, the site will attempt to calculate age at time of death (assuming that the greatest <a href="/admin/data/time/divisions">division of time</a> corresponds to years). This can be overridden by manually setting an age. Date settings use either a single generic year field (if no divisions of time are set) or date fields constructed according to the divisions enabled for use in dates.'
                ]
            ]
        ]
    ],

    'places' => [
        'name' => 'Places',
        'description' => 'Pages for this subject represent locations within or relevant to your project, big and small.',
        'segments' => [
            'general properties' => [
                'parent' => [
                    'name' => 'Parent Location (Optional)',
                    'description' => 'The location that the location being edited exists within. For instance, countries exist within continents, neighborhoods exist within cities, etc.'
                ]
            ]
        ]
    ],

    'species' => [
        'name' => 'Flora & Fauna',
        'description' => 'Pages for this subject represent species, plant and/or animal, within or relevant to your project.'
    ],

    'things' => [
        'name' => 'Things',
        'description' => 'Pages for this subject represent inanimate objects within or relevant to your project. This can include anything from rather mundane things like clothing, tools, and food to objects of great rarity or import.'
    ],

    'concepts' => [
        'name' => 'Concepts',
        'description' => 'Pages for this subject represent abstract concepts within or relevant to your project. This covers an immense amount of potential material; everything from natural laws to social constructs, laws, and culture. Time and language are technically part of this umbrella as well, though are represented independently.'
    ],

    'time' => [
        'name' => 'Time & Events',
        'description' => 'Pages for this subject correspond to individual events. While categories are used much like other subjects, this subject also has separate chronology settings that are used to order large spans of time (and events within them) as well as settings for divisions of time.',
        'pages' => [
            // This is used to generate links/interface with the generic views; routes etc must
            // be manually set as these correspond to particular/specialized functions
            'divisions' => '<i class="fas fa-stopwatch"></i> Divisions of Time',
            'chronology' => '<i class="far fa-clock"></i> Chronology'
        ],
        'segments' => [
            'general properties' => [
                'chronology' => [
                    'name' => 'Chronology (Optional)',
                    'description' => 'The chronological group that the event is in.'
                ],
                'date' => [
                    'name' => 'Date (Optional)',
                    'description' => 'The date of the event. Uses either a generic year field or the divisions of time enabled for use for dates. This isn\'t required, but if no date is provided, events will be assumed to take place at the beginning of their chronology. Depending on how your configure the latter, this may be fine or it may make it impossible to generate coherent timelines.'
                ]
            ]
        ]
    ],

    'language' => [
        'name' => 'Language',
        'description' => 'Pages for this subject cover concepts about and around language, such as grammar, etc. However, the heart of this subject is the lexicon system, which allows you to categorize and enter vocabulary used within your project. This can be as simple as some special terms that are important or unqiue to your project, or as elaborate as whole languages or linguistic structures. Lexicon settings cover the parts of speech (noun, verb, etc.) used, while lexicon categories allow for organization of words-- as well as some advanced functions like specifying cases and auto-conjucation/declension.',
        'pages' => [
            'lexicon-settings' => '<i class="fas fa-list-ul"></i> Lexicon Settings',
            'lexicon-categories' => '<i class="far fa-list-alt"></i> Lexicon Categories'
        ]
    ],

    'misc' => [
        'name' => 'Miscellaneous',
        'description' => 'While the other subjects should be broad enough to cover most if not all use cases, this subject-of-sorts is here in the event that some content falls outside the others nonetheless.'
    ],
];
