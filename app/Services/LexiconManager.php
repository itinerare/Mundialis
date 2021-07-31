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
use App\Models\Lexicon\LexiconEtymology;

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

            // Create entry
            $entry = LexiconEntry::create($data);

            // Process etymology data
            if(!$this->processEtymology($entry, $data)) throw new \Exception('An error occurred while creating etymology records.');

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

            // Process etymology data
            if(!$this->processEtymology($entry, $data)) throw new \Exception('An error occurred while creating etymology records.');

            // Update entry
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

    /**
     * Processes etymology data.
     *
     * @param   \App\Models\Lexicon\LexiconEntry  $entry
     * @param  array                              $data
     * @return array
     */
    private function processEtymology($entry, $data)
    {
        DB::beginTransaction();

        try {
            // Delete existing etymology records
            if($entry->etymologies->count())
                $entry->etymologies()->delete();

            if(isset($data['parent_id'])) {
                // Check that entries with the specified id(s) exist on site
                foreach($data['parent_id'] as $id) {
                    if(isset($id) && $id) {
                        $parent = LexiconEntry::find($id);
                        if(!$parent) throw new \Exception('One or more parent entries are invalid.');
                    }
                }

                // Create etymology record
                foreach($data['parent_id'] as $key=>$parent) {
                    if($parent || $data['parent'][$key]) {
                        $etymology = LexiconEtymology::create([
                            'entry_id' => $entry->id,
                            'parent_id' => isset($parent) ? $parent : null,
                            'parent' => !isset($parent) && isset($data['parent'][$key]) ? $data['parent'][$key] : null
                        ]);
                        if(!$etymology) throw new \Exception('An error occurred while creating an etymology record.');
                    }
                }
            }

            return $this->commitReturn($data);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

}
