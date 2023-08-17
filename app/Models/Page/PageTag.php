<?php

namespace App\Models\Page;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

class PageTag extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'type', 'tag',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_tags';
    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the page this tag belongs to.
     */
    public function page() {
        return $this->belongsTo('App\Models\Page\Page');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include page tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTag($query) {
        return $query->where('type', 'page_tag');
    }

    /**
     * Scope a query to only include utility tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUtilityTag($query) {
        return $query->where('type', 'utility');
    }

    /**
     * Scope a query to include all forms of a tag.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $tag
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTagSearch($query, $tag) {
        return $query->where(function ($query) use ($tag) {
            $i = 0;
            foreach (Config::get('mundialis.page_tags') as $prefix) {
                if ($i == 0) {
                    $query->where('tag', $tag)->orWhere('tag', $prefix['prefix'].$tag);
                }
                if ($i > 0) {
                    $query->orWhere('tag', $prefix['prefix'].$tag);
                }
                $i++;
            }
        });
    }

    /**
     * Scope a query to only include prefixed tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrefixedTags($query) {
        return $query->where(function ($query) {
            $i = 0;
            foreach (Config::get('mundialis.page_tags') as $prefix) {
                if ($i == 0) {
                    $query->where('tag', 'regexp', $prefix['regex']);
                }
                if ($i > 0) {
                    $query->orWhere('tag', 'regexp', $prefix['regex']);
                }
                $i++;
            }
        });
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the prefix of a prefixed tag.
     *
     * @return string
     */
    public function getPrefixAttribute() {
        // Check the tag name against prefixes
        $matches = [];
        foreach (Config::get('mundialis.page_tags') as $prefix) {
            if ($matches == []) {
                preg_match($prefix['regex_alt'], $this->tag, $matches);
            }
        }

        // If the tag has a prefix, return the prefix
        if ($matches != []) {
            return $matches[0];
        } else {
            return null;
        }
    }

    /**
     * Get the base form of a prefixed tag.
     *
     * @return string
     */
    public function getBaseTagAttribute() {
        // Check the tag name against prefixes
        $matches = [];
        foreach (Config::get('mundialis.page_tags') as $prefix) {
            if ($matches == []) {
                preg_match($prefix['regex_alt'], $this->tag, $matches);
            }
        }

        // If the tag has a prefix, return the unprefixed tag
        if ($matches != []) {
            return $matches[1];
        } else {
            return $this->tag;
        }
    }

    /**
     * Get the page tag's url.
     *
     * @return string
     */
    public function getUrlAttribute() {
        return url('pages/tags/'.str_replace(' ', '_', $this->baseTag));
    }

    /**
     * Get the page tag as a formatted link.
     *
     * @return string
     */
    public function getDisplayNameAttribute() {
        return '<a href="'.$this->url.'">'.$this->tag.'</a>';
    }

    /**
     * Get the page tag as a formatted link with base tag name.
     *
     * @return string
     */
    public function getDisplayNameBaseAttribute() {
        return '<a href="'.$this->url.'">'.$this->baseTag.'</a>';
    }

    /**
     * Checks if a tag has an associated navbox.
     *
     * @return bool
     */
    public function getHasNavboxAttribute() {
        // If the tag itself has a prefix, this is true by default
        if ($this->prefix) {
            return true;
        }
        // Else check if there are prefixed tags for this tag
        elseif ($this->tagSearch($this->tag)->prefixedTags()->count()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if a tag has associated events.
     *
     * @return bool
     */
    public function getHasTimelineAttribute() {
        $timePages = $this->page->subject('time')->whereIn('id', $this->tagSearch($this->baseTag)->tag()->pluck('page_id')->toArray())->get()->filter(function ($page) {
            if (isset($page->parent_id) || isset($page->data['date']['start'])) {
                return true;
            }

            return false;
        });
        if ($timePages->count()) {
            return true;
        }

        return false;
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Fetch a list of all base tags, including the base forms
     * of those that only existi in prefixed form. Does not include
     * utility tags.
     *
     * @return array
     */
    public function listTags() {
        // Find all tags with prefixes
        $filter = $this->tag()->prefixedTags();

        // Cycle through them, fetching the tags themselves
        foreach ($filter->get() as $tag) {
            $matches = [];
            foreach (Config::get('mundialis.page_tags') as $prefix) {
                preg_match($prefix['regex_alt'], $tag, $matches);
                if ($matches != []) {
                    $tags[] = $matches[1];
                    $matches = [];
                }
            }
        }

        $returnTags = $this->tag()->whereNotIn('tag', $filter->pluck('tag')->toArray())->pluck('tag', 'tag')->unique()->toArray();

        // Check to see if the tags exist already, and if not, add to the list
        if (isset($tags) && count($tags)) {
            foreach ($tags as $tag) {
                if (!$this->where('tag', $tag)->exists()) {
                    $returnTags[$tag] = $tag;
                }
            }
        }

        return $returnTags;
    }

    /**
     * Gets navbox information, namely: the hub tag,
     * and all context pages.
     *
     * @param \App\Models\User\User $user
     *
     * @return array
     */
    public function navboxInfo($user = null) {
        $info = [];
        // Check for/get hub tag
        if ($this->tagSearch('Hub:'.$this->baseTag)->first() && $this->tagSearch('Hub:'.$this->baseTag)->first()->page) {
            $info['hub'] = $this->tagSearch('Hub:'.$this->baseTag)->first();
        }
        // Check for/get context tags
        if ($this->tagSearch('Context:'.$this->baseTag)->count()) {
            $info['context'] = $this->tagSearch('Context:'.$this->baseTag)->get();
        }

        // Fetch context pages and group by subject
        if (isset($info['context'])) {
            foreach ($info['context'] as $contextTag) {
                if ($contextTag->page && $contextTag->page->is_visible || ($user && $user->canWrite)) {
                    $info['pages'][] = $contextTag->page;
                }
            }
            $info['pages'] = collect($info['pages']);
            foreach ($info['pages'] as $page) {
                $info['subjects'][$page->category->subject['key']][] = $page;
            }
        }

        if ($info != []) {
            return $info;
        }

        return null;
    }
}
