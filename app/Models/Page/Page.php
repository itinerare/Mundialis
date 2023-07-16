<?php

namespace App\Models\Page;

use App\Models\Model;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Page extends Model {
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'title', 'summary', 'is_visible', 'parent_id', 'image_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;

    /**
     * Validation rules for page creation.
     *
     * @var array
     */
    public static $createRules = [
        'title' => 'required',
    ];

    /**
     * Validation rules for page updating.
     *
     * @var array
     */
    public static $updateRules = [
        'title' => 'required',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the category this page belongs to.
     */
    public function category() {
        return $this->belongsTo('App\Models\Subject\SubjectCategory', 'category_id');
    }

    /**
     * Get the parent this page belongs to.
     */
    public function parent() {
        if ($this->category->subject['key'] == 'time') {
            return $this->belongsTo('App\Models\Subject\TimeChronology', 'parent_id');
        }

        return $this->belongsTo('App\Models\Page\Page', 'parent_id');
    }

    /**
     * Get this page's primary image.
     */
    public function image() {
        return $this->hasOne('App\Models\Page\PageImage', 'id', 'image_id');
    }

    /**
     * Get this page's images.
     */
    public function images() {
        return $this->belongsToMany('App\Models\Page\PageImage')->using('App\Models\Page\PagePageImage')->withPivot('is_valid');
    }

    /**
     * Get this page's versions.
     */
    public function versions() {
        return $this->hasMany('App\Models\Page\PageVersion');
    }

    /**
     * Get this page's protection records.
     */
    public function protections() {
        return $this->hasMany('App\Models\Page\PageProtection');
    }

    /**
     * Get this page's tags.
     */
    public function tags() {
        return $this->hasMany('App\Models\Page\PageTag')->where('type', '!=', 'utility');
    }

    /**
     * Get this page's utility tags.
     */
    public function utilityTags() {
        return $this->hasMany('App\Models\Page\PageTag')->where('type', 'utility');
    }

    /**
     * Get this page's associated links.
     */
    public function links() {
        return $this->hasMany('App\Models\Page\PageLink', 'parent_id')->where('parent_type', 'page');
    }

    /**
     * Get this page's associated links.
     */
    public function linked() {
        return $this->hasMany('App\Models\Page\PageLink', 'link_id');
    }

    /**
     * Get this page's relationships.
     */
    public function relationships() {
        return $this->hasMany('App\Models\Page\PageRelationship', 'page_one_id', 'id');
    }

    /**
     * Get this page's relationships.
     */
    public function related() {
        return $this->hasMany('App\Models\Page\PageRelationship', 'page_two_id', 'id');
    }

    /**
     * Get the page's watchers.
     */
    public function watchers() {
        return $this->hasManyThrough(
            'App\Models\User\User',
            'App\Models\User\WatchedPage',
            'page_id',
            'id',
            'id',
            'user_id'
        );
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include visible pages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User\User                 $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $user = null) {
        if ($user && $user->canWrite) {
            return $query;
        }

        return $query->where('is_visible', 1);
    }

    /**
     * Scope a query to only include pages of a given subject.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $subject
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubject($query, $subject) {
        return $query->whereIn(
            'category_id',
            SubjectCategory::where('subject', $subject)->pluck('id')->toArray()
        );
    }

    /**
     * Scope a query to only include pages with a given title.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $title
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTitleSearch($query, $title) {
        return $query->where('title', $title);
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the page's most recent version.
     *
     * @return \App\Models\Page\PageVersion
     */
    public function getVersionAttribute() {
        return $this->versions()->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Get the page's most recent protection record.
     *
     * @return \App\Models\Page\PageProtection
     */
    public function getProtectionAttribute() {
        if (!$this->protections->count()) {
            return null;
        }

        return $this->protections()->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Get the current version's data attribute as an associative array.
     *
     * @return array
     */
    public function getDataAttribute() {
        if (!$this->versions->count() || !isset($this->version->data['data'])) {
            return null;
        }

        return $this->version->data['data'];
    }

    /**
     * Get the current version's parsed data attribute as an associative array.
     *
     * @return array
     */
    public function getParsedDataAttribute() {
        if (!$this->versions->count() || !isset($this->version->data['data']['parsed'])) {
            return null;
        }

        return $this->version->data['data']['parsed'];
    }

    /**
     * Get the page's slug.
     *
     * @return string
     */
    public function getSlugAttribute() {
        $string = str_replace(' ', '-', $this->title);

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    /**
     * Get the page's url.
     *
     * @return string
     */
    public function getUrlAttribute() {
        return url('pages/'.$this->id.'.'.$this->slug);
    }

    /**
     * Get the formatted page title. Handles disambiguation.
     *
     * @return string
     */
    public function getDisplayTitleAttribute() {
        // Check if there is more than one page with this title
        if ($this->titleSearch($this->title)->count() > 1) {
            $titlePages = $this->titleSearch($this->title);

            // Check if there is more than one page within this subject with this title
            if ($titlePages->whereIn('category_id', SubjectCategory::where('subject', $this->category->subject['key'])->pluck('id')->toArray())->count() > 1) {
                return $this->title.' ('.$this->category->subject['term'].'/'.$this->category->name.')';
            }

            // Otherwise just note the subject
            return $this->title.' ('.$this->category->subject['term'].')';
        }

        return $this->title;
    }

    /**
     * Get the page title as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute() {
        // Check to see if this page is currently being viewed/the link would be redundant
        if (url()->current() == $this->url) {
            return $this->displayTitle.(!$this->is_visible ? ' <i class="fas fa-eye-slash" data-toggle="tooltip" title="This page is currently hidden"></i>' : '');
        }
        // Otherwise, return the link as usual
        return '<a href="'.$this->url.'" class="text-primary"'.($this->summary ? ' data-toggle="tooltip" title="'.$this->summary.'"' : '').'>'.$this->displayTitle.'</a>'.(!$this->is_visible ? ' <i class="fas fa-eye-slash" data-toggle="tooltip" title="This page is currently hidden"></i>' : '');
    }

    /**
     * Get the page's tags for use by the tag entry field.
     *
     * @return string
     */
    public function getEntryTagsAttribute() {
        $tags = [];
        foreach ($this->tags()->pluck('tag') as $tag) {
            $tags[] = ['tag' => $tag];
        }

        return json_encode($tags);
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Attempt to calculate the age of a person by comparing two time arrays.
     *
     * @param array $birth
     * @param array $current
     *
     * @return string
     */
    public function personAge($birth, $current) {
        if ((isset($birth['date']) && isset($birth['date'][
            str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))
            ])) &&
        (isset($current['date']) && isset($current['date'][
            str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))
            ])) &&
        (isset($birth['chronology']) && isset($current['chronology']) &&
        ($birth['chronology'] == $current['chronology']))) {
            // Cycle through both birth and current dates,
            // collecting set values, then turn them into a "datestring"
            foreach ($birth['date'] as $date) {
                // Trim leading zeros on each while collecting
                $birthString[] = ltrim($date, 0);
            }
            $birthString = implode('', array_reverse($birthString));

            foreach ($current['date'] as $date) {
                $currentString[] = ltrim($date, 0);
            }
            $currentString = implode('', array_reverse($currentString));

            // Just calculate the rough age either for use or for checking against
            $roughYear = $current['date'][str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))] -
            $birth['date'][str_replace(' ', '_', strtolower(TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->first()->name))];

            // If the birth and current strings are the same, more precisely
            // calculate and return age
            if (strlen($birthString) == strlen($currentString)) {
                $ageString = floor($currentString - $birthString);

                if ($roughYear == $ageString) {
                    return $roughYear;
                } else {
                    return floor($ageString / pow(10, (strlen($ageString) - 2)));
                }
            }

            // Failing that, just return the rough age
            return $roughYear;
        }

        return null;
    }

    /**
     * Gather a person's family from extant relationships.
     *
     * @param string $type
     *
     * @return array
     */
    public function personRelations($type = null) {
        // Gather family types depending on the type
        switch ($type) {
            case 'parents':
                $familyTypes = [
                    'familial_parent' => 'Parent',
                ];
                break;
            case 'children':
                $familyTypes = [
                    'familial_child'   => 'Child',
                    'familial_adopted' => 'Child (Adopted)',
                ];
                break;
            case 'siblings':
                $familyTypes = [
                    'familial_sibling' => 'Sibling',
                ];
                break;
            default:
                $familyTypes =
                    Config::get('mundialis.people_relationships.Familial') +
                    Config::get('mundialis.people_relationships.Romantic') +
                    ['platonic_partner' => 'Partner (platonic)'];
                break;
        }

        // Gather relationships with these types
        $family = $this->relationships()->get()->filter(function ($relationship) use ($familyTypes) {
            return isset($familyTypes[$relationship->type_two]);
        });
        $family = $family->concat($this->related()->get()->filter(function ($related) use ($familyTypes) {
            return isset($familyTypes[$related->type_one]);
        }));

        // Cycle through family members and assemble a stripped-down array for convenience
        foreach ($family as $familyMember) {
            if ($familyMember->page_one_id == $this->id) {
                $familyMembers[] = [
                    'link'        => $familyMember,
                    'type'        => $familyMember->type_two,
                    'displayType' => $familyMember->displayTypeTwo,
                    'page'        => $familyMember->pageTwo,
                ];
            } elseif ($familyMember->page_two_id == $this->id) {
                $familyMembers[] = [
                    'link'        => $familyMember,
                    'type'        => $familyMember->type_one,
                    'displayType' => $familyMember->displayTypeOne,
                    'page'        => $familyMember->pageOne,
                ];
            }
        }

        $familyMembers = collect($familyMembers ?? null);

        if ($familyMembers->count()) {
            return $familyMembers;
        }

        return null;
    }

    /**
     * Organize events in chronological order.
     *
     * @param \App\Models\User\User $user
     * @param int                   $chronology
     * @param array                 $tags
     *
     * @return \Illuminate\Support\Collection
     */
    public function timeOrderedEvents($user = null, $chronology = null, $tags = null) {
        // Gather relevant pages
        $timePages = $this->subject('time')->visible($user ? $user : null);
        if ($chronology) {
            $timePages = $timePages->where('parent_id', $chronology);
        } else {
            $timePages = $timePages->whereNull('parent_id');
        }
        if ($tags) {
            foreach ($tags as $tag) {
                $timePages = $timePages->whereIn('id', PageTag::tagSearch($tag)->tag()->pluck('page_id')->toArray());
            }
        }
        $timePages = $timePages->get()->filter(function ($page) use ($chronology) {
            if ((isset($page->parent_id) && $chronology) ||
            isset($page->data['date']['start'])) {
                return true;
            }

            return false;
        });

        // Get list of date-enabled divisions
        $dateDivisions = TimeDivision::dateEnabled()->orderBy('sort', 'DESC')->get();
        foreach ($dateDivisions as $division) {
            $divisionKeys[] = $division->id;
        }

        // Recursively group events
        $timePages = $this->timeGroupEvents($timePages, $divisionKeys);

        if ($timePages->count()) {
            return $timePages;
        }

        return null;
    }

    /**
     * Help organize events recursively.
     *
     * @param \Illuminate\Support\Collection $group
     * @param array                          $divisionKeys
     * @param int                            $i
     *
     * @return \Illuminate\Support\Collection
     */
    public function timeGroupEvents($group, $divisionKeys, $i = 0) {
        // Group the pages by the current division
        $group = $group->groupBy(function ($page) use ($divisionKeys, $i) {
            if (isset($page->data['date']['start'][$divisionKeys[$i]])) {
                return $page->data['date']['start'][$divisionKeys[$i]];
            }

            return '00';
        })->sortBy(function ($group, $key) {
            return $key;
        });

        // See if there is a smaller division
        if (isset($divisionKeys[$i + 1])) {
            // And if so, group the pages by it, etc
            $group = $group->map(function ($subGroup) use ($divisionKeys, $i) {
                return $this->timeGroupEvents($subGroup, $divisionKeys, $i + 1);
            });
        }

        return $group;
    }
}
