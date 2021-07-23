<?php namespace App\Services;

use App\Services\Service;

use DB;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Page\Page;
use App\Models\Page\PageVersion;

use App\Services\ImageManager;

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
     * @param  array                         $data
     * @param  \App\Models\User\User         $user
     * @return bool|\App\Models\Page\Page
     */
    public function createPage($data, $user)
    {
        DB::beginTransaction();

        try {
            // Process data for storage
            $data = $this->processPageData($data);

            // Process data for recording
            if(isset($data['data'])) $data['version'] = $this->processVersionData($data);
            else $data['version'] = null;

            // Create page
            $page = Page::create($data);

            // Create version
            $version = $this->logPageVersion($page->id, $user->id, 'Page Created', isset($data['reason']) ? $data['reason'] : null, $data['version'], false);
            if(!$version) throw Exception('An error occurred while saving page version.');

            return $this->commitReturn($page);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a page.
     *
     * @param  \App\Models\Page\Page     $page
     * @param  array                     $data
     * @param  \App\Models\User\User     $user
     * @return \App\Models\Page\Page|bool
     */
    public function updatePage($page, $data, $user)
    {
        DB::beginTransaction();

        try {
            // More specific validation
            if(Page::where('title', $data['title'])->where('id', '!=', $page->id)->exists()) throw new \Exception("The page title has already been taken.");

            // Process data for storage
            $data = $this->processPageData($data, $page);

            // Ascertain cause of version broadly
            if($data['data'] == $page->data) {
                if(isset($data['parent_id'])) if($data['parent_id'] != $page->parent_id)
                    $versionType = 'Parent Changed';
                elseif($data['is_visible'] != $page->is_visible)
                    $versionType = 'Visibility Changed';
            }
            if(!isset($versionType)) $versionType = 'Page Updated';

            // Process data for recording
            if(isset($data['data'])) $data['version'] = $this->processVersionData($data);
            else $data['version'] = null;

            // Create version
            $version = $this->logPageVersion($page->id, $user->id, $versionType, isset($data['reason']) ? $data['reason'] : null, $data['version'], isset($data['is_minor']) ? $data['is_minor'] : false);
            if(!$version) throw Exception('An error occurred while saving page version.');

            // Update page
            $page->update($data);

            return $this->commitReturn($page);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Resets a page to a given version.
     *
     * @param  \App\Models\Page\Page         $page
     * @param  \App\Models\Page\PageVersion  $version
     * @param  \App\Models\User\User         $user
     * @param  string                        $reason
     * @return bool
     */
    public function resetPage($page, $version, $user, $reason)
    {
        DB::beginTransaction();

        try {
            // Double-check the title
            if(Page::where('title', $version->data['title'])->where('id', '!=', $page->id)->exists()) throw new \Exception("The page title has already been taken.");

            // Update the page itself
            $page->update($version->data);

            // Create a version logging the reset
            $version = $this->logPageVersion($page->id, $user->id, 'Page Reset to Ver. #'.$version->id, $reason, $version->data, false);
            if(!$version) throw Exception('An error occurred while saving page version.');

            return $this->commitReturn($page);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Delete a page.
     *
     * @param  \App\Models\Page\Page     $page
     * @param  \App\Models\User\User     $user
     * @param  string                    $reason
     * @param  bool                      $forceDelete
     * @return bool
     */
    public function deletePage($page, $user, $reason, $forceDelete = false)
    {
        DB::beginTransaction();

        try {
            if(Page::where('parent_id', $page->id)->count()) throw new \Exception('A page exists with this as its parent. Please remove or reassign the page\'s parentage first.');

            // Unset the parent ID of any pages with this as their parent
            // This should not be relevant given the check above, but just in case
            if(Page::where('parent_id', $page->id)->count())
                Page::where('parent_id', $page->id)->update([
                    'parent_id' => null
            ]);

            if($forceDelete) {
                // Delete the page's versions
                $page->versions()->delete();

                // Check to see if any images are linked only to this page,
                // and if so, force delete them
                foreach($page->images()->withTrashed()->get() as $image)
                    if($image->pages->count() == 1) {
                        if(!(new ImageManager)->deletePageImage($image, true)) throw new \Exception('An error occurred deleting an image.');
                    }

                // Detach any remaining images
                $page->images()->detach();

                // Finally, force-delete the page
                $page->forceDelete();
            }
            else {
                // Check to see if any images are linked only to this page,
                // and if so, soft-delete them
                foreach($page->images as $image)
                    if($image->pages->count() == 1) {
                        if(!(new ImageManager)->deletePageImage($image)) throw new \Exception('An error occurred deleting an image.');
                    }

                // Create a version logging the deletion
                $version = $this->logPageVersion($page->id, $user->id, 'Page Deleted', $reason, $page->version->data, false);
                if(!$version) throw Exception('An error occurred while saving page version.');

                // Delete the page
                $page->delete();
            }

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Restore a deleted page.
     *
     * @param  \App\Models\Page\Page     $page
     * @param  \App\Models\User\User     $user
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
            foreach($page->images()->withTrashed()->whereNotNull('deleted_at')->get() as $image)
            if($image->pages()->count() == 1) {
                if(!(new ImageManager)->restorePageImage($image, $user)) throw new \Exception('An error occurred restoring an image.');
            }

            // Finally, create a version logging the restoration
            $version = $this->logPageVersion($page->id, $user->id, 'Page Restored', $reason, $page->version->data, false);
            if(!$version) throw Exception('An error occurred while saving page version.');

            return $this->commitReturn($page);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Processes page data for storage.
     *
     * @param  array                     $data
     * @param  App\Models\Page\Page      $page
     * @return array
     */
    private function processPageData($data, $page = null)
    {
        // Fetch category-- either from the page if it already exists, or from the category ID
        $category = $page ? $page->category : SubjectCategory::where('id', $data['category_id'])->first();

        // Record the introduction as necessary
        $data['data']['description'] = isset($data['description']) ? $data['description'] : null;
        if(!isset($data['is_visible'])) $data['is_visible'] = 0;

        // Cycle through the category's form fields
        // Data is recorded here in a flat array/only according to key, as keys should not
        // be duplicated in a template, and the template accounts for form building as well
        // as page formatting
        foreach($category->formFields as $key=>$field)
            $data['data'][$key] = isset($data[$key]) ? $data[$key] : null;

        // Process any subject-specific data
        switch($category->subject['key']) {
            case 'people';
                // Record name
                $data['data']['people_name'] = isset($data['people_name']) ? $data['people_name'] : null;

                // Record birth and death data
                foreach(['birth', 'death'] as $segment) {
                    if(isset($data[$segment.'_place_id']) || isset($data[$segment.'_chronology_id'])) $data['data'][$segment] = [
                        'place' => isset($data[$segment.'_place_id']) ? $data[$segment.'_place_id'] : null,
                        'chronology' => isset($data[$segment.'_chronology_id']) ? $data[$segment.'_chronology_id'] : null
                    ];
                    foreach((new TimeDivision)->dateFields() as $key=>$field)
                        if(isset($data[$segment.'_'.$key])) $data['data'][$segment]['date'][$key] = isset($data[$segment.'_'.$key]) ? $data[$segment.'_'.$key] : null;
                }
                break;
            case 'places';
                // Record parent location
                $data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : null;
                break;
            case 'time';
                // Record chronology
                $data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : null;
                // Record dates
                foreach(['start', 'end'] as $segment) {
                    foreach((new TimeDivision)->dateFields() as $key=>$field)
                        if(isset($data['date_'.$segment.'_'.$key])) $data['data']['date'][$segment][$key] = isset($data['date_'.$segment.'_'.$key]) ? $data['date_'.$segment.'_'.$key] : null;
                }
                break;
        }

        return $data;
    }

    /**
     * Processes version data for storage.
     *
     * @param  array                 $data
     * @return array
     */
    private function processVersionData($data)
    {
        $versionData = [];

        // Gather the general data for recording
        $versionData['data'] = $data['data'];

        // Cycle through various fields not present in data
        $versionData = $versionData + [
            'title' => $data['title'],
            'is_visible' => $data['is_visible'],
            'summary' => $data['summary']
        ];
        if(isset($data['parent_id']))
            $versionData = $versionData + ['parent_id' => $data['parent_id']];

        return $versionData;
    }

    /**
     * Records a new page version.
     *
     * @param  int                         $pageId
     * @param  int                         $userId
     * @param  string                      $type
     * @param  string                      $reason
     * @param  array                       $data
     * @param  bool                        $isMinor
     * @return \App\Models\Page\PageVersion|bool
     */
    public function logPageVersion($pageId, $userId, $type, $reason, $data, $isMinor = false)
    {
        try {
            $version = PageVersion::create([
                'page_id' => $pageId,
                'user_id' => $userId,
                'type' => $type,
                'reason' => $reason,
                'is_minor' => $isMinor,
                'data' => json_encode($data)
            ]);

            return $version;
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return false;
    }

}
