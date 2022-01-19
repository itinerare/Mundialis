<?php

namespace App\Services;

use App\Models\Page\Page;
use App\Models\Page\PageLink;
use App\Models\Page\PageProtection;
use App\Models\Page\PageTag;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\User\WatchedPage;
use Config;
use DB;
use Notifications;

class PageManager extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Page Manager
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of pages.
    |
    */

    /**
     * Creates a page.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Page\Page|bool
     */
    public function createPage($data, $user)
    {
        DB::beginTransaction();

        try {
            // More specific validation
            if (Page::withTrashed()->where('title', $data['title'])->where('category_id', $data['category_id'])->exists()) {
                throw new \Exception('The page title has already been taken within this category.');
            }

            // Process data for storage
            $data = $this->processPageData($data);

            // Parse data for wiki-style links
            if (!$data['data'] = $this->parse_wiki_links($data['data'])) {
                throw new \Exception('An error occurred while parsing links.');
            }

            // Process data for recording
            if (isset($data['data'])) {
                $data['version'] = $this->processVersionData($data);
            } else {
                $data['version'] = null;
            }

            // Create page
            $page = Page::create($data);

            // If the page is wanted, update the existing page(s)
            if (PageLink::where('title', $page->displayTitle)->exists()) {
                foreach (PageLink::where('title', $page->displayTitle)->where('parent_type', 'page')->get() as $link) {
                    $version = PageVersion::find($link->parent->version->id);
                    $versionData = $version->data;
                    if (isset($versionData['data']['parsed'])) {
                        unset($versionData['data']['parsed']);
                    }
                    if (isset($versionData['data']['links'])) {
                        unset($versionData['data']['links']);
                    }

                    // Parse data and update version
                    $newData['data'] = $this->parse_wiki_links($versionData['data']);
                    $version->data = json_encode($newData);
                    $version->save();

                    // And update the links themselves
                    $link->update([
                        'link_id' => $page->id,
                        'title'   => null,
                    ]);
                }

                // As well as entries
                foreach (PageLink::where('title', $page->displayTitle)->where('parent_type', 'entry')->get() as $link) {
                    $entry = $link->parent;

                    $parsed = $this->parse_wiki_links((array) $entry->definition);

                    // Parse data and update version
                    $entry->parsed_definition = $parsed['parsed'][0];
                    $entry->save();

                    // And update the links themselves
                    $link->update([
                        'link_id' => $page->id,
                        'title'   => null,
                    ]);
                }
            }

            // Process links
            if (isset($data['data']['links'])) {
                $data['data']['links'] = $this->processLinks($page, $data['data']['links']);
            }

            // Process and create tags
            if (!$this->processTags($page, $data)) {
                throw new \Exception('Error occurred while updating tags.');
            }

            // Create version
            $version = $this->logPageVersion($page->id, $user->id, 'Page Created', isset($data['reason']) ? $data['reason'] : null, $data['version'], false);
            if (!$version) {
                throw new \Exception('An error occurred while saving page version.');
            }

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a page.
     *
     * @param \App\Models\Page\Page $page
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Page\Page|bool
     */
    public function updatePage($page, $data, $user)
    {
        DB::beginTransaction();

        try {
            // Ensure user can edit
            if (!$user->canEdit($page)) {
                throw new \Exception('You don\'t have permission to edit this page.');
            }

            // More specific validation
            if (Page::withTrashed()->where('title', $data['title'])->where('category_id', $page->category->id)->where('id', '!=', $page->id)->exists()) {
                throw new \Exception('The page title has already been taken within this category.');
            }

            // Process data for storage
            $data = $this->processPageData($data, $page);

            // Parse data for wiki-style links
            if (!$data['data'] = $this->parse_wiki_links($data['data'])) {
                throw new \Exception('An error occurred while parsing links.');
            }

            // Process links
            if (isset($data['data']['links'])) {
                $data['data']['links'] = $this->processLinks($page, $data['data']['links']);
            }

            // Process and update tags
            if (!$data = $this->processTags($page, $data)) {
                throw new \Exception('Error occurred while updating tags.');
            }

            // Process data for version recording
            if (isset($data['data'])) {
                $data['version'] = $this->processVersionData($data);
            } else {
                $data['version'] = null;
            }

            // Ascertain cause of version broadly
            if ($data['data'] == $page->data) {
                if ($data['title'] != $page->title) {
                    $versionType = 'Title Changed';
                } elseif (isset($data['parent_id']) && $data['parent_id'] != $page->parent_id) {
                    $versionType = 'Parent Changed';
                } elseif (isset($data['is_visible']) && $data['is_visible'] != $page->is_visible) {
                    $versionType = 'Visibility Changed';
                } elseif ((isset($data['page_tag']) && isset($page->version->data['page_tag']) && ($data['page_tag'] != $page->version->data['page_tag']) || (isset($data['page_tag']) && !isset($page->version->data['page_tag']) || (!isset($data['page_tag']) && isset($page->version->data['page_tag']))))) {
                    $versionType = 'Page Tags Changed';
                } elseif ((isset($data['utility_tag']) && isset($page->version->data['utility_tag']) && ($data['utility_tag'] != $page->version->data['utility_tag']) || (isset($data['utility_tag']) && !isset($page->version->data['utility_tag']) || (!isset($data['utility_tag']) && isset($page->version->data['utility_tag']))))) {
                    $versionType = 'Utility Tags Changed';
                }
            }
            if (!isset($versionType)) {
                $versionType = 'Page Updated';
            }

            // Create version
            $version = $this->logPageVersion($page->id, $user->id, $versionType, isset($data['reason']) ? $data['reason'] : null, $data['version'], isset($data['is_minor']) ? $data['is_minor'] : false);
            if (!$version) {
                throw new \Exception('An error occurred while saving page version.');
            }

            // Update page
            $page->update($data);

            // Send a notification to users that have watched this page
            if ($page->watchers->count()) {
                foreach ($page->watchers as $recipient) {
                    if ($recipient->id != $user->id) {
                        Notifications::create('WATCHED_PAGE_UPDATED', $recipient, [
                            'page_url'   => $page->url,
                            'page_title' => $page->title,
                            'user_url'   => $user->url,
                            'user_name'  => $user->name,
                        ]);
                    }
                }
            }

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a page's protection information.
     *
     * @param \App\Models\Page\Page $page
     * @param \App\Models\User\User $user
     * @param array                 $data
     *
     * @return bool
     */
    public function protectPage($page, $user, $data)
    {
        DB::beginTransaction();

        try {
            // Check toggle
            if (!isset($data['is_protected'])) {
                $data['is_protected'] = 0;
            }

            // Create new protection data
            $protection = PageProtection::create([
                'page_id'      => $page->id,
                'user_id'      => $user->id,
                'is_protected' => $data['is_protected'],
                'reason'       => $data['reason'],
            ]);
            if (!$protection) {
                throw new \Exception('An error occurred while creating the protection record.');
            }

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Moves a page to a different category.
     *
     * @param \App\Models\Page\Page               $page
     * @param \App\Models\Subject\SubjectCategory $category
     * @param \App\Models\User\User               $user
     * @param string                              $reason
     *
     * @return bool
     */
    public function movePage($page, $category, $user, $reason)
    {
        DB::beginTransaction();

        try {
            // Ensure user can edit
            if (!$user->canEdit($page)) {
                throw new \Exception('You don\'t have permission to edit this page.');
            }

            // More specific validation
            if (Page::withTrashed()->where('title', $page->title)->where('category_id', $category->id)->where('id', '!=', $page->id)->exists()) {
                throw new \Exception('The page title has already been taken within the target category.');
            }

            // Note the old category
            $oldCategory = $page->category;

            // Update the page's category
            $page->update(['category_id' => $category->id]);

            // Create a version logging the move
            $version = $this->logPageVersion($page->id, $user->id, 'Page Moved from '.$oldCategory->name.' to '.$category->name, $reason, $page->version->data, false);
            if (!$version) {
                throw new \Exception('An error occurred while saving page version.');
            }

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Resets a page to a given version.
     *
     * @param \App\Models\Page\Page        $page
     * @param \App\Models\Page\PageVersion $version
     * @param \App\Models\User\User        $user
     * @param string                       $reason
     *
     * @return bool
     */
    public function resetPage($page, $version, $user, $reason)
    {
        DB::beginTransaction();

        try {
            // Ensure user can edit
            if (!$user->canEdit($page)) {
                throw new \Exception('You don\'t have permission to edit this page.');
            }

            // Double-check the title
            if (Page::withTrashed()->where('title', $version->data['title'])->where('id', '!=', $page->id)->exists()) {
                throw new \Exception('The page title has already been taken.');
            }

            // Update the page itself
            $page->update($version->data);

            // Create a version logging the reset
            $version = $this->logPageVersion($page->id, $user->id, 'Page Reset to Ver. #'.$version->id, $reason, $version->data, false);
            if (!$version) {
                throw new \Exception('An error occurred while saving page version.');
            }

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete a page.
     *
     * @param \App\Models\Page\Page $page
     * @param \App\Models\User\User $user
     * @param string                $reason
     * @param bool                  $forceDelete
     *
     * @return bool
     */
    public function deletePage($page, $user, $reason, $forceDelete = false)
    {
        DB::beginTransaction();

        try {
            // Ensure user can edit
            if (!$user->canEdit($page)) {
                throw new \Exception('You don\'t have permission to edit this page.');
            }

            if (Page::where('parent_id', $page->id)->count()) {
                throw new \Exception('A page exists with this as its parent. Please remove or reassign the page\'s parentage first.');
            }

            // Unset the parent ID of any pages with this as their parent
            // This should not be relevant given the check above, but just in case
            if (Page::where('parent_id', $page->id)->count()) {
                Page::where('parent_id', $page->id)->update([
                    'parent_id' => null,
            ]);
            }

            if ($forceDelete) {
                // Delete the page's versions
                $page->versions()->delete();

                // Delete the page's protection records
                $page->protections()->delete();

                // Check to see if any images are linked only to this page,
                // and if so, force delete them
                foreach ($page->images()->withTrashed()->get() as $image) {
                    if ($image->pages()->count() <= 1) {
                        if (!(new ImageManager)->deletePageImage($image, $user, null, true)) {
                            throw new \Exception('An error occurred deleting an image.');
                        }
                    }
                }

                // Detach any remaining images
                $page->images()->detach();

                // Delete the page's relationships if relevant
                if ($page->relationships->count()) {
                    $page->relationships()->delete();
                }

                // Finally, force-delete the page
                $page->forceDelete();
            } else {
                // Check to see if any images are linked only to this page,
                // and if so, soft-delete them
                foreach ($page->images as $image) {
                    if ($image->pages->count() == 1) {
                        if (!(new ImageManager)->deletePageImage($image, $user, 'Page Deleted')) {
                            throw new \Exception('An error occurred deleting an image.');
                        }
                    }
                }

                // Send a notification to users that have watched this page
                if ($page->watchers->count()) {
                    foreach ($page->watchers as $recipient) {
                        if ($recipient->id != $user->id) {
                            Notifications::create('WATCHED_PAGE_DELETED', $recipient, [
                                'page_title' => $page->title,
                                'user_url'   => $user->url,
                                'user_name'  => $user->name,
                            ]);
                        }
                    }
                }

                // Delete page watches
                if (WatchedPage::where('page_id', $page->id)->exists()) {
                    WatchedPage::where('page_id', $page->id)->delete();
                }

                // Create a version logging the deletion
                $version = $this->logPageVersion($page->id, $user->id, 'Page Deleted', $reason, $page->version->data, false);
                if (!$version) {
                    throw new \Exception('An error occurred while saving page version.');
                }

                // Delete the page
                $page->delete();
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Restore a deleted page.
     *
     * @param \App\Models\Page\Page $page
     * @param \App\Models\User\User $user
     * @param string                $reason
     *
     * @return bool
     */
    public function restorePage($page, $user, $reason)
    {
        DB::beginTransaction();

        try {
            // First, restore the page itself
            $page->restore();

            // Then, attempt to restore any images that were soft-deleted by virtue of only
            // being linked to the page when it was deleted
            foreach ($page->images()->withTrashed()->whereNotNull('deleted_at')->get() as $image) {
                if ($image->pages()->count() == 1) {
                    if (!(new ImageManager)->restorePageImage($image, $user, 'Page Restored')) {
                        throw new \Exception('An error occurred restoring an image.');
                    }
                }
            }

            // Finally, create a version logging the restoration
            $version = $this->logPageVersion($page->id, $user->id, 'Page Restored', $reason, $page->version->data, false);
            if (!$version) {
                throw new \Exception('An error occurred while saving page version.');
            }

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Records a new page version.
     *
     * @param int    $pageId
     * @param int    $userId
     * @param string $type
     * @param string $reason
     * @param array  $data
     * @param bool   $isMinor
     *
     * @return \App\Models\Page\PageVersion|bool
     */
    public function logPageVersion($pageId, $userId, $type, $reason, $data, $isMinor = false)
    {
        try {
            $version = PageVersion::create([
                'page_id'  => $pageId,
                'user_id'  => $userId,
                'type'     => $type,
                'reason'   => $reason,
                'is_minor' => $isMinor,
                'data'     => json_encode($data),
            ]);

            return $version;
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return false;
    }

    /**
     * Processes page data for storage.
     *
     * @param array                $data
     * @param App\Models\Page\Page $page
     *
     * @return array
     */
    private function processPageData($data, $page = null)
    {
        // Fetch category-- either from the page if it already exists, or from the category ID
        $category = $page ? $page->category : SubjectCategory::where('id', $data['category_id'])->first();

        // Record the introduction as necessary
        $data['data']['description'] = isset($data['description']) ? $data['description'] : null;
        if (!isset($data['is_visible'])) {
            $data['is_visible'] = 0;
        }

        // Cycle through the category's form fields
        // Data is recorded here in a flat array/only according to key, as keys should not
        // be duplicated in a template, and the template accounts for form building as well
        // as page formatting
        foreach ($category->formFields as $key=>$field) {
            $data['data'][$key] = isset($data[$key]) ? $data[$key] : null;
        }

        if (isset($data['page_tag'])) {
            $data['page_tag'] = explode(',', $data['page_tag']);
        }

        // Process any subject-specific data
        switch ($category->subject['key']) {
            case 'people':
                // Record name
                $data['data']['people_name'] = isset($data['people_name']) ? $data['people_name'] : null;

                // Record birth and death data
                foreach (['birth', 'death'] as $segment) {
                    if (isset($data[$segment.'_place_id']) || isset($data[$segment.'_chronology_id'])) {
                        $data['data'][$segment] = [
                        'place'      => isset($data[$segment.'_place_id']) ? $data[$segment.'_place_id'] : null,
                        'chronology' => isset($data[$segment.'_chronology_id']) ? $data[$segment.'_chronology_id'] : null,
                    ];
                    }
                    foreach ((new TimeDivision)->dateFields() as $key=>$field) {
                        if (isset($data[$segment.'_'.$key])) {
                            $data['data'][$segment]['date'][$key] = isset($data[$segment.'_'.$key]) ? $data[$segment.'_'.$key] : null;
                        }
                    }
                }
                break;
            case 'places':
                // Record parent location
                $data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : null;
                break;
            case 'time':
                // Record chronology
                $data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : null;
                // Record dates
                foreach (['start', 'end'] as $segment) {
                    foreach ((new TimeDivision)->dateFields() as $key=>$field) {
                        if (isset($data['date_'.$segment.'_'.$key])) {
                            $data['data']['date'][$segment][$key] = isset($data['date_'.$segment.'_'.$key]) ? $data['date_'.$segment.'_'.$key] : null;
                        }
                    }
                }
                break;
        }

        return $data;
    }

    /**
     * Processes tags.
     *
     * @param \App\Models\Page\Page $page
     * @param array                 $data
     *
     * @return array
     */
    private function processLinks($page, $data)
    {
        DB::beginTransaction();

        try {
            // If the page already has links...
            if ($page->links()->count()) {
                $page->links()->delete();
            }

            foreach ($data as $link) {
                if ((isset($link['link_id']) && !$page->links()->where('link_id', $link['link_id'])->first()) || (isset($link['title']) && !$page->links()->where('title', $link['title'])->first())) {
                    $link = PageLink::create([
                        'parent_id' => $page->id,
                        'link_id'   => isset($link['link_id']) ? $link['link_id'] : null,
                        'title'     => isset($link['title']) && !isset($link['link_id']) ? $link['title'] : null,
                    ]);
                    if (!$link) {
                        throw new \Exception('An error occurred while creating a link.');
                    }
                }
            }

            return $this->commitReturn($data);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Processes tags.
     *
     * @param \App\Models\Page\Page $page
     * @param array                 $data
     *
     * @return array
     */
    private function processTags($page, $data)
    {
        DB::beginTransaction();

        try {
            // Process utility tags
            if (isset($data['utility_tag'])) {
                foreach ($data['utility_tag'] as $tag) {
                    // Utility tag options are already set by the config,
                    // but just in case, perform some extra validation
                    if (Config::get('mundialis.utility_tags.'.$tag) == null) {
                        throw new \Exception('One or more of the specified utility tags is invalid.');
                    }
                }

                // If the page already has utility tags, check against these
                // and only modify as necessary
                if ($page->utilityTags->count()) {
                    $diff = [];

                    // Fetch existing tags
                    $data['old_tags']['utility'] = $page->utilityTags->pluck('tag')->toArray();

                    // Compare old and new arrays for differences
                    $diff['removed'] = array_diff($data['old_tags']['utility'], $data['utility_tag']);
                    $diff['added'] = array_diff($data['utility_tag'], $data['old_tags']['utility']);

                    // Delete removed tags
                    foreach ($diff['removed'] as $tag) {
                        $page->utilityTags()->tagSearch($tag)->delete();
                    }

                    // Create added tags
                    foreach ($diff['added'] as $tag) {
                        $tag = PageTag::create([
                            'page_id' => $page->id,
                            'type'    => 'utility',
                            'tag'     => $tag,
                        ]);
                        if (!$tag) {
                            throw new \Exception('An error occurred while creating a tag.');
                        }
                    }
                }
                // Otherwise, just create the tags
                else {
                    foreach ($data['utility_tag'] as $tag) {
                        $tag = PageTag::create([
                            'page_id' => $page->id,
                            'type'    => 'utility',
                            'tag'     => $tag,
                        ]);
                    }
                    if (!$tag) {
                        throw new \Exception('An error occurred while creating a tag.');
                    }
                }
            }
            // If utility tag data is not set, but the page has existing tags,
            // delete all existing tags
            elseif (!isset($data['utility_tag']) && $page->tags->count()) {
                $page->utilityTags()->delete();
            }

            // Process standard tags
            if (isset($data['page_tag'])) {
                // Check to see if any of the entered tags are hub tags, and if so, ensure
                // that a duplicate hub tag is not being added
                foreach ($data['page_tag'] as $tag) {
                    $matches = [];
                    preg_match(Config::get('mundialis.page_tags.hub.regex_alt'), $tag, $matches);
                    if ($matches != []) {
                        if (PageTag::tag()->where('tag', $tag)->where('page_id', '!=', $page->id)->exists()) {
                            throw new \Exception('A hub already exists for the tag '.$matches[1].'.');
                        }
                    }
                }

                // If the page already has tags, check against these
                // and only modify as necessary
                if ($page->tags->count()) {
                    $diff = [];

                    // Fetch existing tags
                    $data['old_tags']['page'] = $page->tags->pluck('tag')->toArray();

                    // Compare old and new arrays for differences
                    $diff['removed'] = array_diff($data['old_tags']['page'], $data['page_tag']);
                    $diff['added'] = array_diff($data['page_tag'], $data['old_tags']['page']);

                    // Delete removed tags
                    foreach ($diff['removed'] as $tag) {
                        $page->tags()->tagSearch($tag)->delete();
                    }

                    // Create added tags
                    foreach ($diff['added'] as $tag) {
                        $tag = PageTag::create([
                            'page_id' => $page->id,
                            'type'    => 'page_tag',
                            'tag'     => $tag,
                        ]);
                        if (!$tag) {
                            throw new \Exception('An error occurred while creating a tag.');
                        }
                    }
                }
                // Otherwise, just create the tags
                else {
                    foreach ($data['page_tag'] as $tag) {
                        $tag = PageTag::create([
                            'page_id' => $page->id,
                            'type'    => 'page_tag',
                            'tag'     => $tag,
                        ]);
                    }
                    if (!$tag) {
                        throw new \Exception('An error occurred while creating a tag.');
                    }
                }
            }
            // If page tag data is not set, but the page has existing tags,
            // delete all existing tags
            elseif (!isset($data['page_tag']) && $page->tags->count()) {
                $page->tags()->delete();
            }

            return $this->commitReturn($data);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Processes version data for storage.
     *
     * @param array $data
     *
     * @return array
     */
    private function processVersionData($data)
    {
        $versionData = [];

        // Gather the general data for recording
        $versionData['data'] = $data['data'];

        // Cycle through various fields not present in data
        $versionData = $versionData + [
            'title'       => $data['title'],
            'is_visible'  => $data['is_visible'],
            'summary'     => $data['summary'],
            'utility_tag' => isset($data['utility_tag']) ? $data['utility_tag'] : null,
            'page_tag'    => isset($data['page_tag']) ? $data['page_tag'] : null,
        ];
        if (isset($data['parent_id'])) {
            $versionData = $versionData + ['parent_id' => $data['parent_id']];
        }

        return $versionData;
    }
}
