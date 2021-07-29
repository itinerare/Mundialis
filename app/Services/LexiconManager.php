<?php namespace App\Services;

use App\Services\Service;

use DB;
use Image;
use Arr;
use Config;

use App\Models\User\User;

use App\Models\Subject\LexiconCategory;
use App\Models\Subject\LexiconSetting;
use App\Models\Lexicon\LexiconEntry;

class LexiconManager extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Lexicon Manager
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of lexicon entries.
    |
    */

    /**
     * Creates a lexicon entry.
     *
     * @param  array                                 $data
     * @param  \App\Models\User\User                 $user
     * @return bool|\App\Models\Lexicon\LexiconEntry
     */
    public function createLexiconEntry($data, $user)
    {
        DB::beginTransaction();

        try {
            // Process toggles
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;

            $entry = LexiconEntry::create($data);

            return $this->commitReturn($entry);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a lexicon entry.
     *
     * @param  \App\Models\Lexicon\LexiconEntry      $entry
     * @param  array                                 $data
     * @param  \App\Models\User\User                 $user
     * @return \App\Models\Lexicon\LexiconEntry|bool
     */
    public function updateLexiconEntry($entry, $data, $user)
    {
        DB::beginTransaction();

        try {
            // Process toggles
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;

            // Update image
            $entry->update($data);

            return $this->commitReturn($entry);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a lexicon entry.
     *
     * @param  \App\Models\Lexicon\LexiconEntry  $entry
     * @param  \App\Models\User\User             $user
     * @return bool
     */
    public function deleteLexiconEntry($entry, $user)
    {
        DB::beginTransaction();

        try {
            // Delete the entry
            $entry->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

}
