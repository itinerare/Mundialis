<?php

return [

    /*
    |--------------------------------------------------------------------------
    | People/Relatonships
    |--------------------------------------------------------------------------
    |
    | This is a list of relationships for use with relationship tracking for people.
    | More types can be added at will, and can be removed -IF- they are not currently
    | in use -AND- are below the marked point-- otherwise doing so will cause errors.
    |
    | Types after the marked point are largely present for convenience, and function
    | more or less like entering in a custom type, but adding frequently types may
    | be useful/efficient since custom types must be entered in each time.
    |
    | Note that keys MUST BE UNIQUE WITHIN THIS FILE. It does not matter if they are
    | in different groups.
    |
    */

    // Custom-entered relationships won't be used for anything special,
    // but can be entered in per-person on the fly
    'Custom' => [
        'custom' => 'Custom',
    ],

    'Familial' => [
        'familial_parent' => 'Parent',
        'familial_child' => 'Child',
        'familial_sibling' => 'Sibling',
        // These are mostly here in the interest of accurate tracking for potential
        // family tree rendering
        'familial_adoptive' => 'Parent (Adoptive)',
        'familial_adopted' => 'Child (Adopted)',
    ],

    'Romantic' => [
        // All types in this category function more or less the same, any specific
        // types specified are largely here for convenience.

        // This is a catch-all for [involved, but not legally]
        'romantic_partner' => 'Partner (romantic)',
        // Likewise this is a catch-all for [involved, legally]
        'romantic_spouse' => 'Spouse',
        'romantic_custom' => 'Custom',
    ],

    'Platonic' => [
        'platonic_partner' => 'Partner (platonic)',
        // -- Types after this point can be safely removed if desired --------------------
        'platonic_bff' => 'Close Friend',
        'platonic_friend' => 'Friend',
        'platonic_circumstantial' => 'Friend (Circumstantial)',
        'platonic_mentor' => 'Mentor',
        'platonic_mentee' => 'Mentee',
    ],

    'Enmity' => [
        'enmity_enemy' => 'Enemy',
    ],
];
