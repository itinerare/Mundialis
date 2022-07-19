<?php

namespace App\Services;

use App\Models\Page\Page;
use App\Models\Subject\LexiconCategory;
use App\Models\Subject\LexiconSetting;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\SubjectTemplate;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\TimeDivision;
use DB;

class SubjectService extends Service {
    /*
    |--------------------------------------------------------------------------
    | Subject Service
    |--------------------------------------------------------------------------
    |
    | Handles the editing of subject templates and creation of categories.
    |
    */

    /**
     * Updates a subject template.
     *
     * @param string                $subject
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\SubjectTemplate|bool
     */
    public function editTemplate($subject, $data, $user) {
        DB::beginTransaction();

        try {
            // Record subject
            $data['subject'] = $subject;
            $template = SubjectTemplate::where('subject', $subject)->first();

            // Collect and record infobox and form fields
            $data = $this->processTemplateData($data);

            // Check if changes should cascade, and if so, perform comparison
            // and make updates as necessary
            if ($template && (isset($data['cascade_template']) && $data['cascade_template']) && $data['data'] != $template->data) {
                // First find any impacted categories
                $categories = $template->categories()->whereNotNull('data')->get();

                // Collect existing template data
                $data['old'] = $template->data;

                // Cascade changes to impacted categories
                $this->cascadeTemplateChanges($categories, $data);
            }

            // Encode data before saving either way, for convenience
            if (isset($data['data'])) {
                $data['data'] = json_encode($data['data']);
            } else {
                $data['data'] = null;
            }

            // Either create or update template data
            if (!$template) {
                $template = SubjectTemplate::create($data);
            } else {
                $template->update($data);
            }

            return $this->commitReturn($template);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Creates a category.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     * @param string                $subject
     *
     * @return \App\Models\Subject\SubjectCategory|bool
     */
    public function createCategory($data, $user, $subject) {
        DB::beginTransaction();

        try {
            // Record subject
            $data['subject'] = $subject;

            // Check for image
            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $image = $data['image'];
                unset($data['image']);
            } else {
                $data['has_image'] = 0;
            }

            // Collect and record infobox and form fields
            $data = $this->processTemplateData($data);

            // Overwrite with data from subject template if necessary
            if (isset($data['populate_template']) && $data['populate_template']) {
                if (isset($data['parent_id'])) {
                    $parent = SubjectCategory::find($data['parent_id']);
                }
                $data['data'] = isset($parent) && $parent ? $parent->data : SubjectTemplate::where('subject', $subject)->first()->data;
            }

            // Encode data before saving either way, for convenience
            if (isset($data['data'])) {
                $data['data'] = json_encode($data['data']);
            } else {
                $data['data'] = null;
            }

            // Create category
            $category = SubjectCategory::create($data);

            // Handle image
            if ($image) {
                $this->handleImage($image, $category->imagePath, $category->imageFileName);
            }

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a category.
     *
     * @param \App\Models\Subject\SubjectCategory $category
     * @param array                               $data
     * @param \App\Models\User\User               $user
     *
     * @return \App\Models\Subject\SubjectCategory|bool
     */
    public function updateCategory($category, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (SubjectCategory::where('name', $data['name'])->where('id', '!=', $category->id)->exists()) {
                throw new \Exception('The name has already been taken.');
            }

            // Check to see if an existing image should be removed
            if (isset($data['remove_image'])) {
                if ($category->has_image && $data['remove_image']) {
                    $data['has_image'] = 0;
                    $this->deleteImage($category->imagePath, $category->imageFileName);
                }
                unset($data['remove_image']);
            }

            // Check for image
            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $image = $data['image'];
                unset($data['image']);
            }

            // Collect and record template information
            $data = $this->processTemplateData($data);

            // Overwrite with data from subject template if necessary
            if (isset($data['populate_template']) && $data['populate_template']) {
                $data['data'] = $category->parent ? $category->parent->data : $category->subjectTemplate->data;
            }

            // Check if changes should cascade, and if so, perform comparison
            // and make updates as necessary
            if ((isset($data['cascade_template']) && $data['cascade_template']) && $data['data'] != $category->data) {
                // Collect existing template data
                $data['old'] = $category->data;

                // Find any impacted categories
                $categories = $category->children()->whereNotNull('data')->get();

                // Check if changes should be cascaded recursively (to the childrens' children)
                if (isset($data['cascade_recursively']) && $data['cascade_recursively']) {
                    $this->cascadeTemplateChangesRecursively($categories, $data);
                } else {
                    // Cascade changes to impacted categories
                    $this->cascadeTemplateChanges($categories, $data);
                }
            }

            // Encode data before saving either way, for convenience
            if (isset($data['data'])) {
                $data['data'] = json_encode($data['data']);
            } else {
                $data['data'] = null;
            }

            // Update category
            $category->update($data);

            // Handle image
            if ($image) {
                $this->handleImage($image, $category->imagePath, $category->imageFileName);
            }

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete a category.
     *
     * @param \App\Models\Subject\SubjectCategory $category
     * @param \App\Models\User\User               $user
     *
     * @return bool
     */
    public function deleteCategory($category, $user) {
        DB::beginTransaction();

        try {
            // Check first if the category is currently in use
            if (SubjectCategory::where('parent_id', $category->id)->exists()) {
                throw new \Exception('A sub-category of this category exists. Please move or delete it first.');
            }
            if (Page::where('category_id', $category->id)->exists()) {
                throw new \Exception('A page in this category exists. Please move or delete it first.');
            }

            // Permanently delete any remaining pages and associated data in the category,
            // as without the category/its data they will not be recoverable anyway
            if ($category->pages()->withTrashed()->count()) {
                foreach ($category->pages()->withTrashed()->get() as $page) {
                    if (!(new PageManager)->deletePage($page, $user, null, true)) {
                        throw new \Exception('Failed to force delete page.');
                    }
                }
            }
            // Delete the categroy
            $category->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts category order.
     *
     * @param array  $data
     * @param string $subject
     *
     * @return bool
     */
    public function sortCategory($data, $subject) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                SubjectCategory::where('subject', $subject)->where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /******************************************************************************
        SPECIALIZED - TIME
    *******************************************************************************/

    /**
     * Updates time divisions.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\TimeDivision|bool
     */
    public function editTimeDivisions($data, $user) {
        DB::beginTransaction();

        try {
            if (isset($data['name'])) {
                // Process each entered division
                foreach ($data['name'] as $key=>$name) {
                    // More specific validation
                    foreach ($data['name'] as $subKey=>$subName) {
                        if ($subName == $name && $subKey != $key) {
                            throw new \Exception('The name has already been taken.');
                        }
                    }

                    if (isset($data['id'][$key])) {
                        $division = TimeDivision::find($data['id'][$key]);
                    } else {
                        $division = null;
                    }

                    // Assemble data
                    $data[$key] = [
                        'name'          => $data['name'][$key],
                        'abbreviation'  => $data['abbreviation'][$key] ?? null,
                        'unit'          => $data['unit'][$key] ?? null,
                        'use_for_dates' => $division && (isset($data['use_for_dates'][$division->id]) && $data['use_for_dates'][$division->id]) ? 1 : 0,
                    ];

                    // Create or update division data
                    if (!$division) {
                        $divisions[] = TimeDivision::create($data[$key]);
                    } else {
                        $division->update($data[$key]);
                        $divisions[] = $division;
                    }
                }

                // Process sort information
                if (isset($data['sort'])) {
                    // explode the sort array and reverse it since the order is inverted
                    $sort = array_reverse(explode(',', $data['sort']));

                    foreach ($sort as $key => $s) {
                        TimeDivision::where('id', $s)->update(['sort' => $key]);
                    }
                }

                // Remove divisions not present in the form data
                TimeDivision::whereNotIn('name', $data['name'])->delete();
            } else {
                TimeDivision::query()->delete();
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Creates a chronology.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\TimeChronology|bool
     */
    public function createChronology($data, $user) {
        DB::beginTransaction();

        try {
            // Create chronology
            $chronology = TimeChronology::create($data);

            return $this->commitReturn($chronology);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a chronology.
     *
     * @param \App\Models\Subject\TimeChronology $chronology
     * @param array                              $data
     * @param \App\Models\User\User              $user
     *
     * @return \App\Models\Subject\TimeChronology|bool
     */
    public function updateChronology($chronology, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (TimeChronology::where('name', $data['name'])->where('id', '!=', $chronology->id)->exists()) {
                throw new \Exception('The name has already been taken.');
            }

            // Update chronology
            $chronology->update($data);

            return $this->commitReturn($chronology);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete a chronology.
     *
     * @param \App\Models\Subject\TimeChronology $chronology
     *
     * @return bool
     */
    public function deleteChronology($chronology) {
        DB::beginTransaction();

        try {
            // Check first if the project is currently in use
            if (TimeChronology::where('parent_id', $chronology->id)->exists()) {
                throw new \Exception('A sub-chronology of this chronology exists. Please move or delete it first.');
            }
            //if(Piece::where('project_id', $project->id)->exists()) throw new \Exception("A piece with this chronology exists. Please move or delete it first.");

            $chronology->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts chronology order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortChronology($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                TimeChronology::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /******************************************************************************
        SPECIALIZED - LANGUAGE
    *******************************************************************************/

    /**
     * Updates lexicon settings.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\LexiconSetting|bool
     */
    public function editLexiconSettings($data, $user) {
        DB::beginTransaction();

        try {
            if (isset($data['name'])) {
                // Process each entered division
                foreach ($data['name'] as $key=>$name) {
                    // More specific validation
                    foreach ($data['name'] as $subKey=>$subName) {
                        if ($subName == $name && $subKey != $key) {
                            throw new \Exception('The name has already been taken.');
                        }
                    }

                    if (isset($data['id'][$key])) {
                        $setting = LexiconSetting::find($data['id'][$key]);
                    } else {
                        $setting = null;
                    }

                    // Assemble data
                    $data[$key] = [
                        'name'         => $data['name'][$key],
                        'abbreviation' => $data['abbreviation'][$key] ?? null,
                    ];

                    // Create or update division data
                    if (!$setting) {
                        $settings[] = LexiconSetting::create($data[$key]);
                    } else {
                        $setting->update($data[$key]);
                        $settings[] = $setting;
                    }
                }

                // Process sort information
                if (isset($data['sort'])) {
                    // explode the sort array and reverse it since the order is inverted
                    $sort = array_reverse(explode(',', $data['sort']));

                    foreach ($sort as $key => $s) {
                        LexiconSetting::where('id', $s)->update(['sort' => $key]);
                    }
                }

                // Remove divisions not present in the form data
                LexiconSetting::whereNotIn('name', $data['name'])->delete();
            } else {
                LexiconSetting::query()->delete();
            }

            return $this->commitReturn($settings);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Creates a lexicon category.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\LexiconCategory|bool
     */
    public function createLexiconCategory($data, $user) {
        DB::beginTransaction();

        try {
            // Process data for storage
            $data = $this->processLexiconData($data);

            // Encode data before saving either way, for convenience
            if (isset($data['data'])) {
                $data['data'] = json_encode($data['data']);
            } else {
                $data['data'] = null;
            }

            // Create category
            $category = LexiconCategory::create($data);

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a lexicon category.
     *
     * @param \App\Models\Subject\LexiconCategory $category
     * @param array                               $data
     * @param \App\Models\User\User               $user
     *
     * @return \App\Models\Subject\LexiconCategory|bool
     */
    public function updateLexiconCategory($category, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (LexiconCategory::where('name', $data['name'])->where('id', '!=', $category->id)->exists()) {
                throw new \Exception('The name has already been taken.');
            }

            // Process data for storage
            $data = $this->processLexiconData($data, $category);

            // Overwrite with data from subject template if necessary
            if (isset($data['populate_settings']) && $data['populate_settings'] && $category->parent && isset($category->parent->data)) {
                $data['data'] = $category->parent->data;
            }

            // Encode data before saving either way, for convenience
            if (isset($data['data'])) {
                $data['data'] = json_encode($data['data']);
            } else {
                $data['data'] = null;
            }

            // Update category
            $category->update($data);

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a lexicon category.
     *
     * @param \App\Models\Subject\LexiconCategory $category
     *
     * @return bool
     */
    public function deleteLexiconCategory($category) {
        DB::beginTransaction();

        try {
            // Check first if the project is currently in use
            if (LexiconCategory::where('parent_id', $category->id)->exists()) {
                throw new \Exception('A sub-category of this category exists. Please move or delete it first.');
            }
            //if(Piece::where('project_id', $project->id)->exists()) throw new \Exception("A piece with this category exists. Please move or delete it first.");

            $category->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts lexicon category order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortLexiconCategory($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                LexiconCategory::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Processes template information.
     *
     * @param array $data
     *
     * @return array
     */
    private function processTemplateData($data) {
        // Collect and record sections if present
        if (isset($data['section_key'])) {
            foreach ($data['section_key'] as $key=>$section) {
                $data['data']['sections'][strtolower($section)] = [
                    'name' => $data['section_name'][$key],
                ];
            }
        }

        // Format and record infobox fields if present
        if (isset($data['infobox_key'])) {
            foreach ($data['infobox_key'] as $key=>$fieldKey) {
                if (isset($data['infobox_choices'][$key])) {
                    $data['infobox_choices'][$key] = explode(',', $data['infobox_choices'][$key]);
                }

                $data['data']['infobox'][$fieldKey] = [
                    'label'   => $data['infobox_label'][$key],
                    'type'    => $data['infobox_type'][$key],
                    'rules'   => $data['infobox_rules'][$key] ?? null,
                    'choices' => $data['infobox_choices'][$key] ?? null,
                    'value'   => $data['infobox_value'][$key] ?? null,
                    'help'    => $data['infobox_help'][$key] ?? null,
                ];
            }
        }

        // Format and record form fields if present
        if (isset($data['field_key'])) {
            foreach ($data['field_key'] as $key=>$fieldKey) {
                if (isset($data['field_choices'][$key])) {
                    $data['field_choices'][$key] = explode(',', $data['field_choices'][$key]);
                }

                $data['data']['fields'][$data['field_section'][$key]][$fieldKey] = [
                    'label'         => $data['field_label'][$key],
                    'type'          => $data['field_type'][$key],
                    'rules'         => $data['field_rules'][$key] ?? null,
                    'choices'       => $data['field_choices'][$key] ?? null,
                    'value'         => $data['field_value'][$key] ?? null,
                    'help'          => $data['field_help'][$key] ?? null,
                    'is_subsection' => $data['field_is_subsection'][$key],
                ];
            }
        }

        return $data;
    }

    /**
     * Cascades template changes.
     *
     * @param Illuminate\Database\Eloquent\Collection $categories
     * @param array                                   $data
     *
     * @return array
     */
    private function cascadeTemplateChangesRecursively($categories, $data) {
        $this->cascadeTemplateChanges($categories, $data);

        foreach ($categories as $category) {
            if ($category->children()->count()) {
                // Find any impacted categories
                $categories = $category->children()->whereNotNull('data')->get();

                // Cascade changes to impacted categories
                $this->cascadeTemplateChangesRecursively($categories, $data);
            }
        }

        return $data;
    }

    /**
     * Cascades template changes.
     *
     * @param Illuminate\Database\Eloquent\Collection $categories
     * @param array                                   $data
     *
     * @return array
     */
    private function cascadeTemplateChanges($categories, $data) {
        // Recursively compare arrays
        $data['changes']['added'] = $this->diff_recursive((array) $data['data'], (array) $data['old']);
        $data['changes']['removed'] = $this->diff_recursive((array) $data['old'], (array) $data['data']);

        // Perform operations on impacted categories
        foreach ($categories as $key=>$category) {
            $categoryData[$key] = $category->data;

            // Perform any removals
            if (isset($data['changes']['removed'])) {
                foreach ($data['changes']['removed'] as $segment=>$items) {
                    if ($segment == 'sections' || $segment == 'fields') {
                        // If segment is nested, step down first
                        foreach ($items as $section=>$sectionData) {
                            foreach ($sectionData as $itemKey=>$item) {
                                // Check to see if key exists in the array and unset if so
                                if (array_key_exists($itemKey, $category->data[$segment][$section])) {
                                    unset($categoryData[$key][$segment][$section][$itemKey]);
                                }
                            }
                            // Check for empty sections and if so remove them
                            if ($segment == 'sections' && isset($categoryData[$key][$segment][$section]) && $categoryData[$key][$segment][$section] == []) {
                                unset($categoryData[$key][$segment][$section]);
                            }
                        }
                    } else {
                        // If segment is not nested, simply proceed
                        if (isset($data['changes']['removed'][$segment]) && $data['changes']['removed'][$segment]) {
                            foreach ($items as $item) {
                                // Check to see if key exists in the array and unset if so
                                if (array_key_exists($item, $category->data[$segment])) {
                                    unset($categoryData[$key][$segment][$item]);
                                }
                            }
                        }
                    }
                }
            }

            // Recursively perform any additions
            $categoryData[$key] = array_replace_recursive($categoryData[$key], $data['changes']['added']);

            // Update the category
            $categoryData[$key] = json_encode($categoryData[$key]);
            $category->update(['data' => $categoryData[$key]]);
        }

        return $data;
    }

    /**
     * Processes lexicon category data for storage.
     *
     * @param array                              $data
     * @param App\Models\Subject\LexiconCategory $category
     *
     * @return array
     */
    private function processLexiconData($data, $category = null) {
        // Collect and record property and dimension information
        if (isset($data['property_name'])) {
            foreach ($data['property_name'] as $key=>$property) {
                $propertyKey[$key] = str_replace(' ', '_', strtolower($property));
                $data['data'][$data['property_class'][$key]]['properties'][$propertyKey[$key]] = [
                    'name'            => $property,
                    'non_dimensional' => isset($data['property_dimensions'][$key]) ? 0 : 1,
                    'dimensions'      => isset($data['property_dimensions'][$key]) ? explode(',', $data['property_dimensions'][$key]) : null,
                ];
            }
        }

        // Collect and record auto-conjugation/declension information
        if ($category && $category->id) {
            $combinations = [];

            // Cycle through lexical classes
            foreach (LexiconSetting::all() as $class) {
                // Gather the combinations for the class and category
                $combinations[$class->id] = $category->classCombinations($class->id);

                // Cycle through declension criteria
                if (isset($data['declension_criteria'][$class->id])) {
                    foreach ($data['declension_criteria'][$class->id] as $key=>$criteria) {
                        if ($criteria != null) {
                            // Perform some general validation, since it's easier to do this now
                            // Since there are many possible values that could throw an error,
                            // go out of the way to specify exactly which field has thrown it
                            if (!isset($data['declension_regex'][$class->id][$key])) {
                                throw new \Exception($class->name.' '.$combinations[$class->id][$key].' regex pattern missing! You must specify at least one pattern to replace.');
                            }
                            if (!isset($data['declension_replacement'][$class->id][$key])) {
                                throw new \Exception($class->name.' '.$combinations[$class->id][$key].' replacement missing! You must specify at least one string to use for replacement.');
                            }

                            // Assemble data itself, including exploding the selectize outputs
                            // as it's easiest to perform final checks on arrays
                            $data['data'][$class->id]['conjugation'][$key] = [
                                'criteria'    => explode(';', $criteria),
                                'regex'       => explode(';', $data['declension_regex'][$class->id][$key]),
                                'replacement' => explode(';', $data['declension_replacement'][$class->id][$key]),
                            ];

                            // Perform final check to see that criteria and replacements are 1:1
                            if (count($data['data'][$class->id]['conjugation'][$key]['criteria']) != count($data['data'][$class->id]['conjugation'][$key]['replacement'])) {
                                throw new \Exception($class->name.' '.$combinations[$class->id][$key].' has a different number of criteria and replacements! There must be the same number of each.');
                            }
                            // Regex must either be only one or 1:1. This is a little janky,
                            // but the likelihood of people caring about it significantly is low,
                            // and if so it can be reworked later
                            if (count($data['data'][$class->id]['conjugation'][$key]['regex']) > 1 && count($data['data'][$class->id]['conjugation'][$key]['criteria']) != count($data['data'][$class->id]['conjugation'][$key]['regex'])) {
                                throw new \Exception($class->name.' '.$combinations[$class->id][$key].' has a different number of critera and regex patterns! There must either be only one regex pattern for replacement, or the same number as criteria and replacements.');
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }
}
