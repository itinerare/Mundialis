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

    'people'   => [
        'name'        => 'People',
        'description' => 'Pages for this subject represent people within or relevant to your project. These can include characters, both significant and not, NPCs, etc.',
        'term'        => 'Person',
        'segments'    => [
            // This is used for documenting special fields included in the subjects' templates
            // as a matter of course.
            'infobox' => [
                'name'        => [
                    'name'        => 'Name (Optional)',
                    'description' => 'This is provided as an optional convenience in the event that you want to have a person\'s name or full name above the birth and death information in a page\'s infobox, as all other infobox fields follow after.',
                ],
                'birth-death' => [
                    'name'        => 'Birth & Death (Optional)',
                    'description' => 'These allow setting of the time and place of birth and/or death. If both are set, and both times are within the same <a href="/admin/data/time/chronology">chronology</a>, the site will attempt to calculate age at time of death (assuming that the greatest <a href="/admin/data/time/divisions">division of time</a> corresponds to years). This can be overridden by manually setting an age. Date settings use either a single generic year field (if no divisions of time are set) or date fields constructed according to the divisions enabled for use in dates.',
                ],
            ],
        ],
        // Whether or not dates are displayed on and around pages in this subject/if a helper class for date display must be provided.
        'hasDates' => true,
        // Which common options (chronologies, places, etc.) need to be provided when creating or editing pages in this subject.
        'editing'  => [
            'placeOptions'      => true,
            'chronologyOptions' => true,
        ],
    ],

    'places'   => [
        'name'        => 'Places',
        'description' => 'Pages for this subject represent locations within or relevant to your project, big and small.',
        'term'        => 'Place',
        'segments'    => [
            'general properties' => [
                'parent' => [
                    'name'        => 'Parent Place (Optional)',
                    'description' => 'The location that the location being edited exists within. For instance, countries exist within continents, neighborhoods exist within cities, etc.',
                ],
            ],
        ],
        'editing' => [
            'placeOptions' => true,
        ],
    ],

    'species'  => [
        'name'        => 'Flora & Fauna',
        'description' => 'Pages for this subject represent species, plant and/or animal, within or relevant to your project.',
        'term'        => 'Species',
    ],

    'things'   => [
        'name'        => 'Things',
        'description' => 'Pages for this subject represent inanimate objects within or relevant to your project. This can include anything from rather mundane things like clothing, tools, and food to objects of great rarity or import.',
        'term'        => 'Thing',
    ],

    'concepts' => [
        'name'        => 'Concepts',
        'description' => 'Pages for this subject represent abstract concepts within or relevant to your project. This covers an immense amount of potential material; everything from natural laws to social constructs, laws, and culture. Time and language are technically part of this umbrella as well, though are represented independently.',
        'term'        => 'Concept',
    ],

    'time'     => [
        'name'        => 'Time & Events',
        'description' => 'Pages for this subject correspond to individual events. While categories are used much like other subjects, this subject also has separate chronology settings that are used to order large spans of time (and events within them) as well as settings for divisions of time.',
        'term'        => 'Event',
        'pages'       => [
            // This is used to generate links/interface with the generic views; routes etc must
            // be manually set as these correspond to particular/specialized functions
            'divisions'  => '<i class="fas fa-stopwatch"></i> Divisions of Time',
            'chronology' => '<i class="far fa-clock"></i> Chronology',
        ],
        'segments'    => [
            'general properties' => [
                'chronology' => [
                    'name'        => 'Chronology (Optional)',
                    'description' => 'The chronological group that the event is in.',
                ],
                'date'       => [
                    'name'        => 'Date (Optional)',
                    'description' => 'The date of the event. Uses either a generic year field or the divisions of time enabled for use for dates. This isn\'t required, but if no date is provided, events will be assumed to take place at the beginning of their chronology. Depending on how your configure the latter, this may be fine or it may make it impossible to generate coherent timelines.',
                ],
            ],
        ],
        'hasDates' => true,
        'editing'  => [
            'chronologyOptions' => true,
        ],
    ],

    'language' => [
        'name'        => 'Language',
        'description' => 'Pages for this subject cover concepts about and around language, such as grammar, etc. However, the heart of this subject is the lexicon system, which allows you to categorize and enter vocabulary used within your project. This can be as simple as some special terms that are important or unqiue to your project, or as elaborate as whole languages or linguistic structures. Lexicon settings cover the parts of speech (noun, verb, etc.) used, while lexicon categories allow for organization of words-- as well as some advanced functions like specifying cases and auto-conjucation/declension.',
        'term'        => 'Lang. Page',
        'pages'       => [
            'lexicon-settings'   => '<i class="fas fa-list-ul"></i> Lexicon Settings',
            'lexicon-categories' => '<i class="far fa-list-alt"></i> Lexicon Categories',
        ],
    ],

    'misc'     => [
        'name'        => 'Miscellaneous',
        'term'        => 'Misc. Page',
        'description' => 'While the other subjects should be broad enough to cover most if not all use cases, this subject-of-sorts is here in the event that some content falls outside the others nonetheless.',
    ],
];
