<?php

namespace App\Services;

use App\Services\Service;

use DB;
use Image;
use Arr;
use Config;

use App\Models\User\User;
use App\Models\Page\Page;
use App\Models\Page\PageRelationship;

class RelationshipManager extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Image Manager
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of images.
    |
    */

    /**
     * Creates a relationship.
     *
     * @param  array                         $data
     * @param  \App\Models\Page\Page         $page
     * @param  \App\Models\User\User         $user
     * @return bool|\App\Models\Page\PageImage
     */
    public function createPageRelationship($data, $page, $user)
    {
        DB::beginTransaction();

        try {
            // Ensure that both pages exist
            $pageOne = $page;
            $pageTwo = Page::where('id', $data['page_two_id'])->first();
            if (!$pageOne || !$pageTwo) {
                throw new \Exception('One or both selected pages are invalid.');
            }

            // Ensure user can edit the parent page, and that it's of the appropriate subject
            if (!$user->canEdit($pageOne) || !$user->canEdit($pageTwo)) {
                throw new \Exception('You don\'t have permission to edit one or both of these pages.');
            }
            if ($pageOne->category->subject['key'] != 'people' || $pageTwo->category->subject['key'] != 'people') {
                throw new \Exception('Relationships can\'t be created between pages in this subject.');
            }

            // Create relationship
            $relationship = PageRelationship::create($data);

            return $this->commitReturn($relationship);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a relationship.
     *
     * @param  \App\Models\Page\PageRelationship  $relationship
     * @param  array                              $data
     * @param  \App\Models\User\User              $user
     * @return \App\Models\Page\Page|bool
     */
    public function updatePageRelationship($relationship, $data, $user)
    {
        DB::beginTransaction();

        try {
            // Ensure user can edit the parent pages
            if (!$user->canEdit($relationship->pageOne) || !$user->canEdit($relationship->pageTwo)) {
                throw new \Exception('You don\'t have permission to edit one or more of the relationship\'s pages.');
            }

            // Update image
            $relationship->update($data);

            return $this->commitReturn($relationship);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Delete a relationship.
     *
     * @param  \App\Models\Page\PageRelationship  $relationship
     * @param  \App\Models\User\User              $user
     * @return bool
     */
    public function deletePageRelationship($relationship, $user)
    {
        DB::beginTransaction();

        try {
            // Ensure user can edit the parent pages
            if (!$user->canEdit($relationship->pageOne) || !$user->canEdit($relationship->pageTwo)) {
                throw new \Exception('You don\'t have permission to edit one or more of the relationship\'s pages.');
            }

            // Delete the relationship
            $relationship->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }
}
