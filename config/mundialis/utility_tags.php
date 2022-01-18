<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Utility Tags
    |--------------------------------------------------------------------------
    |
    | This is a list of utility tags that can be applied to pages and for which
    | maintenance reports can be viewed. These are used primarily for maintenance
    | functions and indicators, e.g. on-page alerts to indicate WIP pages, stubs,
    | etc., versus regular tags which are used for content organization.
    |
    | This file can be fairly safely customized and tags added/removed at will.
    | Make sure to follow the same formatting as the existing entries and include
    | all properties present in them, as they are all required.
    | Nonetheless, it's recommended not to have too many tags here. Also take care
    | to avoid any added tags' keys conflicting with existing special page routes.
    |
    */

    'wip' => [
        // Used for general site interface
        'name' => 'WIP Pages',
        // Used for selecting tags during page editing
        'label' => 'WIP Page',
        // Used for populating the alert on a tagged page
        'message' => 'This page is a work-in-progress! The information present on it may change or be incomplete.',
        'verb' => 'contributing to',
    ],

    'stub' => [
        'name' => 'Stubs',
        'label' => 'Stub',
        'message' => 'This page is a stub.',
        'verb' => 'expanding',
    ],

    'outdated' => [
        'name' => 'Outdated Pages',
        'label' => 'Outdated Page',
        'message' => 'The content of this page is outdated.',
        'verb' => 'updating',
    ],

    'cleanup' => [
        'name' => 'Pages Needing Clean-up',
        'label' => 'Needs Clean-up',
        'message' => 'This page needs clean-up for formatting or other reasons.',
        'verb' => 'resolving',
    ],
];
