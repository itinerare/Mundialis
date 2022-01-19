<?php

namespace App\Services;

use App\Models\Lexicon\LexiconEntry;
use App\Models\Lexicon\LexiconEtymology;
use App\Models\Page\PageLink;
use DB;

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
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return bool|\App\Models\Lexicon\LexiconEntry
     */
    public function createLexiconEntry($data, $user)
    {
        DB::beginTransaction();

        try {
            // Process toggles
            if (!isset($data['is_visible'])) {
                $data['is_visible'] = 0;
            }

            // Create entry
            $entry = LexiconEntry::create($data);

            if (isset($data['definition'])) {
                if (!$parseData = $this->parse_wiki_links((array) $data['definition'])) {
                    throw new \Exception('An error occurred while parsing links.');
                }
                $data['parsed_definition'] = $parseData['parsed'][0];

                if (isset($parseData['links'])) {
                    foreach ($parseData['links'] as $link) {
                        if (isset($link['link_id']) || isset($link['title'])) {
                            $link = PageLink::create([
                            'parent_id'   => $entry->id,
                            'parent_type' => 'entry',
                            'link_id'     => isset($link['link_id']) ? $link['link_id'] : null,
                            'title'       => isset($link['title']) && !isset($link['link_id']) ? $link['title'] : null,
                        ]);
                            if (!$link) {
                                throw new \Exception('An error occurred while creating a link.');
                            }
                        }
                    }
                }
            } else {
                $data['parsed_definition'] = null;
            }
            $entry->update($data);

            // Process etymology data
            if (!$this->processEtymology($entry, $data)) {
                throw new \Exception('An error occurred while creating etymology records.');
            }

            return $this->commitReturn($entry);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a lexicon entry.
     *
     * @param \App\Models\Lexicon\LexiconEntry $entry
     * @param array                            $data
     * @param \App\Models\User\User            $user
     *
     * @return \App\Models\Lexicon\LexiconEntry|bool
     */
    public function updateLexiconEntry($entry, $data, $user)
    {
        DB::beginTransaction();

        try {
            // Process toggles
            if (!isset($data['is_visible'])) {
                $data['is_visible'] = 0;
            }

            if (isset($data['definition'])) {
                if (!$parseData = $this->parse_wiki_links((array) $data['definition'])) {
                    throw new \Exception('An error occurred while parsing links.');
                }
                $data['parsed_definition'] = $parseData['parsed'][0];

                // If the page already has links...
                if ($entry->links()->count()) {
                    $entry->links()->delete();
                }

                if (isset($parseData['links'])) {
                    foreach ($parseData['links'] as $link) {
                        if ((isset($link['link_id']) && !$entry->links()->where('link_id', $link['link_id'])->first()) || (isset($link['title']) && !$entry->links()->where('title', $link['title'])->first())) {
                            $link = PageLink::create([
                            'parent_id'   => $entry->id,
                            'parent_type' => 'entry',
                            'link_id'     => isset($link['link_id']) ? $link['link_id'] : null,
                            'title'       => isset($link['title']) && !isset($link['link_id']) ? $link['title'] : null,
                        ]);
                            if (!$link) {
                                throw new \Exception('An error occurred while creating a link.');
                            }
                        }
                    }
                }
            } else {
                $data['parsed_definition'] = null;
            }

            // Process etymology data
            if (isset($data['conjdecl'])) {
                // Process conjugation/declension data
                if ($entry->category) {
                    $data['data'] = $this->processConjData($entry, $data);
                }
            }

            if (!$this->processEtymology($entry, $data)) {
                throw new \Exception('An error occurred while creating etymology records.');
            }

            // Update entry
            $entry->update($data);

            return $this->commitReturn($entry);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a lexicon entry.
     *
     * @param \App\Models\Lexicon\LexiconEntry $entry
     * @param \App\Models\User\User            $user
     *
     * @return bool
     */
    public function deleteLexiconEntry($entry, $user)
    {
        DB::beginTransaction();

        try {
            if (LexiconEtymology::where('parent_id', $entry->id)->exists()) {
                throw new \Exception('This entry has child words. Please remove them before deleting this entry.');
            }

            // Delete any etymologies associated with the entry
            $entry->etymologies()->delete();

            // Delete the entry
            $entry->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Processes etymology data.
     *
     * @param \App\Models\Lexicon\LexiconEntry $entry
     * @param array                            $data
     *
     * @return array
     */
    private function processEtymology($entry, $data)
    {
        DB::beginTransaction();

        try {
            // Delete existing etymology records
            if ($entry->etymologies->count()) {
                $entry->etymologies()->delete();
            }

            if (isset($data['parent_id'])) {
                // Check that entries with the specified id(s) exist on site
                foreach ($data['parent_id'] as $id) {
                    if (isset($id) && $id) {
                        $parent = LexiconEntry::find($id);
                        if (!$parent) {
                            throw new \Exception('One or more parent entries are invalid.');
                        }
                    }
                }

                // Create etymology record
                foreach ($data['parent_id'] as $key=>$parent) {
                    if ($parent || $data['parent'][$key]) {
                        $etymology = LexiconEtymology::create([
                            'entry_id'  => $entry->id,
                            'parent_id' => isset($parent) ? $parent : null,
                            'parent'    => !isset($parent) && isset($data['parent'][$key]) ? $data['parent'][$key] : null,
                        ]);
                        if (!$etymology) {
                            throw new \Exception('An error occurred while creating an etymology record.');
                        }
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
     * Processes conjugation/declension data.
     *
     * @param \App\Models\Lexicon\LexiconEntry $entry
     * @param array                            $data
     *
     * @return array
     */
    private function processConjData($entry, $data)
    {
        if (!isset($data['autoconj'])) {
            $data['autoconj'] = 0;
        }

        // Auto-conjugation/declension
        if ($data['autoconj']) {
            $conjData = isset($entry->category->data[$entry->lexicalClass->id]['conjugation']) ? $entry->category->data[$entry->lexicalClass->id]['conjugation'] : null;

            // This option should only be offered in the first place if the data exists,
            // but as a safeguard, double-check
            if (isset($conjData) && $conjData) {
                // Cycle through combinations for the category
                foreach ($entry->category->classCombinations($entry->lexicalClass->id) as $key=>$combination) {
                    // If this is the first combination and there are no settings for it,
                    // Substitute in the word itself
                    if ($key == 0 && !isset($conjData[$key])) {
                        $data['conjdecl'][$combination] = $entry->word;
                    }

                    // Otherwise, check to see if instructions exist, then process the word
                    elseif (isset($conjData[$key])) {
                        foreach ($conjData[$key]['criteria'] as $conjKey=>$criteria) {
                            $matches = [];
                            preg_match('/'.$criteria.'/', $entry->word, $matches);
                            if ($matches != []) {
                                $data['conjdecl'][$combination] = preg_replace(isset($conjData[$key]['regex'][$conjKey]) ? '/'.$conjData[$key]['regex'][$conjKey].'/' : '/'.$conjData[$key]['regex'][0].'/', $conjData[$key]['replacement'][$conjKey], lcfirst($entry->word));
                                if ($entry->word != lcfirst($entry->word)) {
                                    $data['conjdecl'][$combination] = ucfirst($data['conjdecl'][$combination]);
                                }
                                break;
                            } else {
                                $data['conjdecl'][$combination] = null;
                            }
                        }
                    }
                }
            }
        }

        // Process inputs for recording
        $data['data'] = json_encode($data['conjdecl']);

        return $data['data'];
    }
}
