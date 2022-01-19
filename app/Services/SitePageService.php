<?php

namespace App\Services;

use DB;

class SitePageService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Text Page Service
    |--------------------------------------------------------------------------
    |
    | Handles the editing of text pages. Creation is handled by the associated
    | command, as pages are used exclusively for basic site info.
    |
    */

    /**
     * Updates a text page.
     *
     * @param \App\Models\TextPage  $page
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\TextPage|bool
     */
    public function updatePage($page, $data, $user)
    {
        DB::beginTransaction();

        try {
            $page->update($data);

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }
}
